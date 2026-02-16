INSERT INTO villes (nom_ville) VALUES 
('Antananarivo'),
('Toamasina'),
('Mananjary'),
('Fianarantsoa');

INSERT INTO types_besoin (nom_type_besoin) VALUES 
('En nature'),     -- Pour le riz, huile, etc. [cite: 10]
('En matériaux'),  -- Pour les tôles, clous, etc. [cite: 11]
('En argent');     -- Pour les aides financières [cite: 12]

-- Liaison avec les IDs de types_besoin (1: Nature, 2: Matériaux, 3: Argent)
INSERT INTO besoins (nom_besoin, prix_unitaire, type_besoin_id) VALUES 
('Sac de Riz', 150000.00, 1),
('Bouteille Huile 1L', 9000.00, 1),
('Feuille de Tôle', 45000.00, 2),
('Paquet de Clous', 12000.00, 2),
('Aide Numéraire', 1.00, 3); -- Pour l'argent, l'unité est souvent 1 Ar