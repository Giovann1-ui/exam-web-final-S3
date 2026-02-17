<?php
namespace app\controllers;

use Flight;
use PDO;

class ResetController
{
    public function resetDatabase()
    {
        try {
            $db = Flight::db();
            
            // Chemin vers le fichier SQL d'origine
            $sqlFile = __DIR__ . '/../../sql/16-02-2026_06.sql';
            
            if (!file_exists($sqlFile)) {
                Flight::redirect('/?error=sql_file_not_found');
                return;
            }
            
            // Lire le contenu du fichier SQL
            $sql = file_get_contents($sqlFile);
            
            if (empty($sql)) {
                Flight::redirect('/?error=sql_file_empty');
                return;
            }
            
            // Désactiver temporairement les contraintes de clés étrangères
            $db->exec('SET FOREIGN_KEY_CHECKS = 0');
            
            // Diviser le script SQL en requêtes individuelles
            $statements = array_filter(
                array_map('trim', explode(';', $sql)),
                function($stmt) {
                    return !empty($stmt) && !preg_match('/^--/', $stmt);
                }
            );
            
            // Exécuter chaque requête séparément
            foreach ($statements as $statement) {
                if (!empty($statement)) {
                    $db->exec($statement);
                }
            }
            
            // Réactiver les contraintes
            $db->exec('SET FOREIGN_KEY_CHECKS = 1');
            
            // Rediriger vers la page d'accueil avec succès
            Flight::redirect('/?success=database_reset');
            
        } catch (\Exception $e) {
            error_log("Erreur réinitialisation BD: " . $e->getMessage());
            Flight::redirect('/?error=' . urlencode($e->getMessage()));
        }
    }
}