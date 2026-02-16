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
        $stmt = $this->db->prepare("
            SELECT b.*, tb.nom_type_besoin 
            FROM besoins b
            INNER JOIN types_besoin tb ON b.type_besoin_id = tb.id
            ORDER BY tb.nom_type_besoin, b.nom_besoin
        ");
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