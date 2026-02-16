<?php

namespace app\models;

use PDO;

class RecapModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }


    public function getSatisfait()
    {
        $stmt1 = $this->db->query("SELECT SUM(d.quantite * b.prix_unitaire) AS total_distributions FROM distributions d JOIN besoins b ON d.besoin_id = b.id");
        $distributions = $stmt1->fetch(PDO::FETCH_ASSOC)['total_distributions'] ?? 0;

        $stmt2 = $this->db->query("SELECT SUM(montant_total_ttc) AS total_achats FROM achats");
        $achats = $stmt2->fetch(PDO::FETCH_ASSOC)['total_achats'] ?? 0;

        return [
            'distributions' => $distributions,
            'achats' => $achats
        ];
    }

    public function getTotalBesoins(): float
    {
        $stmt = $this->db->query(
            "SELECT SUM(bv.quantite * b.prix_unitaire) AS total
         FROM besoins_ville bv
         JOIN besoins b ON bv.besoin_id = b.id"
        );

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (float) ($row['total'] ?? 0.0);
    }

    public function getRecapData()
    {
        $total = $this->getTotalBesoins(); // now a float
        $satisfaitData = $this->getSatisfait();
        $satisfait = (float)$satisfaitData['distributions'] + (float)$satisfaitData['achats'];
        $restant = $total - $satisfait;

        return [
            'total' => $total,
            'satisfait' => $satisfait,
            'restant' => $restant,
        ];
    }
}