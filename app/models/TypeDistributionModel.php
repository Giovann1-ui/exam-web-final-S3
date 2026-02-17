<?php 
namespace app\models;
use PDO;

class TypeDistributionModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Récupère tous les types de distribution
     */
    public function getAllTypesDistribution()
    {
        $stmt = $this->db->prepare("SELECT * FROM type_distribution ORDER BY id");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un type de distribution par son ID
     */
    public function getTypeDistributionById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM type_distribution WHERE id = :id");
        $stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>