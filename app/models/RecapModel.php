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
        $stmt1 = $this->db->query(
            "SELECT SUM(d.quantite * b.prix_unitaire) AS total_distributions 
             FROM distributions d 
             JOIN besoins b ON d.besoin_id = b.id"
        );
        $distributions = $stmt1->fetch(PDO::FETCH_ASSOC)['total_distributions'] ?? 0;

        $stmt2 = $this->db->query(
            "SELECT SUM(ab.quantite * b.prix_unitaire * (1 + f.frais/100)) AS total_achats
             FROM achats_besoins ab
             JOIN besoins_ville bv ON ab.besoin_ville_id = bv.id
             JOIN besoins b ON bv.besoin_id = b.id
             JOIN frais_achat_besoin f ON b.id = f.besoin_id"
        );
        $achats = $stmt2->fetch(PDO::FETCH_ASSOC)['total_achats'] ?? 0;

        return [
            'distributions' => (float)$distributions,
            'achats' => (float)$achats
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
        return (float)($row['total'] ?? 0.0);
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
            'restant' => $restant,
            'details' => [
                'distributions' => $satisfaitData['distributions'],
                'achats' => $satisfaitData['achats']
            ]
        ];
    }
}