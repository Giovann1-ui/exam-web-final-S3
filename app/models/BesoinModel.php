<?php 
namespace app\models;
use Flight;
use PDO;

class BesoinModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function get_all_besoins()
    {
        $stmt = $this->db->prepare("SELECT * FROM besoins");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un besoin par son ID
     */
    public function get_besoin_by_id($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM besoins WHERE id = :id");
        $stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
?>