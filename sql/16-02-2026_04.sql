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