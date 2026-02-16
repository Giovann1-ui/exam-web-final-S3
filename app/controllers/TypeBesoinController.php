<?php
namespace app\controllers;

use app\models\TypeBesoinModel;
use Flight;

class TypeBesoinController {

    /**
     * Affiche tous les objets avec leurs propriétaires
     */
    public function all_type_besoins(){
        $typeBesoinModel = new TypeBesoinModel(Flight::db());
        $type_besoins = $typeBesoinModel->get_all_type_besoins();
        Flight::render('saisie_don', ['type_besoins' => $type_besoins, 'base_url' => Flight::get('flight.base_url')]);
    }

    /**
     * Affiche un type de besoin spécifique par son ID
     */
    public function besoin($id){
        $typeBesoinModel = new TypeBesoinModel(Flight::db());
        $type_besoin = $typeBesoinModel->get_type_besoin_by_id($id);
        Flight::render('type-besoin', ['type_besoin' => $type_besoin, 'base_url' => Flight::get('flight.base_url')]);
    }
}
?>