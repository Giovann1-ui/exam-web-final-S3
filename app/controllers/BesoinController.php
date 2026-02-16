<?php
namespace app\controllers;

use app\models\BesoinModel;
use Flight;

class BesoinController {

    /**
     * Affiche tous les besoins avec leurs propriétaires
     */
    public function all_besoins(){
        $besoinModel = new BesoinModel(Flight::db());
        $besoins = $besoinModel->get_all_besoins();
        
        // Récupérer le nonce CSP depuis l'application
        $csp_nonce = Flight::get('csp_nonce');
        $base_url = Flight::get('flight.base_url');
        
        Flight::render('saisie_don', [
            'besoins' => $besoins,
            'csp_nonce' => $csp_nonce,
            'base_url' => $base_url
        ]);
    }

    /**
     * Affiche un besoin spécifique par son ID
     */
    public function besoin($id){
        $besoinModel = new BesoinModel(Flight::db());
        $besoin = $besoinModel->get_besoin_by_id($id);
        
        // Récupérer le nonce CSP depuis l'application
        $csp_nonce = Flight::get('csp_nonce');
        $base_url = Flight::get('flight.base_url');
        
        Flight::render('type-besoin', [
            'besoin' => $besoin,
            'csp_nonce' => $csp_nonce,
            'base_url' => $base_url
        ]);
    }
}