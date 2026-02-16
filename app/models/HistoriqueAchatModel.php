<?php
namespace app\models;

use flight\database\PdoWrapper;
use PDO;

class HistoriqueAchatModel
{
    private PdoWrapper $db;

    public function __construct(PdoWrapper $db)
    {
        $this->db = $db;
    }

    /**
     * Récupère tous les achats depuis la vue v_historique_achats_besoins avec filtres optionnels
     * @param int|null $ville_id
     * @param string|null $date_debut
     * @param string|null $date_fin
     * @return array
     */
    public function getAchatsAvecFiltres($ville_id = null, $date_debut = null, $date_fin = null): array
    {
        $sql = "SELECT * FROM v_historique_achats_besoins WHERE 1=1";
        
        $params = [];
        
        if ($ville_id !== null && $ville_id !== '') {
            $sql .= " AND ville_id = :ville_id";
            $params[':ville_id'] = (int) $ville_id;
        }
        
        if ($date_debut !== null && $date_debut !== '') {
            $sql .= " AND DATE(date_achat) >= :date_debut";
            $params[':date_debut'] = $date_debut;
        }
        
        if ($date_fin !== null && $date_fin !== '') {
            $sql .= " AND DATE(date_achat) <= :date_fin";
            $params[':date_fin'] = $date_fin;
        }
        
        $sql .= " ORDER BY date_achat DESC";
        
        $stmt = $this->db->prepare($sql);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère toutes les villes pour le filtre
     * @return array
     */
    public function getAllVilles(): array
    {
        $sql = "SELECT id, nom_ville FROM villes ORDER BY nom_ville";
        return $this->db->fetchAll($sql);
    }

    /**
     * Calcule le total des quantités achetées
     * @param array $achats
     * @return int
     */
    public function calculerTotalQuantite(array $achats): int
    {
        $total = 0;
        foreach ($achats as $achat) {
            $total += intval($achat['quantite']);
        }
        return $total;
    }

    /**
     * Calcule le montant total des achats (avec frais)
     * @param array $achats
     * @return float
     */
    public function calculerTotalMontant(array $achats): float
    {
        $total = 0.0;
        foreach ($achats as $achat) {
            $total += floatval($achat['total_paye']);
        }
        return $total;
    }

    public function getAllAchats(): array
    {
        $sql = "SELECT * FROM v_historique_achats_besoins ORDER BY date_achat DESC";
        return $this->db->fetchAll($sql);
    }
}