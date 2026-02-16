<?php
namespace app\controllers;

use app\models\BesoinModel;
use Flight;

class BesoinController {

    /**
     * Affiche tous les objets avec leurs propriétaires
     */
    public function all_besoins(){
        $besoinModel = new BesoinModel(Flight::db());
        $besoins = $besoinModel->get_all_besoins();
        Flight::render('saisie_don', ['besoins' => $besoins, 'base_url' => Flight::get('flight.base_url')]);
    }

    /**
     * Affiche un besoin spécifique par son ID
     */
    public function besoin($id){
        $besoinModel = new BesoinModel(Flight::db());
        $besoin = $besoinModel->get_besoin_by_id($id);
        Flight::render('type-besoin', ['besoin' => $besoin, 'base_url' => Flight::get('flight.base_url')]);
    }
}
?>