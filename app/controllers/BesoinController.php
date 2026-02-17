<?php

namespace app\controllers;

use app\models\BesoinModel;
use Flight;

class BesoinController
{

    public function all_besoins()
    {
        $besoinModel = new BesoinModel(Flight::db());
        $besoins = $besoinModel->get_all_besoins();

        // Récupérer le nonce CSP depuis l'application
        $csp_nonce = Flight::get('csp_nonce');

        Flight::render('saisie_don', [
            'besoins' => $besoins,
            'csp_nonce' => $csp_nonce
        ]);
    }

    public function showInsertionForm()
    {
        $model = new BesoinModel(Flight::db());
        $villes = $model->getVilles();
        $besoins = $model->getBesoinsWithTypes();

        Flight::render('insertionBesoins', [
            'villes' => $villes,
            'besoins' => $besoins
        ]);
    }

    public function addBesoin()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Flight::redirect('/besoins/insert');
            return;
        }
        $ville_id = $_POST['ville_id'] ?? null;
        $besoin_id = $_POST['besoin_id'] ?? null;
        $quantite = $_POST['quantite'] ?? null;
        $date_besoin = $_POST['date_besoin'] ?? date('Y-m-d');

        if (!$ville_id || !$besoin_id || !$quantite) {
            $model = new BesoinModel(Flight::db());
            Flight::render('insertionBesoins', [
                'error' => 'Tous les champs sont obligatoires',
                'villes' => $model->getVilles(),
                'besoins' => $model->getBesoinsWithTypes()
            ]);
            return;
        }

        try {
            $model = new BesoinModel(Flight::db());
            $model->insertBesoinVille($ville_id, $besoin_id, (int) $quantite, $date_besoin);

            Flight::render('insertionBesoins', [
                'success' => 'Besoin ajouté avec succès',
                'villes' => $model->getVilles(),
                'besoins' => $model->getBesoinsWithTypes()
            ]);
        } catch (\Exception $e) {
            $model = new BesoinModel(Flight::db());
            Flight::render('insertionBesoins', [
                'error' => 'Erreur lors de l\'ajout : ' . $e->getMessage(),
                'villes' => $model->getVilles(),
                'besoins' => $model->getBesoinsWithTypes()
            ]);
        }
    }
}