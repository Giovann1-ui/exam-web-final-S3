-- DROP DATABASE bngrc;

-- CREATE DATABASE bngrc;

-- USE bngrc;
-- SET FOREIGN_KEY_CHECKS = 0;
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
-- SET FOREIGN_KEY_CHECKS = 1;

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
    date_achat DATE NOT NULL DEFAULT CURRENT_DATE,
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
                        date_achat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        FOREIGN KEY (ville_id) REFERENCES villes(id),
                        FOREIGN KEY (besoin_id) REFERENCES besoins(id)
);

CREATE TABLE type_distribution (
    id int AUTO_INCREMENT PRIMARY KEY,
    nom_type_distribution VARCHAR(500) NOT NULL -- Ex: 'Distribution directe', 'Distribution via partenaires locaux'
);

CREATE OR REPLACE VIEW v_historique_achats_besoins AS
(SELECT 
    a.date_achat, 
    v.nom_ville, 
    b.nom_besoin, 
    tb.nom_type_besoin, 
    a.quantite, 
    b.prix_unitaire, 
    COALESCE(f.frais, 0) AS frais,
    -- Si les frais sont NULL, on utilise 0 pour ne pas fausser le calcul
    (a.quantite * b.prix_unitaire * (1 + COALESCE(f.frais, 0)/100)) AS total_paye
FROM achats_besoins a
JOIN besoins_ville bv ON a.besoin_ville_id = bv.id
JOIN villes v ON bv.ville_id = v.id
JOIN besoins b ON bv.besoin_id = b.id
JOIN types_besoin tb ON b.type_besoin_id = tb.id
-- Utilisation du LEFT JOIN pour inclure les achats sans frais associés
LEFT JOIN frais_achat_besoin f ON b.id = f.besoin_id);


INSERT INTO villes (nom_ville) VALUES
('Toamasina'),
('Mananjary'),
('Farafangana'),
('Nosy Be'),
('Morondava');

INSERT INTO types_besoin (nom_type_besoin) VALUES
('nature'),
('materiel'),
('argent');

INSERT INTO besoins (nom_besoin, prix_unitaire, type_besoin_id) VALUES
('Riz(kg)', 3000, 1),
('Eau(L)', 1000, 1),
('Tôle', 25000, 2),
('Bache', 15000, 2),
('Argent', 1, 3),
('Huile(L)', 6000, 1),
('Clous(kg)', 8000, 2),
('Bois', 10000, 2),
('Hariot', 4000, 1),
('Groupe', 6750000, 2);

INSERT INTO besoins_ville (ville_id, date_besoin, besoin_id, quantite, quantite_restante) VALUES
(1, '2026-02-16', 1, 800, 800),
(1, '2026-02-15', 2, 1500, 1500),
(1, '2026-02-16', 3, 120, 120),
(1, '2026-02-15', 4, 200, 200),
(1, '2026-02-16', 5, 12000000, 12000000),
(2, '2026-02-15', 1, 500, 500),
(2, '2026-02-16', 6, 120, 120),
(2, '2026-02-15', 3, 80, 80),
(2, '2026-02-16', 7, 60, 60),
(2, '2026-02-15', 5, 6000000, 6000000),
(3, '2026-02-16', 1, 600, 600),
(3, '2026-02-15', 2, 1000, 1000),
(3, '2026-02-16', 4, 150, 150),
(3, '2026-02-15', 8, 100, 100),
(3, '2026-02-16', 5, 8000000, 8000000),
(4, '2026-02-15', 1, 300, 300),
(4, '2026-02-16', 9, 200, 200),
(4, '2026-02-15', 3, 40, 40),
(4, '2026-02-16', 7, 8000, 8000),
(4, '2026-02-16', 5, 4000000, 4000000),
(5, '2026-02-16', 1, 700, 700),
(5, '2026-02-15', 2, 1200, 1200),
(5, '2026-02-16', 4, 180, 180),
(5, '2026-02-15', 8, 150, 150),
(5, '2026-02-16', 5, 10000000, 10000000),
(1, '2026-02-15', 10, 3, 3);

INSERT INTO dons (nom_donneur, date_don, besoin_id, quantite, quantite_restante) VALUES
('Default', '2026-02-16', 5, 5000000, 5000000),
('Default', '2026-02-16', 5, 3000000, 3000000),
('Default', '2026-02-17', 5, 4000000, 4000000),
('Default', '2026-02-17', 5, 1500000, 1500000),
('Default', '2026-02-17', 5, 6000000, 6000000),
('Default', '2026-02-16', 1, 400, 400),
('Default', '2026-02-16', 2, 600, 600),
('Default', '2026-02-17', 3, 50, 50),
('Default', '2026-02-17', 4, 70, 70),
('Default', '2026-02-17', 9, 100, 100),
('Default', '2026-02-18', 1, 2000, 2000),
('Default', '2026-02-18', 3, 300, 300),
('Default', '2026-02-18', 2, 5000, 5000),
('Default', '2026-02-19', 5, 20000000, 20000000),
('Default', '2026-02-19', 4, 500, 500),
('Default', '2026-02-17', 9, 88, 88);


-- INSERT INTO frais_achat_besoin (besoin_id, frais) VALUES
-- (1, 10),
-- (2, 20),
-- (3, 0);


-- INSERT INTO dons (nom_donneur, besoin_id, quantite, quantite_restante) VALUES
-- ('Donateur 1', 1, 50, 50);

INSERT INTO type_distribution (nom_type_distribution) VALUES
('Par date de demande'), ('Par demande minimum'), ('Distribution proportionnelle');
-- INSERT INTO achats_besoins (besoin_ville_id, quantite) VALUES
-- (1, 50),
-- (2, 30),
-- (3, 100);