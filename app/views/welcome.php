<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BNGRC - Bureau National de Gestion des Risques et Catastrophes</title>
    <link href="<?= $base_url ?>/assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= $base_url ?>/assets/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .welcome-container {
            max-width: 800px;
            text-align: center;
        }
        
        .welcome-card {
            background: white;
            border-radius: 30px;
            padding: 60px 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            animation: fadeIn 0.8s;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .logo {
            font-size: 5rem;
            color: #2c3e50;
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .btn-action {
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            margin: 10px;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .btn-action:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        }
        
        .features {
            margin-top: 40px;
            text-align: left;
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            padding: 15px;
            margin-bottom: 15px;
            background: #f8f9fa;
            border-radius: 15px;
            transition: all 0.3s;
        }
        
        .feature-item:hover {
            background: #e9ecef;
            transform: translateX(10px);
        }
        
        .feature-icon {
            font-size: 2rem;
            margin-right: 20px;
            color: #27ae60;
        }
    </style>
</head>
<body>
    
    <div class="container welcome-container">
        <div class="welcome-card">
            
            <div class="logo">
                <i class="bi bi-heart-fill text-danger"></i>
            </div>
            
            <h1 class="display-4 fw-bold text-primary mb-3">
                Bienvenue au BNGRC
            </h1>
            
            <p class="lead text-muted mb-4">
                Bureau National de Gestion des Risques et Catastrophes
            </p>
            
            <?php if(!empty($message)): ?>
                <div class="alert alert-info" role="alert">
                    <i class="bi bi-info-circle me-2"></i>
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>
            
            <p class="mb-5">
                Plateforme de gestion des dons et de distribution des ressources
                pour venir en aide aux populations sinistrées.
            </p>
            
            <div class="d-flex justify-content-center flex-wrap">
                <a href="<?= $base_url ?>dons" class="btn btn-primary btn-action">
                    <i class="bi bi-list-ul me-2"></i>
                    Voir les Dons
                </a>
                <a href="<?= $base_url ?>dons/give" class="btn btn-success btn-action">
                    <i class="bi bi-plus-circle me-2"></i>
                    Faire un Don
                </a>
            </div>
            
            <div class="features">
                <h5 class="text-primary mb-3">
                    <i class="bi bi-star-fill me-2"></i>
                    Fonctionnalités
                </h5>
                
                <div class="feature-item">
                    <i class="bi bi-box-seam feature-icon"></i>
                    <div>
                        <strong>Gestion des Dons</strong>
                        <p class="mb-0 text-muted small">Enregistrez et suivez tous les dons reçus</p>
                    </div>
                </div>
                
                <div class="feature-item">
                    <i class="bi bi-geo-alt feature-icon"></i>
                    <div>
                        <strong>Distribution Automatique</strong>
                        <p class="mb-0 text-muted small">Dispatch intelligent vers les villes dans le besoin</p>
                    </div>
                </div>
                
                <div class="feature-item">
                    <i class="bi bi-graph-up feature-icon"></i>
                    <div>
                        <strong>Suivi en Temps Réel</strong>
                        <p class="mb-0 text-muted small">Visualisez les statistiques et l'évolution des dons</p>
                    </div>
                </div>
            </div>
            
        </div>
        
        <p class="text-white text-center mt-4">
            <small>
                <i class="bi bi-shield-check me-2"></i>
                Système sécurisé et confidentiel
            </small>
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>