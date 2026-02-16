<?php
namespace app\models;

use PDO;
use PDOException;

class AchatModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Récupère tous les besoins restants à acheter (type_besoin_id = 3 pour Argent)
     */
    public function getBesoinsRestantsAcheter()
    {
        $stmt = $this->db->query("
            SELECT 
                v.id AS ville_id,
                v.nom_ville,
                b.id AS besoin_id,
                b.nom_besoin,
                b.prix_unitaire,
                tb.nom_type_besoin,
                bv.id AS besoin_ville_id,
                bv.quantite,
                bv.quantite_restante,
                bv.date_besoin
            FROM villes v
            JOIN besoins_ville bv ON v.id = bv.ville_id
            JOIN besoins b ON b.id = bv.besoin_id
            JOIN types_besoin tb ON b.type_besoin_id = tb.id
            WHERE bv.quantite_restante > 0 
            AND b.type_besoin_id != 3
            ORDER BY v.nom_ville, bv.date_besoin ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un besoin spécifique par son ID
     */
    public function getBesoinVilleById($besoin_ville_id)
    {
        $stmt = $this->db->prepare("
            SELECT 
                v.id AS ville_id,
                v.nom_ville,
                b.id AS besoin_id,
                b.nom_besoin,
                b.prix_unitaire,
                tb.nom_type_besoin,
                bv.id AS besoin_ville_id,
                bv.quantite,
                bv.quantite_restante,
                bv.date_besoin
            FROM besoins_ville bv
            JOIN villes v ON bv.ville_id = v.id
            JOIN besoins b ON bv.besoin_id = b.id
            JOIN types_besoin tb ON b.type_besoin_id = tb.id
            WHERE bv.id = :besoin_ville_id
        ");
        $stmt->bindValue(':besoin_ville_id', (int) $besoin_ville_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère le montant total d'argent disponible dans les dons
     */
    public function getArgentDisponible()
    {
        $stmt = $this->db->query("
            SELECT COALESCE(SUM(d.quantite_restante), 0) AS argent_disponible
            FROM dons d
            JOIN besoins b ON d.besoin_id = b.id
            WHERE b.type_besoin_id = 3
        ");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (float) $result['argent_disponible'];
    }

    /**
     * Récupère les frais applicables à un besoin
     */
    public function getFraisAchat($besoin_id)
    {
        $stmt = $this->db->prepare("
            SELECT frais
            FROM frais_achat_besoin
            WHERE besoin_id = :besoin_id
        ");
        $stmt->bindValue(':besoin_id', (int) $besoin_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (float) $result['frais'] : 0;
    }

    /**
     * Vérifie s'il y a du stock disponible pour ce besoin
     */
    public function hasStockDisponible($besoin_id)
    {
        $stmt = $this->db->prepare("
            SELECT quantite_restante
            FROM dons
            WHERE besoin_id = :besoin_id
            AND quantite_restante > 0
            LIMIT 1
        ");
        $stmt->bindValue(':besoin_id', (int) $besoin_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result && $result['quantite_restante'] > 0;
    }

    /**
     * Insère un achat dans la base de données
     */
    public function insertAchat($besoin_ville_id, $quantite, $frais_achat_besoin_id, $date_achat)
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO achats_besoins (ville_id, besoin_id, quantite, frais_achat_besoin_id, date_achat)
                VALUES (:besoin_ville_id, :quantite, :frais_achat_besoin_id, :date_achat)
            ");

            $stmt->bindValue(':besoin_ville_id', (int) $besoin_ville_id, PDO::PARAM_INT);
            $stmt->bindValue(':quantite', (int) $quantite, PDO::PARAM_INT);
            $stmt->bindValue(':frais_achat_besoin_id', (int) $frais_achat_besoin_id, PDO::PARAM_INT);
            $stmt->bindValue(':date_achat', $date_achat, PDO::PARAM_STR);

            $stmt->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erreur lors de l'insertion de l'achat: " . $e->getMessage());
            return false;
        }
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
     * Déduit l'argent utilisé des dons par ordre d'arrivée
     */
    public function deduireArgent($montant_a_deduire)
    {
        // Récupérer les dons d'argent par ordre de date
        $stmt = $this->db->query("
            SELECT d.id, d.quantite_restante
            FROM dons d
            JOIN besoins b ON d.besoin_id = b.id
            WHERE b.type_besoin_id = 3
            AND d.quantite_restante > 0
            ORDER BY d.date_don ASC, d.id ASC
        ");
        $dons = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $reste_a_deduire = $montant_a_deduire;

        foreach ($dons as $don) {
            if ($reste_a_deduire <= 0) {
                break;
            }

            $montant_deduction = min($reste_a_deduire, $don['quantite_restante']);
            $nouvelle_quantite = $don['quantite_restante'] - $montant_deduction;

            // Mettre à jour le don
            $update_stmt = $this->db->prepare("
                UPDATE dons 
                SET quantite_restante = :quantite_restante 
                WHERE id = :id
            ");
            $update_stmt->bindValue(':quantite_restante', $nouvelle_quantite, PDO::PARAM_STR);
            $update_stmt->bindValue(':id', (int) $don['id'], PDO::PARAM_INT);
            $update_stmt->execute();

            $reste_a_deduire -= $montant_deduction;
        }

        return $reste_a_deduire <= 0;
    }
}