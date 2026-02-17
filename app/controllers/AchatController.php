<?php
namespace app\controllers;

use app\models\AchatModel;
use Flight;

class AchatController
{
    /**
     * Affiche la liste des besoins restants à acheter
     */
    public function listBesoinsAcheter()
    {
        $achatModel = new AchatModel(Flight::db());
        $besoins = $achatModel->getBesoinsRestantsAcheter();
        $argent_disponible = $achatModel->getArgentDisponible();
        $csp_nonce = Flight::get('csp_nonce');

        // Grouper les besoins par ville
        $besoins_groupes = [];
        foreach ($besoins as $besoin) {
            $ville_id = $besoin['ville_id'];
            if (!isset($besoins_groupes[$ville_id])) {
                $besoins_groupes[$ville_id] = [
                    'ville_id' => $ville_id,
                    'nom_ville' => $besoin['nom_ville'],
                    'besoins' => []
                ];
            }
            $besoins_groupes[$ville_id]['besoins'][] = $besoin;
        }

        Flight::render('achats/liste-besoins', [
            'besoins_groupes' => $besoins_groupes,
            'argent_disponible' => $argent_disponible,
            'csp_nonce' => $csp_nonce
        ]);
    }

    /**
     * Affiche le formulaire de saisie d'un achat
     */
    public function showFormAchat($besoin_ville_id)
    {
        $achatModel = new AchatModel(Flight::db());
        $besoin = $achatModel->getBesoinVilleById($besoin_ville_id);

        if (!$besoin) {
            Flight::redirect('/achats/besoins?error=' . urlencode("Besoin introuvable"));
            return;
        }

        if ($achatModel->hasStockDisponible($besoin['besoin_id'])) {
            Flight::redirect('/achats/besoins?error=' . urlencode("Impossible d'acheter : du stock est déjà disponible pour " . $besoin['nom_besoin']));
            return;
        }

        $argent_disponible = $achatModel->getArgentDisponible();
        $frais_pourcentage = $achatModel->getFraisAchat($besoin['besoin_id']);
        
        // Calculer le montant nécessaire
        $montant_base = $besoin['quantite_restante'] * $besoin['prix_unitaire'];
        $montant_frais = $montant_base * ($frais_pourcentage / 100);
        $montant_total = $montant_base + $montant_frais;

        $csp_nonce = Flight::get('csp_nonce');

        Flight::render('achats/form-achat', [
            'besoin' => $besoin,
            'argent_disponible' => $argent_disponible,
            'frais_pourcentage' => $frais_pourcentage,
            'montant_base' => $montant_base,
            'montant_frais' => $montant_frais,
            'montant_total' => $montant_total,
            'csp_nonce' => $csp_nonce
        ]);
    }

    /**
     * Traite l'ajout d'un achat
     */
    public function addAchat()
    {
        $besoin_ville_id = Flight::request()->data->besoin_ville_id ?? '';
        $quantite = Flight::request()->data->quantite ?? 0;
        $date_achat = Flight::request()->data->date_achat ?? '';

        // Validation
        $errors = [];

        if (empty($besoin_ville_id) || !is_numeric($besoin_ville_id)) {
            $errors[] = "Besoin invalide";
        }

        if (empty($quantite) || !is_numeric($quantite) || $quantite <= 0) {
            $errors[] = "La quantité doit être un nombre positif";
        }

        if (empty($date_achat)) {
            $errors[] = "La date d'achat est requise";
        }

        if (!empty($errors)) {
            Flight::redirect('/achats/form/' . $besoin_ville_id . '?error=' . urlencode(implode(', ', $errors)));
            return;
        }

        try {
            $achatModel = new AchatModel(Flight::db());
            
            // Commencer une transaction
            Flight::db()->beginTransaction();

            // Récupérer les informations du besoin
            $besoin = $achatModel->getBesoinVilleById($besoin_ville_id);

            if (!$besoin) {
                throw new \Exception("Besoin introuvable");
            }

            // Vérifier s'il y a du stock
            if ($achatModel->hasStockDisponible($besoin['besoin_id'])) {
                throw new \Exception("Impossible d'acheter : du stock est déjà disponible pour " . $besoin['nom_besoin']);
            }

            // Vérifier que la quantité n'excède pas le besoin restant
            if ($quantite > $besoin['quantite_restante']) {
                throw new \Exception("La quantité dépasse le besoin restant");
            }

            // Calculer le montant total
            $prix_unitaire = $besoin['prix_unitaire'];
            $frais_pourcentage = $achatModel->getFraisAchat($besoin['besoin_id']);
            $montant_base = $quantite * $prix_unitaire;
            $montant_total = $montant_base * (1 + $frais_pourcentage / 100);

            // Vérifier l'argent disponible
            $argent_disponible = $achatModel->getArgentDisponible();
            if ($montant_total > $argent_disponible) {
                throw new \Exception("Fonds insuffisants. Disponible: " . number_format($argent_disponible, 2) . " Ar, Requis: " . number_format($montant_total, 2) . " Ar");
            }

            // Conversion de la date
            $date_achat_sql = date('Y-m-d', strtotime($date_achat));

            // Insérer l'achat
            $achat_id = $achatModel->insertAchat(
                $besoin_ville_id,
                $quantite
            );

            if (!$achat_id) {
                throw new \Exception("Erreur lors de l'insertion de l'achat");
            }

            // Mettre à jour la quantité restante du besoin
            $nouvelle_quantite_restante = $besoin['quantite_restante'] - $quantite;
            $achatModel->updateBesoinVilleQuantite($besoin_ville_id, $nouvelle_quantite_restante);

            // Déduire l'argent des dons
            if (!$achatModel->deduireArgent($montant_total)) {
                throw new \Exception("Erreur lors de la déduction de l'argent");
            }

            // Valider la transaction
            Flight::db()->commit();

            Flight::redirect('/historique-achats?success=' . urlencode("Achat enregistré avec succès !"));

        } catch (\Exception $e) {
            // Annuler la transaction
            Flight::db()->rollBack();
            
            error_log("Erreur lors de l'ajout de l'achat: " . $e->getMessage());
            Flight::redirect('/achats/form/' . $besoin_ville_id . '?error=' . urlencode($e->getMessage()));
        }
    }
}