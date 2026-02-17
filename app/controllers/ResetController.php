<?php
namespace app\controllers;

use Flight;
use PDO;

class ResetController
{
    public function showResetPage()
    {
        $csp_nonce = Flight::get('csp_nonce');
        Flight::render('reset', ['csp_nonce' => $csp_nonce]);
    }

    public function resetDatabase()
    {
        try {
            $db = Flight::db();
            
            $sqlFile = __DIR__ . '/../../sql/insertionBase.sql';
            
            if (!file_exists($sqlFile)) {
                Flight::json([
                    'success' => false,
                    'message' => 'Fichier SQL introuvable'
                ], 404);
                return;
            }
            
            $sql = file_get_contents($sqlFile);
            
            $db->exec('SET FOREIGN_KEY_CHECKS = 0');
            
            $db->exec('TRUNCATE TABLE distributions');
            $db->exec('TRUNCATE TABLE dons');
            $db->exec('TRUNCATE TABLE achats');
            $db->exec('TRUNCATE TABLE achats_besoins');
            $db->exec('TRUNCATE TABLE frais_achat_besoin');
            $db->exec('TRUNCATE TABLE besoins_ville');
            $db->exec('TRUNCATE TABLE besoins');
            $db->exec('TRUNCATE TABLE types_besoin');
            $db->exec('TRUNCATE TABLE villes');
            
            $db->exec('SET FOREIGN_KEY_CHECKS = 1');
            
            $db->exec($sql);
            
            Flight::json([
                'success' => true,
                'message' => 'Base de données réinitialisée avec succès !'
            ]);
            
        } catch (\Exception $e) {
            Flight::json([
                'success' => false,
                'message' => 'Erreur : ' . $e->getMessage()
            ], 500);
        }
    }
}