-- Insert into villes
INSERT INTO villes (id, nom_ville) VALUES
(1, 'Antananarivo'),
(2, 'Toamasina'),
(3, 'Antsirabe');

-- Insert into types_besoin
INSERT INTO types_besoin (id, nom_type_besoin) VALUES
(1, 'Riz'),
(2, 'Tôle'),
(3, 'Argent');

-- Insert into besoins (depends on types_besoin)
INSERT INTO besoins (id, nom_besoin, prix_unitaire, type_besoin_id) VALUES
(1, 'Sac de riz 50kg', 50000.00, 1),
(2, 'Tôle ondulée', 150000.00, 2),
(3, 'Aide financière', 100000.00, 3);

-- Insert into besoins_ville (depends on villes and besoins)
INSERT INTO besoins_ville (id, ville_id, besoin_id, quantite, quantite_restante, date_besoin) VALUES
(1, 1, 1, 100, 80, '2023-10-01'),
(2, 2, 2, 50, 30, '2023-10-02'),
(3, 3, 3, 200, 150, '2023-10-03');

-- Insert into dons (depends on besoins)
INSERT INTO dons (id, nom_donneur, besoin_id, quantite, quantite_restante, date_don) VALUES
(1, 'Jean Dupont', 1, 100, 50, '2023-10-01'),
(2, 'Marie Curie', 2, 200, 150, '2023-10-02'),
(3, 'Pierre Martin', 1, 50, 20, '2023-10-03'),
(4, 'Sophie Leroy', 3, 75, 75, '2023-10-04'),
(5, 'Luc Bernard', 2, 120, 100, '2023-10-05');

-- Insert into distributions (depends on villes and besoins)
INSERT INTO distributions (id, id_ville, besoin_id, quantite, date_distribution) VALUES
(1, 1, 1, 20, '2023-10-10'),
(2, 2, 2, 10, '2023-10-11'),
(3, 3, 3, 50, '2023-10-12');
