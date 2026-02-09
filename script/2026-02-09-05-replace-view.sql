CREATE OR REPLACE VIEW view_objets_details AS
SELECT
    o.id AS objet_id,
    o.title AS objet_title,
    o.description AS objet_description,
    o.id_proprietaire AS proprietaire_id,
    o.created_at AS objet_created_at,
    o.id_categorie AS categorie_id,
    o.qtt AS quantity,
    o.prix_estime AS prix_estime,
    c.name AS categorie_name,
    u.username AS proprietaire_username
FROM
    objets o
LEFT JOIN categories c ON o.id_categorie = c.id
LEFT JOIN users u ON o.id_proprietaire = u.id;