<?php
namespace app\controllers;

use app\models\HistoriqueAchatModel;
use Flight;

class HistoriqueAchatController {
    public function getAllAchats() {
        $vueHistoriqueAchatModel = new HistoriqueAchatModel(Flight::db());
        $achats = $vueHistoriqueAchatModel->getAllAchats();
        $villes = $vueHistoriqueAchatModel->getAllVilles();
        $total_montant = $vueHistoriqueAchatModel->calculerTotalMontant($achats);
        $total_quantite = $vueHistoriqueAchatModel->calculerTotalQuantite($achats);
        $base_url = Flight::get('flight.base_url');
        
        Flight::render('historique-achats', [
            'achats' => $achats,
            'villes' => $villes,
            'total_montant' => $total_montant,
            'total_quantite' => $total_quantite,
            'base_url' => $base_url
        ]);
    }
}