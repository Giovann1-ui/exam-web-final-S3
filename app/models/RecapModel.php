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

    public function getTotalBesoins()
    {
        $stmt = $this->db->query("select SUM(quantite * prix_unitaire) from besoins_ville join besoins on besoins_ville.besoin_id = besoins.id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    public function getRecapData()
    {
        $total = $this->getTotalBesoins();
        $satisfaitData = $this->getSatisfait();
        $satisfait = $satisfaitData['distributions'] + $satisfaitData['achats'];
        $restant = $total - $satisfait;

        return [
            'total' => $total,
            'satisfait' => $satisfait,
            'restant' => $restant
        ];
    }
}