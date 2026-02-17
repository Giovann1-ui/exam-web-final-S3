-- DROP DATABASE bngrc;

-- CREATE DATABASE bngrc;

-- USE bngrc;

DROP TABLE IF EXISTS distributions;
DROP TABLE IF EXISTS dons;
DROP TABLE IF EXISTS besoins;
DROP TABLE IF EXISTS types_besoin;
DROP TABLE IF EXISTS besoins_ville;
DROP TABLE IF EXISTS villes;
DROP TABLE IF EXISTS frais_achat_besoin;
DROP TABLE IF EXISTS achats_besoins;
DROP TABLE IF EXISTS achats;
DROP TABLE IF EXISTS type_distribution;

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
    date_besoin DATE NOT NULL DEFAULT (CURRENT_DATE),
    FOREIGN KEY (ville_id) REFERENCES villes(id),
    FOREIGN KEY (besoin_id) REFERENCES besoins(id)
);

CREATE TABLE dons (
    id int AUTO_INCREMENT PRIMARY KEY,
    nom_donneur VARCHAR(500) NOT NULL,
    besoin_id int NOT NULL,
    quantite int NOT NULL,
    quantite_restante int NOT NULL,
    date_don DATE NOT NULL DEFAULT (CURRENT_DATE),
    FOREIGN KEY (besoin_id) REFERENCES besoins(id)
);

CREATE TABLE distributions (
    id int AUTO_INCREMENT PRIMARY KEY,
    id_ville int NOT NULL,
    besoin_id int NOT NULL,
    quantite int NOT NULL,
    date_distribution DATE NOT NULL DEFAULT (CURRENT_DATE),
    FOREIGN KEY (id_ville) REFERENCES villes(id),
    FOREIGN KEY (besoin_id) REFERENCES besoins(id)
);

CREATE TABLE frais_achat_besoin (
    id int AUTO_INCREMENT PRIMARY KEY,
    besoin_id int NOT NULL,
    frais DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (besoin_id) REFERENCES besoins(id)
);

CREATE TABLE achats_besoins (
    id int AUTO_INCREMENT PRIMARY KEY,
    besoin_ville_id int NOT NULL,
    quantite int NOT NULL,
    date_achat DATE NOT NULL DEFAULT (CURRENT_DATE),
    FOREIGN KEY (besoin_ville_id) REFERENCES besoins_ville(id)
);

-- truc de statistiques (page recapitulatif)
CREATE TABLE achats (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        ville_id INT NOT NULL,
                        besoin_id INT NOT NULL, -- Le besoin en nature (ex: Riz) qu'on a acheté
                        quantite INT NOT NULL,
                        prix_unitaire_ht DECIMAL(10, 2), -- Prix du produit au moment de l'achat
                        frais_pourcentage DECIMAL(5, 2), -- Le x% de frais configuré
                        montant_total_ttc DECIMAL(15, 2), -- (Qté * Prix) * (1 + x/100)
                        date_achat TIMESTAMP DEFAULT (CURRENT_TIMESTAMP),
                        FOREIGN KEY (ville_id) REFERENCES villes(id),
                        FOREIGN KEY (besoin_id) REFERENCES besoins(id)
);

CREATE TABLE type_distribution (
    id int AUTO_INCREMENT PRIMARY KEY,
    nom_type_distribution VARCHAR(500) NOT NULL -- Ex: 'Distribution directe', 'Distribution via partenaires locaux'
);

CREATE OR REPLACE VIEW v_historique_achats_besoins AS
(SELECT a.date_achat, v.nom_ville, b.nom_besoin, tb.nom_type_besoin, a.quantite, b.prix_unitaire, f.frais,
       (a.quantite * b.prix_unitaire * (1 + f.frais/100)) AS total_paye
FROM achats_besoins a
JOIN besoins_ville bv ON a.besoin_ville_id = bv.id
JOIN villes v ON bv.ville_id = v.id
JOIN besoins b ON bv.besoin_id = b.id
JOIN types_besoin tb ON b.type_besoin_id = tb.id
JOIN frais_achat_besoin f ON b.id = f.besoin_id);


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

INSERT INTO frais_achat_besoin (besoin_id, frais) VALUES
(1, 10),
(2, 20),
(3, 0); 


-- INSERT INTO dons (nom_donneur, besoin_id, quantite, quantite_restante) VALUES
-- ('Donateur 1', 1, 50, 50);

INSERT INTO type_distribution (nom_type_distribution) VALUES
('Par date de demande'), ('Par demande minimum'), ('Distribution proportionnelle');
INSERT INTO achats_besoins (besoin_ville_id, quantite) VALUES
(1, 50),
(2, 30),
(3, 100);