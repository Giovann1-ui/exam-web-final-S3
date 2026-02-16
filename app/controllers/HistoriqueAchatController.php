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
        Flight::render('historique-achats', ['achats' => $achats, 'villes' => $villes, 'total_montant' => $total_montant, 'total_quantite' => $total_quantite]);
    }
}