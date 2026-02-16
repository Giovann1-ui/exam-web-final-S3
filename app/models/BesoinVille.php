<?php

namespace app\models;

use flight\database\PdoWrapper;

class BesoinVille {
    private PdoWrapper $db;

    public function __construct(PdoWrapper $db) {
        $this->db = $db;
    }

    /**
     * Récupère toutes les villes avec leurs besoins NON satisfaits
     * @return array
     */
    public function getVillesAvecBesoins(): array {
        $sql = "SELECT 
                    v.id AS ville_id, 
                    v.nom_ville,
                    b.id AS besoin_id,
                    b.nom_besoin,
                    b.prix_unitaire,
                    tb.nom_type_besoin,
                    bv.quantite,
                    bv.quantite_restante,
                    bv.date_besoin
                FROM villes v
                JOIN besoins_ville bv ON v.id = bv.ville_id
                JOIN besoins b ON b.id = bv.besoin_id
                JOIN types_besoin tb ON b.type_besoin_id = tb.id
                WHERE bv.quantite_restante > 0
                ORDER BY v.nom_ville, b.nom_besoin";
        
        return $this->db->fetchAll($sql);
    }

    /**
     * Récupère les besoins satisfaits (attribués) par ville
     * @return array
     */
    public function getBesoinsAttribuesParVille(): array {
        $sql = "SELECT 
                    v.id AS ville_id,
                    v.nom_ville,
                    b.id AS besoin_id,
                    b.nom_besoin,
                    b.prix_unitaire,
                    tb.nom_type_besoin,
                    bv.quantite AS quantite_initiale,
                    (bv.quantite - bv.quantite_restante) AS quantite_attribuee,
                    bv.date_besoin
                FROM villes v
                JOIN besoins_ville bv ON v.id = bv.ville_id
                JOIN besoins b ON b.id = bv.besoin_id
                JOIN types_besoin tb ON b.type_besoin_id = tb.id
                WHERE (bv.quantite - bv.quantite_restante) > 0
                ORDER BY v.nom_ville, b.nom_besoin";
        
        return $this->db->fetchAll($sql);
    }

    /**
     * Récupère les villes entièrement satisfaites
     * @return array
     */
    public function getVillesSatisfaites(): array {
        $sql = "SELECT DISTINCT v.id AS ville_id, v.nom_ville
                FROM villes v
                WHERE NOT EXISTS (
                    SELECT 1 
                    FROM besoins_ville bv 
                    WHERE bv.ville_id = v.id 
                    AND bv.quantite_restante > 0
                )
                AND EXISTS (
                    SELECT 1
                    FROM besoins_ville bv
                    WHERE bv.ville_id = v.id
                )
                ORDER BY v.nom_ville";
        
        return $this->db->fetchAll($sql);
    }

    /**
     * Récupère les dons attribués par ville
     * @return array
     */
    public function getDonsParVille(): array {
        $sql = "SELECT 
                    v.id AS ville_id,
                    v.nom_ville,
                    b.nom_besoin,
                    d.nom_donneur,
                    d.quantite AS quantite_donnee,
                    d.quantite_restante,
                    d.date_don,
                    dist.quantite AS quantite_distribuee,
                    dist.date_distribution
                FROM distributions dist
                JOIN villes v ON dist.id_ville = v.id
                JOIN besoins b ON dist.besoin_id = b.id
                LEFT JOIN dons d ON d.besoin_id = b.id
                ORDER BY dist.date_distribution DESC
                LIMIT 20";
        
        return $this->db->fetchAll($sql);
    }

    /**
     * Récupère les statistiques globales
     * @return array
     */
    public function getStatistiques(): array {
        $stats = [];
        
        $stats['total_villes'] = $this->db->fetchField("SELECT COUNT(*) FROM villes");
        $stats['total_besoins'] = $this->db->fetchField("SELECT SUM(quantite) FROM besoins_ville");
        $stats['total_besoins_restants'] = $this->db->fetchField("SELECT SUM(quantite_restante) FROM besoins_ville");
        $stats['total_dons'] = $this->db->fetchField("SELECT COUNT(*) FROM dons");
        
        if ($stats['total_besoins'] > 0) {
            $stats['pourcentage_satisfaction'] = round(
                (($stats['total_besoins'] - $stats['total_besoins_restants']) / $stats['total_besoins']) * 100, 
                2
            );
        } else {
            $stats['pourcentage_satisfaction'] = 0;
        }
        
        return $stats;
    }
}