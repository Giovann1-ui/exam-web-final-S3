<?php
namespace app\models;

use PDO;
use PDOException;

class DonModels
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllDons()
    {
        $stmt = $this->db->query("
            SELECT 
                d.nom_donneur, 
                d.quantite, 
                d.quantite_restante, 
                d.date_don,
                b.nom_besoin,
                tb.nom_type_besoin
            FROM dons d
            JOIN besoins b ON d.besoin_id = b.id
            JOIN types_besoin tb ON b.type_besoin_id = tb.id
            ORDER BY d.date_don
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Insère un nouveau don dans la base de données
     */
    public function insertDon($nom_donneur, $besoin_id, $quantite, $date_don)
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO dons (nom_donneur, besoin_id, quantite, quantite_restante, date_don) 
                VALUES (:nom_donneur, :besoin_id, :quantite, :quantite_restante, :date_don)
            ");

            $stmt->bindValue(':nom_donneur', $nom_donneur, PDO::PARAM_STR);
            $stmt->bindValue(':besoin_id', (int) $besoin_id, PDO::PARAM_INT);
            $stmt->bindValue(':quantite', (int) $quantite, PDO::PARAM_INT);
            $stmt->bindValue(':quantite_restante', (int) $quantite, PDO::PARAM_INT);
            $stmt->bindValue(':date_don', $date_don, PDO::PARAM_STR);

            $stmt->execute();

            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erreur lors de l'insertion du don: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère tous les dons avec quantité restante > 0
     */
    public function getDonsNonDistribues()
    {
        $stmt = $this->db->prepare("
            SELECT d.*, b.nom_besoin, tb.nom_type_besoin
            FROM dons d
            INNER JOIN besoins b ON d.besoin_id = b.id
            INNER JOIN types_besoin tb ON b.type_besoin_id = tb.id
            WHERE d.quantite_restante > 0
            ORDER BY d.date_don ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les besoins des villes avec les noms pour la simulation
     */
    public function getBesoinsVilleByBesoinId($besoin_id)
    {
        $stmt = $this->db->prepare("
            SELECT bv.*, v.nom_ville, b.nom_besoin
            FROM besoins_ville bv
            INNER JOIN villes v ON bv.ville_id = v.id
            INNER JOIN besoins b ON bv.besoin_id = b.id
            WHERE bv.besoin_id = :besoin_id
            AND bv.quantite_restante > 0
            ORDER BY bv.date_besoin ASC
        ");
        $stmt->bindValue(':besoin_id', (int) $besoin_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Met à jour la quantité restante d'un besoin de ville
     */
    public function updateBesoinVilleQuantite($besoin_ville_id, $nouvelle_quantite_restante)
    {
        $stmt = $this->db->prepare("
            UPDATE besoins_ville 
            SET quantite_restante = :quantite_restante 
            WHERE id = :id
        ");

        $stmt->bindValue(':quantite_restante', (int) $nouvelle_quantite_restante, PDO::PARAM_INT);
        $stmt->bindValue(':id', (int) $besoin_ville_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Met à jour la quantité restante d'un don
     */
    public function updateDonQuantiteRestante($don_id, $nouvelle_quantite_restante)
    {
        $stmt = $this->db->prepare("
            UPDATE dons 
            SET quantite_restante = :quantite_restante 
            WHERE id = :id
        ");

        $stmt->bindValue(':quantite_restante', (int) $nouvelle_quantite_restante, PDO::PARAM_INT);
        $stmt->bindValue(':id', (int) $don_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Enregistre une distribution dans la table distributions
     */
    public function insertDistribution($ville_id, $besoin_id, $quantite, $date_distribution)
    {
        $stmt = $this->db->prepare("
            INSERT INTO distributions (id_ville, besoin_id, quantite, date_distribution) 
            VALUES (:id_ville, :besoin_id, :quantite, :date_distribution)
        ");

        $stmt->bindValue(':id_ville', (int) $ville_id, PDO::PARAM_INT);
        $stmt->bindValue(':besoin_id', (int) $besoin_id, PDO::PARAM_INT);
        $stmt->bindValue(':quantite', (int) $quantite, PDO::PARAM_INT);
        $stmt->bindValue(':date_distribution', $date_distribution, PDO::PARAM_STR);

        return $stmt->execute();
    }
}