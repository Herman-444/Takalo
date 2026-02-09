CREATE OR REPLACE ViEW view_echange_Mere_Fille AS
SELECT
    em.id AS echange_id,
    em.id_proprietaire AS proprietaire_id,
    em.id_demandeur AS demandeur_id,
    em.status_id AS status_id,
    em.created_at AS echange_created_at,
    em.accepted_at AS echange_accepted_at,
    ef.id AS echange_fille_id,
    ef.id_objet AS objet_id,
    ef.created_at AS echange_fille_created_at,
    ef.id_proprietaire AS echange_fille_proprietaire_id,
    s.name AS status_name
FROM
    echangeMere em
JOIN echangeFille ef ON em.id = ef.id_echangeMere
JOIN status s ON em.status_id = s.id;

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
JOIN categories c ON o.id_categorie = c.id
JOIN users u ON o.id_proprietaire = u.id;     

