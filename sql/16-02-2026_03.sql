CREATE DATABASE bngrc;

USE bngrc;

DROP TABLE IF EXISTS distributions;
DROP TABLE IF EXISTS dons;
DROP TABLE IF EXISTS besoins;
DROP TABLE IF EXISTS types_besoin;
DROP TABLE IF EXISTS besoins_ville;
DROP TABLE IF EXISTS villes;

CREATE TABLE villes (
    id int AUTO_INCREMENT PRIMARY KEY,
    nom_ville VARCHAR(500) NOT NULL
);

CREATE TABLE types_besoin (
    id int AUTO_INCREMENT PRIMARY KEY,
    nom_type_besoin VARCHAR(500) NOT NULL -- Ex: 'Riz', 'Tôle', 'Argent' [cite: 10, 11, 12]
);

CREATE TABLE besoins (
    id int AUTO_INCREMENT PRIMARY KEY,
    nom_besoin VARCHAR(500) NOT NULL,
    prix_unitaire DECIMAL(10, 2) NOT NULL,
    type_besoin_id int NOT NULL,
    FOREIGN KEY (type_besoin_id) REFERENCES types_besoin(id)
);

CREATE TABLE besoins_ville (
    id int AUTO_INCREMENT PRIMARY KEY,
    ville_id int NOT NULL,
    besoin_id int NOT NULL,
    quantite int NOT NULL,
    quantite_restante int NOT NULL,
    date_besoin DATE NOT NULL DEFAULT CURRENT_DATE,
    FOREIGN KEY (ville_id) REFERENCES villes(id),
    FOREIGN KEY (besoin_id) REFERENCES besoins(id)
);

CREATE TABLE dons (
    id int AUTO_INCREMENT PRIMARY KEY,
    nom_donneur VARCHAR(500) NOT NULL,
    besoin_id int NOT NULL,
    quantite int NOT NULL,
    quantite_restante int NOT NULL,
    date_don DATE NOT NULL DEFAULT CURRENT_DATE,
    FOREIGN KEY (besoin_id) REFERENCES besoins(id)
);

CREATE TABLE distributions (
    id int AUTO_INCREMENT PRIMARY KEY,
    id_ville int NOT NULL,
    besoin_id int NOT NULL,
    quantite int NOT NULL,
    date_distribution DATE NOT NULL DEFAULT CURRENT_DATE,
    FOREIGN KEY (id_ville) REFERENCES villes(id),
    FOREIGN KEY (besoin_id) REFERENCES besoins(id)
);

INSERT INTO villes (id, nom_ville) VALUES
(1, 'Ville A'),
(2, 'Ville B'),
(3, 'Ville C');

INSERT INTO types_besoin (nom_type_besoin) VALUES
("Nature"), ("Matériaux"), ("Argent");

INSERT INTO besoins (nom_besoin, prix_unitaire, type_besoin_id) VALUES
('Riz', 2.50, 1),
('Tôle', 15.00, 2),
('Argent', 1.00, 3);

INSERT INTO besoins_ville (ville_id, besoin_id, quantite, quantite_restante) VALUES
(1, 1, 100, 100),
(1, 2, 50, 50),
(1, 3, 200, 200),
(2, 1, 150, 150),
(2, 2, 75, 75),
(2, 3, 300, 300),
(3, 1, 200, 200),
(3, 2, 100, 100),
(3, 3, 400, 400);

SELECT 
                    v.id AS ville_id,
                    v.nom_ville,
                    b.id AS besoin_id,
                    b.nom_besoin,
                    b.prix_unitaire,
                    tb.nom_type_besoin,
                    bv.quantite AS quantite_initiale,
                    (bv.quantite - bv.quantite_restante) AS quantite_attribuee,
                    bv.date_besoin
                FROM villes v
                JOIN besoins_ville bv ON v.id = bv.ville_id
                JOIN besoins b ON b.id = bv.besoin_id
                JOIN types_besoin tb ON b.type_besoin_id = tb.id
                WHERE (bv.quantite - bv.quantite_restante) > 0
                ORDER BY v.nom_ville, b.nom_besoin;