CREATE DATABASE bngrc;

USE bngrc;

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