<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enregistrement d'un Don - BNGRC</title>
    <link href="<?=$base_url?>/assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?=$base_url?>/assets/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #27ae60;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            background: var(--primary-color) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .form-container {
            max-width: 700px;
            margin: 50px auto;
        }
        
        .form-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            overflow: hidden;
        }
        
        .form-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #34495e 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        
        .form-header i {
            font-size: 4rem;
            margin-bottom: 15px;
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        .form-body {
            padding: 40px;
        }
        
        .alert-rule {
            background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);
            border-left: 5px solid #f97316;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        .form-control, .form-select {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(39, 174, 96, 0.25);
        }
        
        .btn-submit {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #229954 100%);
            border: none;
            padding: 15px;
            font-weight: 600;
            font-size: 1.1rem;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(39, 174, 96, 0.3);
            transition: all 0.3s;
        }
        
        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(39, 174, 96, 0.4);
        }
        
        .input-icon {
            position: relative;
        }
        
        .input-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        
        .input-icon .form-control,
        .input-icon .form-select {
            padding-left: 45px;
        }
    </style>
</head>
<body>
    
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="<?=$base_url?>">
                <i class="bi bi-heart-fill me-2"></i>
                BNGRC - Gestion des Dons
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?=$base_url?>/dons">
                            <i class="bi bi-list-ul me-1"></i>
                            Liste des Dons
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?=$base_url?>/dons/give">
                            <i class="bi bi-plus-circle me-1"></i>
                            Ajouter un Don
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container form-container">
        <div class="form-card">
            
            <!-- En-tête du formulaire -->
            <div class="form-header">
                <i class="bi bi-gift-fill"></i>
                <h2 class="mb-2">Enregistrement d'un Don</h2>
                <p class="mb-0 opacity-75">Merci pour votre générosité</p>
            </div>

            <!-- Corps du formulaire -->
            <div class="form-body">
                
                <!-- Messages d'erreur ou de succès -->
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <?= htmlspecialchars($_GET['error']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Règle de gestion -->
                <div class="alert-rule">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-info-circle-fill text-warning fs-4 me-3 mt-1"></i>
                        <div>
                            <strong>Règle de gestion :</strong>
                            <p class="mb-0 mt-1">Le dispatch se fera automatiquement par ordre de date et de saisie vers les villes ayant des besoins correspondants.</p>
                        </div>
                    </div>
                </div>

                <!-- Formulaire -->
                <form method="POST" action="<?=$base_url?>/dons/add" id="donForm">
                    
                    <!-- Donateur -->
                    <div class="mb-4">
                        <label for="donateur" class="form-label">
                            <i class="bi bi-person-fill text-primary me-2"></i>
                            Donateur / Libellé
                        </label>
                        <div class="input-icon">
                            <i class="bi bi-person"></i>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="donateur" 
                                name="donateur" 
                                placeholder="Ex: Association Humanitaire X"
                                required
                            >
                        </div>
                        <small class="text-muted">Nom de l'organisation ou de la personne qui fait le don</small>
                    </div>

                    <!-- Type de don -->
                    <div class="mb-4">
                        <label for="type" class="form-label">
                            <i class="bi bi-box-seam text-info me-2"></i>
                            Type de Don
                        </label>
                        <div class="input-icon">
                            <i class="bi bi-box-seam"></i>
                            <select class="form-select" id="type" name="type" required>
                                <option value="" disabled selected>-- Sélectionnez un type de don --</option>
                                <?php foreach ($besoins as $besoin): ?>
                                    <option value="<?= $besoin['id'] ?>">
                                        <?= htmlspecialchars($besoin['nom_besoin']) ?>
                                        (<?= number_format($besoin['prix_unitaire'], 2) ?> Ar)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <small class="text-muted">Choisissez le type de ressource que vous souhaitez donner</small>
                    </div>

                    <!-- Quantité -->
                    <div class="mb-4">
                        <label for="quantite_don" class="form-label">
                            <i class="bi bi-123 text-warning me-2"></i>
                            Quantité Offerte
                        </label>
                        <div class="input-icon">
                            <i class="bi bi-hash"></i>
                            <input 
                                type="number" 
                                class="form-control" 
                                id="quantite_don" 
                                name="quantite_don"
                                min="1"
                                placeholder="Entrez la quantité"
                                required
                            >
                        </div>
                        <small class="text-muted">Nombre d'unités que vous souhaitez donner</small>
                    </div>

                    <!-- Date de réception -->
                    <div class="mb-4">
                        <label for="date_saisie" class="form-label">
                            <i class="bi bi-calendar-event text-danger me-2"></i>
                            Date de Réception
                        </label>
                        <div class="input-icon">
                            <i class="bi bi-calendar"></i>
                            <input 
                                type="datetime-local" 
                                class="form-control" 
                                id="date_saisie" 
                                name="date_saisie" 
                                value="<?= date('Y-m-d\TH:i') ?>"
                                required
                            >
                        </div>
                        <small class="text-muted">Date et heure de réception du don</small>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="d-grid gap-3">
                        <button type="submit" class="btn btn-success btn-submit btn-lg">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Enregistrer et Simuler le Dispatch
                        </button>
                        <a href="<?=$base_url?>/dons" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-arrow-left me-2"></i>
                            Retour à la liste
                        </a>
                    </div>

                </form>

            </div>

        </div>

        <!-- Footer info -->
        <div class="text-center mt-4 text-white">
            <small>
                <i class="bi bi-shield-check me-2"></i>
                Toutes les informations sont sécurisées et traitées confidentiellement
            </small>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
