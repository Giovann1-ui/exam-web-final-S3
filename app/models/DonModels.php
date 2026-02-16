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
        $stmt = $this->db->query("select nom_donneur , quantite , quantite_restante , date_don from dons order by date_don ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}