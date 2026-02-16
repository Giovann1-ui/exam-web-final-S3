CREATE DATABASE bngrc;

USE bngrc;

DROP TABLE IF EXISTS distributions;
DROP TABLE IF EXISTS dons;
DROP TABLE IF EXISTS besoins;
DROP TABLE IF EXISTS types_besoin;
DROP TABLE IF EXISTS besoins_ville;
DROP TABLE IF EXISTS villes;

CREATE TABLE villes (
    id int PRIMARY KEY AUTO_INCREMENT,
    nom_ville VARCHAR(500) NOT NULL
);

CREATE TABLE types_besoin (
    id int PRIMARY KEY AUTO_INCREMENT,
    nom_type_besoin VARCHAR(500) NOT NULL -- Ex: 'Riz', 'Tôle', 'Argent' [cite: 10, 11, 12]
);

CREATE TABLE besoins (
    id int PRIMARY KEY AUTO_INCREMENT,
    nom_besoin VARCHAR(500) NOT NULL,
    prix_unitaire DECIMAL(10, 2) NOT NULL,
    type_besoin_id int NOT NULL,
    FOREIGN KEY (type_besoin_id) REFERENCES types_besoin(id)
);

CREATE TABLE besoins_ville (
    id int PRIMARY KEY AUTO_INCREMENT,
    ville_id int NOT NULL,
    besoin_id int NOT NULL,
    quantite int NOT NULL,
    quantite_restante int NOT NULL,
    date_besoin DATE NOT NULL DEFAULT CURRENT_DATE,
    FOREIGN KEY (ville_id) REFERENCES villes(id),
    FOREIGN KEY (besoin_id) REFERENCES besoins(id)
);

CREATE TABLE dons (
    id int PRIMARY KEY AUTO_INCREMENT,
    nom_donneur VARCHAR(500) NOT NULL,
    besoin_id int NOT NULL,
    quantite int NOT NULL,
    quantite_restante int NOT NULL,
    date_don DATE NOT NULL DEFAULT CURRENT_DATE,
    FOREIGN KEY (besoin_id) REFERENCES besoins(id)
);

CREATE TABLE distributions (
    id int PRIMARY KEY AUTO_INCREMENT,
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