<?php
namespace app\models;

use PDO;
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
}