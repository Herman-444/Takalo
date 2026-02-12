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
    u.username AS proprietaire_username,
    s.name AS status_name
FROM
    echangeMere em
JOIN echangeFille ef ON em.id = ef.id_echangeMere
JOIN users u ON em.id_proprietaire = u.id
JOIN status s ON em.status_id = s.id;

