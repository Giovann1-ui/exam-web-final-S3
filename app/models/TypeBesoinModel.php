<?php 
namespace app\models;
use Flight;
use PDO;

class TypeBesoinModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function get_all_type_besoins()
    {
        $stmt = $this->db->prepare("SELECT * FROM types_besoin");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un type de besoin par son ID
     */
    public function get_type_besoin_by_id($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM types_besoin WHERE id = :id");
        $stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
?>