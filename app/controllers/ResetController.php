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
            
            $sqlFile = __DIR__ . '/../../sql/17-02-2026_03.sql';
            
            if (!file_exists($sqlFile)) {
                Flight::redirect('/?error=sql_file_not_found');
                return;
            }
            
            $sql = file_get_contents($sqlFile);
            
            if (empty($sql)) {
                Flight::redirect('/?error=sql_file_empty');
                return;
            }
            
            $db->exec('SET FOREIGN_KEY_CHECKS = 0');
            
            $statements = array_filter(
                array_map('trim', explode(';', $sql)),
                function($stmt) {
                    return !empty($stmt) && !preg_match('/^--/', $stmt);
                }
            );
            
            foreach ($statements as $statement) {
                if (!empty($statement)) {
                    $db->exec($statement);
                }
            }
            
            $db->exec('SET FOREIGN_KEY_CHECKS = 1');
            
            Flight::redirect('/?success=database_reset');
            
        } catch (\Exception $e) {
            error_log("Erreur réinitialisation BD: " . $e->getMessage());
            Flight::redirect('/?error=' . urlencode($e->getMessage()));
        }
    }
}