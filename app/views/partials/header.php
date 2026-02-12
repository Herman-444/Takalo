<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$currentPath = $_SERVER['REQUEST_URI'] ?? '';
?>
<!-- Top Bar -->
<div class="top-bar">
    <div class="container">
        <div class="top-bar-left">
            <span>🔄 Plateforme d'échange d'objets</span>
            <span>📍 Madagascar</span>
        </div>
        <div class="top-bar-right">
            <?php if (!empty($_SESSION['logged_in'])): ?>
                <span>👋 Bonjour, <?= htmlspecialchars($_SESSION['username'] ?? 'Utilisateur', ENT_QUOTES, 'UTF-8') ?></span>
            <?php else: ?>
                <a href="/user/login">Se connecter</a>
                <span>|</span>
                <a href="/user/inscription">S'inscrire</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Navbar -->
<nav class="navbar">
    <div class="container">
        <a href="/accueil/accueil" class="navbar-brand">
            <svg viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="36" height="36" rx="8" fill="#2563eb"/>
                <path d="M10 13h6v10h-2v-8h-4v8H8V13h2zm10 0h8v2h-6v2h5v2h-5v4h-2V13z" fill="white"/>
            </svg>
            Takalo
        </a>

        <button class="navbar-toggle" aria-label="Menu">
            <svg class="icon-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 12h18M3 6h18M3 18h18"/>
            </svg>
            <svg class="icon-close" style="display:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 6L6 18M6 6l12 12"/>
            </svg>
        </button>

        <ul class="navbar-menu">
            <li><a href="/accueil/accueil" class="<?= strpos($currentPath, '/accueil') !== false ? 'active' : '' ?>">🏠 Accueil</a></li>
            <?php if (!empty($_SESSION['logged_in'])): ?>
                <li><a href="/user/objets" class="<?= strpos($currentPath, '/user/objets') !== false ? 'active' : '' ?>">📦 Mes Objets</a></li>
                <li><a href="/user/echanges" class="<?= strpos($currentPath, '/user/echanges') !== false ? 'active' : '' ?>">🔄 Mes Échanges</a></li>
            <?php endif; ?>
            <?php if (($_SESSION['user_type'] ?? '') === 'admin'): ?>
                <li><a href="/admin/dashboard" class="<?= strpos($currentPath, '/admin') !== false ? 'active' : '' ?>">⚙️ Admin</a></li>
            <?php else: ?>
                <li><a href="/login" class="<?= $currentPath === '/login' ? 'active' : '' ?>">🔐 Admin</a></li>
            <?php endif; ?>
        </ul>

        <div class="navbar-actions">
            <?php if (!empty($_SESSION['logged_in'])): ?>
                <a href="/user/objets/create" class="btn btn-primary btn-sm">+ Ajouter</a>
                <a href="/logout" class="btn btn-sm btn-secondary">Déconnexion</a>
            <?php else: ?>
                <a href="/user/login" class="btn btn-primary btn-sm">Connexion</a>
                <a href="/user/inscription" class="btn btn-outline btn-sm">Inscription</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
