<?php
namespace app\controllers;

use PDO;
use app\models\DonModels;
use Flight;

class DonsController
{
    public function getAllDons()
    {
        $donsModel = new DonModels(Flight::db());
        $dons = $donsModel->getAllDons();
        Flight::render('dons/index', ['dons' => $dons]);
    }

    /**
     * Traite l'ajout d'un nouveau don avec dispatch automatique
     */
    public function addDon()
    {
        // Récupération des données du formulaire
        $nom_donneur = Flight::request()->data->donateur ?? '';
        $besoin_id = Flight::request()->data->type ?? '';
        $quantite_don = Flight::request()->data->quantite_don ?? 0;
        $date_saisie = Flight::request()->data->date_saisie ?? '';

        // Validation des données
        $errors = [];
        
        if (empty($nom_donneur)) {
            $errors[] = "Le nom du donateur est requis";
        }
        
        if (empty($besoin_id) || !is_numeric($besoin_id)) {
            $errors[] = "Le type de don est requis";
        }
        
        if (empty($quantite_don) || !is_numeric($quantite_don) || $quantite_don <= 0) {
            $errors[] = "La quantité doit être un nombre positif";
        }
        
        if (empty($date_saisie)) {
            $errors[] = "La date de réception est requise";
        }

        // Si erreurs, rediriger avec message
        if (!empty($errors)) {
            Flight::redirect('/dons/give?error=' . urlencode(implode(', ', $errors)));
            return;
        }

        // Conversion de la date au format SQL
        $date_don = date('Y-m-d', strtotime($date_saisie));

        try {
            $donsModel = new DonModels(Flight::db());
            
            // Commencer une transaction
            Flight::db()->beginTransaction();

            // 1. Insérer le don
            $don_id = $donsModel->insertDon($nom_donneur, $besoin_id, $quantite_don, $date_don);
            
            if (!$don_id) {
                throw new \Exception("Erreur lors de l'insertion du don");
            }

            // 2. Dispatcher le don vers les villes ayant des besoins
            $distributions_effectuees = $this->dispatchDon($don_id, $besoin_id, $quantite_don, $date_don);

            // Valider la transaction
            Flight::db()->commit();

            // Rediriger avec message de succès
            $message = "Don enregistré avec succès ! ";
            if ($distributions_effectuees > 0) {
                $message .= "$distributions_effectuees distribution(s) effectuée(s) automatiquement.";
            } else {
                $message .= "Aucune ville n'a de besoin correspondant pour le moment.";
            }
            
            Flight::redirect('/dons?success=' . urlencode($message));

        } catch (\Exception $e) {
            // Annuler la transaction en cas d'erreur
            Flight::db()->rollBack();
            
            error_log("Erreur lors de l'ajout du don: " . $e->getMessage());
            Flight::redirect('/dons/give?error=' . urlencode("Une erreur est survenue lors de l'enregistrement du don"));
        }
    }

    /**
     * Dispatch automatique du don vers les villes ayant des besoins
     * Retourne le nombre de distributions effectuées
     */
    private function dispatchDon($don_id, $besoin_id, $quantite_disponible, $date_distribution)
    {
        $donsModel = new DonModels(Flight::db());
        
        // Récupérer tous les besoins des villes pour ce type de besoin
        $besoins_villes = $donsModel->getBesoinsVilleByBesoinId($besoin_id);
        
        $distributions_count = 0;
        $quantite_restante_don = $quantite_disponible;

        foreach ($besoins_villes as $besoin_ville) {
            if ($quantite_restante_don <= 0) {
                break; // Plus de don disponible
            }

            $quantite_besoin = $besoin_ville['quantite_restante'];
            
            // Calculer la quantité à distribuer
            $quantite_distribuee = min($quantite_restante_don, $quantite_besoin);

            // Enregistrer la distribution
            $donsModel->insertDistribution(
                $besoin_ville['ville_id'],
                $besoin_id,
                $quantite_distribuee,
                $date_distribution
            );

            // Mettre à jour la quantité restante du besoin de la ville
            $nouvelle_quantite_besoin = $quantite_besoin - $quantite_distribuee;
            $donsModel->updateBesoinVilleQuantite(
                $besoin_ville['id'],
                $nouvelle_quantite_besoin
            );

            // Mettre à jour la quantité restante du don
            $quantite_restante_don -= $quantite_distribuee;
            
            $distributions_count++;
        }

        // Mettre à jour la quantité restante du don
        $donsModel->updateDonQuantiteRestante($don_id, $quantite_restante_don);

        return $distributions_count;
    }
}