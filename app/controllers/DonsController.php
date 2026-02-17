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
        
        $csp_nonce = Flight::get('csp_nonce');
        
        Flight::render('dons/index', [
            'dons' => $dons,
            'csp_nonce' => $csp_nonce
        ]);
    }

    /**
     * Traite l'ajout d'un nouveau don avec dispatch automatique
     */
    public function addDon()
    {
        // Récupération des données du formulaire
        $nom_donneur = Flight::request()->data->donateur ?? '';
        $besoin_id = Flight::request()->data->besoin_id ?? '';
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
    private function dispatchDon($don_id, $besoin_id, $quantite_disponible, $date_distribution, $simulation_type = 'chrono')
    {
        $donsModel = new DonModels(Flight::db());
        
        // Récupérer tous les besoins des villes pour ce type de besoin
        $besoins_villes = $donsModel->getBesoinsVilleByBesoinId($besoin_id);
        
        // Trier selon le type de simulation
        if ($simulation_type === 'min') {
            // Trier par quantité restante croissante (besoin minimal en premier)
            usort($besoins_villes, function($a, $b) {
                return $a['quantite_restante'] <=> $b['quantite_restante'];
            });
        }
        // Pour 'chrono', on garde l'ordre par défaut (date_besoin ASC)
        
        $distributions_count = 0;
        $quantite_restante_don = $quantite_disponible;

        foreach ($besoins_villes as $besoin_ville) {
            if ($quantite_restante_don <= 0) {
                break;
            }
            
            $quantite_besoin_ville = $besoin_ville['quantite_restante'];
            
            if ($quantite_besoin_ville > 0) {
                // Calculer la quantité à distribuer
                $quantite_a_distribuer = min($quantite_restante_don, $quantite_besoin_ville);
                
                // Insérer la distribution
                $donsModel->insertDistribution(
                    $besoin_ville['ville_id'],
                    $besoin_id,
                    $quantite_a_distribuer,
                    $date_distribution
                );
                
                // Mettre à jour la quantité restante du besoin de la ville
                $nouvelle_quantite_restante = $quantite_besoin_ville - $quantite_a_distribuer;
                $donsModel->updateBesoinVilleQuantite(
                    $besoin_ville['id'],
                    $nouvelle_quantite_restante
                );
                
                // Mettre à jour la quantité restante du don
                $quantite_restante_don -= $quantite_a_distribuer;
                
                $distributions_count++;
            }
        }
        
        // Mettre à jour la quantité restante du don
        $donsModel->updateDonQuantiteRestante($don_id, $quantite_restante_don);
        
        return $distributions_count;
    }

    /**
     * Enregistre un don sans dispatch automatique
     */
    public function store()
    {
        try {
            // Récupérer et valider les données du formulaire
            // Utiliser 'donateur' au lieu de 'nom_donneur' car c'est le nom du champ dans le formulaire
            $nom_donneur = Flight::request()->data->donateur ?? '';
            $besoin_id = Flight::request()->data->besoin_id ?? 0;
            $quantite_don = Flight::request()->data->quantite_don ?? 0;
            // Utiliser 'date_saisie' au lieu de 'date_don' car c'est le nom du champ dans le formulaire
            $date_saisie = Flight::request()->data->date_saisie ?? '';

            // Validation
            if (empty($nom_donneur)) {
                Flight::redirect('/dons/give?error=' . urlencode("Le nom du donateur est requis"));
                return;
            }
            
            if ($besoin_id <= 0) {
                Flight::redirect('/dons/give?error=' . urlencode("Le type de don est requis"));
                return;
            }
            
            if ($quantite_don <= 0) {
                Flight::redirect('/dons/give?error=' . urlencode("La quantité doit être un nombre positif"));
                return;
            }
            
            if (empty($date_saisie)) {
                Flight::redirect('/dons/give?error=' . urlencode("La date de réception est requise"));
                return;
            }

            // Conversion de la date au format SQL
            $date_don = date('Y-m-d', strtotime($date_saisie));

            // Commencer une transaction
            Flight::db()->beginTransaction();

            $donsModel = new DonModels(Flight::db());

            // Insérer le don SANS dispatch automatique
            $don_id = $donsModel->insertDon($nom_donneur, $besoin_id, $quantite_don, $date_don);

            if (!$don_id) {
                throw new \Exception("Erreur lors de l'insertion du don");
            }

            // Valider la transaction
            Flight::db()->commit();

            // Rediriger vers la page de simulation
            Flight::redirect('/dons/simulation?success=' . urlencode("Don enregistré avec succès ! Vous pouvez maintenant simuler la distribution."));
        } catch (\Exception $e) {
            // Annuler la transaction en cas d'erreur
            Flight::db()->rollBack();
            error_log("Erreur lors de l'ajout du don: " . $e->getMessage());
            Flight::redirect('/dons/give?error=' . urlencode("Une erreur est survenue lors de l'enregistrement du don"));
        }
    }

    /**
     * Affiche la page de simulation
     */
    public function simulation()
    {
        $donsModel = new DonModels(Flight::db());
        
        // Récupérer tous les dons non distribués (quantite_restante > 0)
        $dons_non_distribues = $donsModel->getDonsNonDistribues();
        
        // Récupérer le nonce CSP depuis l'application
        $csp_nonce = Flight::get('csp_nonce');
        
        Flight::render('simulation', [
            'dons' => $dons_non_distribues,
            'success' => Flight::request()->query->success ?? null,
            'error' => Flight::request()->query->error ?? null,
            'csp_nonce' => $csp_nonce
        ]);
    }

    /**
     * Simule la distribution sans modification en base
     */
    public function simuler()
    {
        try {
            $donsModel = new DonModels(Flight::db());
            
            // Récupérer le type de simulation choisi
            $simulation_type = Flight::request()->data->simulation_type ?? 'chrono';
            
            $dons_non_distribues = $donsModel->getDonsNonDistribues();
            
            $simulation_results = [];
            
            foreach ($dons_non_distribues as $don) {
                if ($don['quantite_restante'] <= 0) {
                    continue;
                }
                
                // Simuler le dispatch pour ce don selon le type choisi
                $result = $this->simulerDispatchDon(
                    $don['id'],
                    $don['besoin_id'],
                    $don['quantite_restante'],
                    $simulation_type
                );
                
                if (!empty($result['distributions'])) {
                    $simulation_results[] = [
                        'don' => $don,
                        'distributions' => $result['distributions'],
                        'quantite_restante_apres' => $result['quantite_restante']
                    ];
                }
            }
            
            // Récupérer le nonce CSP depuis l'application
            $csp_nonce = Flight::get('csp_nonce');
            
            Flight::render('simulation', [
                'dons' => $dons_non_distribues,
                'simulation_results' => $simulation_results,
                'show_validation' => !empty($simulation_results),
                'simulation_type' => $simulation_type,
                'csp_nonce' => $csp_nonce
            ]);
        } catch (\Exception $e) {
            error_log("Erreur lors de la simulation: " . $e->getMessage());
            Flight::redirect('/dons/simulation?error=' . urlencode("Une erreur est survenue lors de la simulation"));
        }
    }

    /**
     * Simule le dispatch d'un don SANS modifier la base de données
     */
    private function simulerDispatchDon($don_id, $besoin_id, $quantite_disponible, $simulation_type = 'chrono')
    {
        $donsModel = new DonModels(Flight::db());
        
        // Récupérer tous les besoins des villes pour ce type de besoin
        $besoins_villes = $donsModel->getBesoinsVilleByBesoinId($besoin_id);
        
        // Trier selon le type de simulation
        if ($simulation_type === 'min') {
            usort($besoins_villes, function($a, $b) {
                return $a['quantite_restante'] <=> $b['quantite_restante'];
            });
        }
        // Pour 'chrono', on garde l'ordre par défaut (date_besoin ASC)
        
        $distributions = [];
        $quantite_restante_don = $quantite_disponible;
        
        foreach ($besoins_villes as $besoin_ville) {
            if ($quantite_restante_don <= 0) {
                break;
            }
            
            $quantite_besoin_ville = $besoin_ville['quantite_restante'];
            
            if ($quantite_besoin_ville > 0) {
                // Calculer la quantité à distribuer
                $quantite_a_distribuer = min($quantite_restante_don, $quantite_besoin_ville);
                
                $distributions[] = [
                    'ville_id' => $besoin_ville['ville_id'],
                    'ville_nom' => $besoin_ville['nom_ville'],
                    'besoin_id' => $besoin_id,
                    'besoin_nom' => $besoin_ville['nom_besoin'],
                    'quantite' => $quantite_a_distribuer,
                    'quantite_besoin_avant' => $quantite_besoin_ville,
                    'quantite_besoin_apres' => $quantite_besoin_ville - $quantite_a_distribuer
                ];
                
                $quantite_restante_don -= $quantite_a_distribuer;
            }
        }
        
        return [
            'distributions' => $distributions,
            'quantite_restante' => $quantite_restante_don
        ];
    }

    /**
     * Valide et exécute réellement les distributions
     */
    public function valider()
    {
        try {
            // Commencer une transaction
            Flight::db()->beginTransaction();
            
            $donsModel = new DonModels(Flight::db());
            
            // Récupérer le type de simulation depuis la session ou un paramètre
            $simulation_type = Flight::request()->data->simulation_type ?? 'chrono';
            
            // Récupérer tous les dons non distribués
            $dons_non_distribues = $donsModel->getDonsNonDistribues();
            
            $total_distributions = 0;
            $date_distribution = date('Y-m-d');
            
            foreach ($dons_non_distribues as $don) {
                if ($don['quantite_restante'] <= 0) {
                    continue;
                }
                
                // Exécuter le dispatch réel pour ce don
                $distributions_count = $this->dispatchDon(
                    $don['id'],
                    $don['besoin_id'],
                    $don['quantite_restante'],
                    $date_distribution,
                    $simulation_type
                );
                
                $total_distributions += $distributions_count;
            }
            
            // Valider la transaction
            Flight::db()->commit();
            
            $message = "Distribution validée avec succès ! $total_distributions distribution(s) effectuée(s).";
            Flight::redirect('/dons?success=' . urlencode($message));
        } catch (\Exception $e) {
            // Annuler la transaction en cas d'erreur
            Flight::db()->rollBack();
            error_log("Erreur lors de la validation: " . $e->getMessage());
            Flight::redirect('/dons/simulation?error=' . urlencode("Une erreur est survenue lors de la validation"));
        }
    }
}