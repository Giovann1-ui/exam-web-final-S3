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

    public function get_besoin_by_id($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM besoins WHERE id = :id");
        $stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getVilles()
    {
        if ($this->db === null) {
            throw new \Exception('Database connection is null in BesoinModel');
        }

        $stmt = $this->db->query("SELECT id, nom_ville FROM villes ORDER BY nom_ville");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getBesoinsWithTypes()
    {
        $stmt = $this->db->query("
            SELECT b.id, b.nom_besoin, b.prix_unitaire, tb.nom_type_besoin
            FROM besoins b
            INNER JOIN types_besoin tb ON b.type_besoin_id = tb.id
            ORDER BY tb.nom_type_besoin, b.nom_besoin
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertBesoinVille(int $ville_id, int $besoin_id, int $quantite, string $date_besoin): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO besoins_ville (ville_id, besoin_id, quantite, quantite_restante, date_besoin)
            VALUES (:ville_id, :besoin_id, :quantite, :quantite, :date_besoin)
        ");

        return $stmt->execute([
            ':ville_id' => $ville_id,
            ':besoin_id' => $besoin_id,
            ':quantite' => $quantite,
            ':date_besoin' => $date_besoin
        ]);
    }

}
?>