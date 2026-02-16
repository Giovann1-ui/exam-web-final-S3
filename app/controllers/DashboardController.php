<?php

namespace app\controllers;

use app\models\BesoinVille;
use flight\Engine;

class DashboardController {
    protected Engine $app;
    protected BesoinVille $besoinVilleModel;

    public function __construct(Engine $app) {
        $this->app = $app;
        $this->besoinVilleModel = new BesoinVille($app->db());
    }

    public function index(): void {
        $villesAvecBesoins = $this->besoinVilleModel->getVillesAvecBesoins();
        $besoinsAttribues = $this->besoinVilleModel->getBesoinsAttribuesParVille();
        $villesSatisfaites = $this->besoinVilleModel->getVillesSatisfaites();
        $donsParVille = $this->besoinVilleModel->getDonsParVille();
        $statistiques = $this->besoinVilleModel->getStatistiques();

        // Organiser les besoins non satisfaits par ville
        $villesGroupees = [];
        foreach ($villesAvecBesoins as $besoin) {
            $villeId = $besoin['ville_id'];
            if (!isset($villesGroupees[$villeId])) {
                $villesGroupees[$villeId] = [
                    'ville_id' => $villeId,
                    'nom_ville' => $besoin['nom_ville'],
                    'besoins' => []
                ];
            }
            $villesGroupees[$villeId]['besoins'][] = $besoin;
        }

        // Organiser les besoins attribués par ville
        $besoinsAttribuesGroupes = [];
        foreach ($besoinsAttribues as $besoin) {
            $villeId = $besoin['ville_id'];
            if (!isset($besoinsAttribuesGroupes[$villeId])) {
                $besoinsAttribuesGroupes[$villeId] = [
                    'ville_id' => $villeId,
                    'nom_ville' => $besoin['nom_ville'],
                    'besoins' => []
                ];
            }
            $besoinsAttribuesGroupes[$villeId]['besoins'][] = $besoin;
        }

        // Organiser les dons par ville
        $donsGroupes = [];
        foreach ($donsParVille as $don) {
            $villeId = $don['ville_id'];
            if (!isset($donsGroupes[$villeId])) {
                $donsGroupes[$villeId] = [];
            }
            $donsGroupes[$villeId][] = $don;
        }

        $this->app->render('dashboard', [
            'villes' => $villesGroupees,
            'besoins_attribues' => $besoinsAttribuesGroupes,
            'villes_satisfait' => $villesSatisfaites,
            'dons' => $donsGroupes,
            'stats' => $statistiques,
            'page_title' => 'Dashboard - BNGRC',
            'base_url' => Flight::get('flight.base_url')
        ]);
    }
}