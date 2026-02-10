<?php

namespace app\controllers;

use app\models\Objet;
use app\models\Category;
use flight\Engine;

/**
 * UserObjetController - Gestion des objets par l'utilisateur connecté
 */
class UserObjetController
{
    private Engine $app;
    private Objet $objetModel;
    private Category $categoryModel;

    public function __construct(Engine $app)
    {
        $this->app = $app;
        $this->objetModel = new Objet($app->db());
        $this->categoryModel = new Category($app->db());
    }

    /**
     * Vérifier si l'utilisateur est connecté
     */
    private function requireAuth(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['logged_in']) || empty($_SESSION['user_id'])) {
            $this->app->redirect('/user/login');
            exit;
        }
    }

    /**
     * Récupérer l'ID de l'utilisateur connecté
     */
    private function getUserId(): int
    {
        return (int) ($_SESSION['user_id'] ?? 0);
    }

    /**
     * Afficher la liste des objets de l'utilisateur
     */
    public function index(): void
    {
        $this->requireAuth();

        $userId = $this->getUserId();
        $objets = $this->objetModel->getObjetForUser($userId);
        
        $successMessage = $_SESSION['success_message'] ?? null;
        $errorMessage = $_SESSION['error_message'] ?? null;
        unset($_SESSION['success_message'], $_SESSION['error_message']);

        $this->app->render('user/objets/index', [
            'objets' => $objets,
            'success_message' => $successMessage,
            'error_message' => $errorMessage,
            'username' => $_SESSION['username'] ?? 'Utilisateur',
            'csp_nonce' => $this->app->get('csp_nonce')
        ]);
    }

    /**
     * Afficher le formulaire de création d'un objet
     */
    public function create(): void
    {
        $this->requireAuth();

        $categories = $this->categoryModel->getAll();

        $this->app->render('user/objets/create', [
            'categories' => $categories,
            'username' => $_SESSION['username'] ?? 'Utilisateur',
            'csp_nonce' => $this->app->get('csp_nonce')
        ]);
    }

    /**
     * Enregistrer un nouvel objet
     */
    public function store(): void
    {
        $this->requireAuth();

        $userId = $this->getUserId();
        $title = trim($this->app->request()->data->title ?? '');
        $description = trim($this->app->request()->data->description ?? '');
        $idCategorie = $this->app->request()->data->id_categorie ?? '';
        $prixEstime = $this->app->request()->data->prix_estime ?? '';
        $qtt = (int) ($this->app->request()->data->qtt ?? 1);

        // Validation
        if (empty($title)) {
            $_SESSION['error_message'] = 'Le titre de l\'objet est requis.';
            $this->app->redirect('/user/objets/create');
            return;
        }

        // Convertir les valeurs optionnelles
        $idCategorie = $idCategorie !== '' ? (int) $idCategorie : null;
        $prixEstime = $prixEstime !== '' ? (float) $prixEstime : null;
        $qtt = $qtt > 0 ? $qtt : 1;

        // Créer l'objet
        $objetId = $this->objetModel->create([
            'title' => $title,
            'description' => $description ?: null,
            'id_proprietaire' => $userId,
            'id_categorie' => $idCategorie,
            'prix_estime' => $prixEstime,
            'qtt' => $qtt
        ]);

        if ($objetId) {
            // Gérer l'upload des images
            $this->handleImageUpload($objetId);
            $_SESSION['success_message'] = 'Objet "' . htmlspecialchars($title) . '" créé avec succès !';
        } else {
            $_SESSION['error_message'] = 'Erreur lors de la création de l\'objet.';
        }

        $this->app->redirect('/user/objets');
    }

    /**
     * Afficher le formulaire d'édition d'un objet
     */
    public function edit(int $id): void
    {
        $this->requireAuth();

        $userId = $this->getUserId();
        $objet = $this->objetModel->findById($id);

        if ($objet === null || (int) $objet['id_proprietaire'] !== $userId) {
            $_SESSION['error_message'] = 'Objet introuvable ou vous n\'êtes pas autorisé à le modifier.';
            $this->app->redirect('/user/objets');
            return;
        }

        $categories = $this->categoryModel->getAll();
        $images = $this->objetModel->getAllImageByObjetId($id);

        $this->app->render('user/objets/edit', [
            'objet' => $objet,
            'categories' => $categories,
            'images' => $images,
            'username' => $_SESSION['username'] ?? 'Utilisateur',
            'csp_nonce' => $this->app->get('csp_nonce')
        ]);
    }

    /**
     * Mettre à jour un objet
     */
    public function update(): void
    {
        $this->requireAuth();

        $userId = $this->getUserId();
        $objetId = (int) ($this->app->request()->data->objet_id ?? 0);
        
        $objet = $this->objetModel->findById($objetId);

        if ($objet === null || (int) $objet['id_proprietaire'] !== $userId) {
            $_SESSION['error_message'] = 'Objet introuvable ou vous n\'êtes pas autorisé à le modifier.';
            $this->app->redirect('/user/objets');
            return;
        }

        $title = trim($this->app->request()->data->title ?? '');
        $description = trim($this->app->request()->data->description ?? '');
        $idCategorie = $this->app->request()->data->id_categorie ?? '';
        $prixEstime = $this->app->request()->data->prix_estime ?? '';
        $qtt = (int) ($this->app->request()->data->qtt ?? 1);

        // Validation
        if (empty($title)) {
            $_SESSION['error_message'] = 'Le titre de l\'objet est requis.';
            $this->app->redirect('/user/objets/' . $objetId . '/edit');
            return;
        }

        // Convertir les valeurs optionnelles
        $idCategorie = $idCategorie !== '' ? (int) $idCategorie : null;
        $prixEstime = $prixEstime !== '' ? (float) $prixEstime : null;
        $qtt = $qtt > 0 ? $qtt : 1;

        // Mettre à jour l'objet
        $this->objetModel->update($objetId, [
            'title' => $title,
            'description' => $description ?: null,
            'id_categorie' => $idCategorie,
            'prix_estime' => $prixEstime,
            'qtt' => $qtt
        ]);

        // Gérer l'upload des nouvelles images
        $this->handleImageUpload($objetId);

        $_SESSION['success_message'] = 'Objet "' . htmlspecialchars($title) . '" mis à jour avec succès !';
        $this->app->redirect('/user/objets');
    }

    /**
     * Supprimer un objet
     */
    public function delete(): void
    {
        $this->requireAuth();

        $userId = $this->getUserId();
        $objetId = (int) ($this->app->request()->data->objet_id ?? 0);

        $objet = $this->objetModel->findById($objetId);

        if ($objet === null || (int) $objet['id_proprietaire'] !== $userId) {
            $_SESSION['error_message'] = 'Objet introuvable ou vous n\'êtes pas autorisé à le supprimer.';
            $this->app->redirect('/user/objets');
            return;
        }

        // Supprimer les images associées
        $images = $this->objetModel->getAllImageByObjetId($objetId);
        foreach ($images as $image) {
            $this->deleteImageFile($image['image_path']);
        }
        $this->objetModel->deleteAllImagesByObjetId($objetId);

        // Supprimer l'objet
        if ($this->objetModel->delete($objetId)) {
            $_SESSION['success_message'] = 'Objet supprimé avec succès !';
        } else {
            $_SESSION['error_message'] = 'Erreur lors de la suppression de l\'objet.';
        }

        $this->app->redirect('/user/objets');
    }

    /**
     * Supprimer une image d'un objet
     */
    public function deleteImage(): void
    {
        $this->requireAuth();

        $userId = $this->getUserId();
        $imageId = (int) ($this->app->request()->data->image_id ?? 0);
        $objetId = (int) ($this->app->request()->data->objet_id ?? 0);

        error_log("deleteImage called: imageId=$imageId, objetId=$objetId, userId=$userId");

        // Vérifier que l'objet appartient à l'utilisateur
        $objet = $this->objetModel->findById($objetId);
        if ($objet === null || (int) $objet['id_proprietaire'] !== $userId) {
            $_SESSION['error_message'] = 'Vous n\'êtes pas autorisé à supprimer cette image.';
            $this->app->redirect('/user/objets');
            return;
        }

        // Récupérer l'image
        $image = $this->objetModel->getImageById($imageId);
        error_log("Image found: " . ($image ? json_encode($image) : 'null'));
        
        if ($image === null || (int) $image['id_objet'] !== $objetId) {
            $_SESSION['error_message'] = 'Image introuvable.';
            $this->app->redirect('/user/objets/' . $objetId . '/edit');
            return;
        }

        // Supprimer le fichier
        $this->deleteImageFile($image['image_path']);

        // Supprimer l'enregistrement en base
        $deleted = $this->objetModel->deleteImageById($imageId);
        error_log("Image deleted from DB: " . ($deleted ? 'true' : 'false'));

        $_SESSION['success_message'] = 'Image supprimée avec succès !';
        $this->app->redirect('/user/objets/' . $objetId . '/edit');
    }

    /**
     * Gérer l'upload des images
     */
    private function handleImageUpload(int $objetId): void
    {
        if (!isset($_FILES['images']) || empty($_FILES['images']['name'][0])) {
            return;
        }

        // Utiliser le chemin absolu basé sur le dossier public
        $publicDir = dirname($_SERVER['SCRIPT_FILENAME']);
        $uploadDir = $publicDir . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;
        
        // Créer le dossier s'il n'existe pas
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5 Mo

        $files = $_FILES['images'];
        $fileCount = count($files['name']);

        for ($i = 0; $i < $fileCount; $i++) {
            if ($files['error'][$i] !== UPLOAD_ERR_OK) {
                error_log("Upload error for file $i: " . $files['error'][$i]);
                continue;
            }

            $tmpName = $files['tmp_name'][$i];
            $fileName = $files['name'][$i];
            $fileSize = $files['size'][$i];
            
            // Vérifier que le fichier temporaire existe
            if (!file_exists($tmpName)) {
                error_log("Temp file does not exist: $tmpName");
                continue;
            }
            
            $fileType = mime_content_type($tmpName);

            // Validation
            if (!in_array($fileType, $allowedTypes)) {
                error_log("Invalid file type: $fileType for file $fileName");
                continue;
            }

            if ($fileSize > $maxSize) {
                error_log("File too large: $fileSize bytes for file $fileName");
                continue;
            }

            // Générer un nom unique
            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $newFileName = 'objet_' . $objetId . '_' . uniqid() . '.' . $extension;
            $destination = $uploadDir . $newFileName;

            if (move_uploaded_file($tmpName, $destination)) {
                // Enregistrer en base
                $this->objetModel->addImage($objetId, $newFileName);
                error_log("Image uploaded successfully: $newFileName");
            } else {
                error_log("Failed to move uploaded file to: $destination");
            }
        }
    }

    /**
     * Supprimer un fichier image
     */
    private function deleteImageFile(string $imagePath): void
    {
        $publicDir = dirname($_SERVER['SCRIPT_FILENAME']);
        $fullPath = $publicDir . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . $imagePath;
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
}
