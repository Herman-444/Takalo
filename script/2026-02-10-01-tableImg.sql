use takalo;

CREATE TABLE IF NOT EXISTS images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT,
    image_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE OR REPLACE VIEW view_objets_with_first_image AS
SELECT
  o.id,
  o.title,
  o.description,
  o.qtt,
  o.id_proprietaire,
  o.created_at        AS objet_created_at,
  o.id_categorie,
  o.prix_estime,
  c.name               AS categorie,
  img.image_path       AS first_image,
  img.created_at       AS image_created_at
FROM objets o
LEFT JOIN categories c ON o.id_categorie = c.id
LEFT JOIN images img ON img.id = (
  SELECT i2.id
  FROM images i2
  WHERE i2.id_objet = o.id
  ORDER BY i2.created_at ASC, i2.id ASC
  LIMIT 1
);

