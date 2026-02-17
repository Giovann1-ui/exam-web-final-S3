<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulation de Distribution - BNGRC</title>
    <style>
        /* Bloque toutes les transitions au chargement */
        * {
            transition: none !important;
        }
    </style>
</head>
<body>
<?php 
$csp_nonce = $csp_nonce ?? '';
?>
<?php include __DIR__ . '/layouts/navigation.php'; ?>
<style nonce="<?= $csp_nonce ?>">
    .simulation-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .simulation-header h1 {
        font-size: 2rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .card-custom {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 2rem;
        overflow: hidden;
    }
    
    .card-header-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.25rem 1.5rem;
        border: none;
        font-weight: 600;
    }
    
    .card-header-success {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }
    
    .table-simulation {
        margin-bottom: 0;
    }
    
    .table-simulation thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        color: #495057;
    }
    
    .table-simulation tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .badge-type {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.85rem;
    }
    
    .simulation-result-card {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border-left: 5px solid #667eea;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }
    
    .simulation-result-card h6 {
        color: #667eea;
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 1rem;
    }
    
    .quantity-badge {
        display: inline-block;
        padding: 0.5rem 1.2rem;
        border-radius: 25px;
        font-weight: 600;
        font-size: 1rem;
    }
    
    .quantity-available {
        background-color: #e3f2fd;
        color: #1976d2;
    }
    
    .quantity-remaining {
        background-color: #fff3e0;
        color: #f57c00;
    }
    
    .quantity-remaining.zero {
        background-color: #e8f5e9;
        color: #388e3c;
    }
    
    .btn-simulate {
        background: linear-gradient(135deg, #17ead9 0%, #087cc9 100%);
        border: none;
        color: white;
        padding: 1rem 2.5rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 1.1rem;
        box-shadow: 0 4px 15px rgba(51, 84, 249, 0.3);
        cursor: pointer;
    }
    
    .btn-simulate:hover {
        opacity: 0.9;
    }
    
    .btn-validate {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        border: none;
        color: white;
        padding: 1rem 2.5rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 1.1rem;
        box-shadow: 0 4px 15px rgba(56, 239, 125, 0.3);
        cursor: pointer;
    }
    
    .btn-validate:hover {
        opacity: 0.9;
    }
    
    .alert-custom {
        border-radius: 10px;
        border: none;
        padding: 1.25rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    .alert-warning-custom {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        color: #856404;
        border-left: 5px solid #ffc107;
    }
    
    .distribution-table {
        background: white;
        border-radius: 8px;
        overflow: hidden;
    }
    
    .distribution-table thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .distribution-table thead th {
        border: none;
        padding: 1rem;
        font-weight: 600;
    }
    
    .distribution-table tbody td {
        padding: 1rem;
        vertical-align: middle;
    }
    
    .city-icon {
        font-size: 1.2rem;
        color: #667eea;
        margin-right: 0.5rem;
    }
    
    .quantity-distributed {
        color: #11998e;
        font-weight: 700;
        font-size: 1.1rem;
    }
    
    .no-data-message {
        text-align: center;
        padding: 3rem;
        color: #6c757d;
    }
    
    .no-data-message i {
        font-size: 4rem;
        color: #17a2b8;
        margin-bottom: 1rem;
        display: block;
    }
</style>

<div class="main-content">
    <div class="simulation-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1><i class="bi bi-graph-up-arrow me-3"></i>Simulation de Distribution</h1>
                <p class="mb-0 opacity-75">Visualisez l'impact de la distribution avant validation</p>
            </div>
            <a href="<?= base_url('/dons') ?>" class="btn btn-light btn-lg">
                <i class="bi bi-arrow-left me-2"></i>Retour aux dons
            </a>
        </div>
    </div>

    <?php if (isset($success)): ?>
        <div class="alert alert-success alert-custom" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-custom" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="card-custom">
        <div class="card-header-custom">
            <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i>Dons en attente de distribution</h5>
        </div>
        <div class="card-body p-0">
            <?php if (empty($dons)): ?>
                <div class="no-data-message">
                    <i class="bi bi-inbox"></i>
                    <h5>Aucun don en attente de distribution</h5>
                    <p class="text-muted">Tous les dons ont été distribués ou aucun don n'a été enregistré.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-simulation">
                        <thead>
                            <tr>
                                <th><i class="bi bi-person me-2"></i>Donneur</th>
                                <th><i class="bi bi-box me-2"></i>Besoin</th>
                                <th><i class="bi bi-tag me-2"></i>Type</th>
                                <th class="text-end"><i class="bi bi-box-seam me-2"></i>Qté Totale</th>
                                <th class="text-end"><i class="bi bi-boxes me-2"></i>Qté Restante</th>
                                <th><i class="bi bi-calendar me-2"></i>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dons as $don): ?>
                                <tr>
                                    <td class="fw-semibold"><?= htmlspecialchars($don['nom_donneur']) ?></td>
                                    <td><?= htmlspecialchars($don['nom_besoin']) ?></td>
                                    <td><span class="badge badge-type bg-secondary"><?= htmlspecialchars($don['nom_type_besoin']) ?></span></td>
                                    <td class="text-end"><?= number_format($don['quantite'], 0, ',', ' ') ?></td>
                                    <td class="text-end"><strong class="text-primary"><?= number_format($don['quantite_restante'], 0, ',', ' ') ?></strong></td>
                                    <td><?= date('d/m/Y', strtotime($don['date_don'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Formulaire de sélection du type de distribution -->
                <div class="p-4 border-top">
                    <form method="POST" action="<?= base_url('/dons/simuler') ?>">
                        <div class="row align-items-end">
                            <div class="col-md-8">
                                <label for="type_distribution_id" class="form-label fw-semibold">
                                    <i class="bi bi-diagram-3 me-2"></i>Type de Distribution
                                </label>
                                <select name="type_distribution_id" id="type_distribution_id" class="form-select" required>
                                    <?php if (!empty($types_distribution)): ?>
                                        <?php foreach ($types_distribution as $type): ?>
                                            <option value="<?= $type['id'] ?>" 
                                                <?= (isset($type_distribution_selectionne) && $type_distribution_selectionne == $type['id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($type['nom_type_distribution']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <small class="text-muted mt-1 d-block">
                                    <strong>Par date de demande :</strong> Priorité aux besoins les plus anciens<br>
                                    <strong>Par demande minimum :</strong> Priorité aux plus petites quantités<br>
                                    <strong>Distribution proportionnelle :</strong> Répartition équitable selon les besoins
                                </small>
                            </div>
                            <div class="col-md-4 text-end">
                                <button type="submit" class="btn btn-simulate w-100">
                                    <i class="bi bi-play-circle me-2"></i>Lancer la simulation
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (isset($simulation_results) && !empty($simulation_results)): ?>
        <div class="card-custom">
            <div class="card-header-success">
                <h5 class="mb-0"><i class="bi bi-eye me-2"></i>Résultats de la simulation</h5>
            </div>
            <div class="card-body">
                <?php foreach ($simulation_results as $index => $result): ?>
                    <div class="simulation-result-card">
                        <h6>
                            <i class="bi bi-gift-fill me-2"></i>
                            Don de <?= htmlspecialchars($result['don']['nom_donneur']) ?> - 
                            <?= htmlspecialchars($result['don']['nom_besoin']) ?>
                        </h6>
                        
                        <div class="mb-3">
                            <span class="quantity-badge quantity-available">
                                <i class="bi bi-box-seam me-2"></i>Disponible: <?= number_format($result['don']['quantite_restante'], 0, ',', ' ') ?>
                            </span>
                            <i class="bi bi-arrow-right mx-3"></i>
                            <span class="quantity-badge quantity-remaining <?= $result['quantite_restante_apres'] == 0 ? 'zero' : '' ?>">
                                <i class="bi bi-<?= $result['quantite_restante_apres'] == 0 ? 'check-circle' : 'exclamation-circle' ?> me-2"></i>
                                Restant: <?= number_format($result['quantite_restante_apres'], 0, ',', ' ') ?>
                            </span>
                        </div>
                        
                        <div class="distribution-table">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th><i class="bi bi-geo-alt me-2"></i>Ville</th>
                                        <th class="text-end">Besoin Avant</th>
                                        <th class="text-center">Quantité Distribuée</th>
                                        <th class="text-end">Besoin Restant</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($result['distributions'] as $dist): ?>
                                        <tr>
                                            <td>
                                                <i class="city-icon bi bi-pin-map-fill"></i>
                                                <strong><?= htmlspecialchars($dist['ville_nom']) ?></strong>
                                            </td>
                                            <td class="text-end text-muted"><?= number_format($dist['quantite_besoin_avant'], 0, ',', ' ') ?></td>
                                            <td class="text-center">
                                                <span class="quantity-distributed">
                                                    <i class="bi bi-arrow-down-circle me-1"></i>
                                                    +<?= number_format($dist['quantite'], 0, ',', ' ') ?>
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <span class="<?= $dist['quantite_besoin_apres'] == 0 ? 'text-success fw-bold' : 'text-warning' ?>">
                                                    <?= number_format($dist['quantite_besoin_apres'], 0, ',', ' ') ?>
                                                    <?php if ($dist['quantite_besoin_apres'] == 0): ?>
                                                        <i class="bi bi-check-circle-fill ms-2"></i>
                                                    <?php endif; ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="alert alert-warning-custom alert-custom">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Attention :</strong> Cette simulation ne modifie pas la base de données. 
                </div>

                <div class="text-center">
                    <form method="POST" action="<?= base_url('/dons/valider') ?>" class="d-inline" onsubmit="return confirm('⚠️ Êtes-vous sûr ?');">
                        <input type="hidden" name="type_distribution_id" value="<?= $type_distribution_selectionne ?? 1 ?>">
                        <button type="submit" class="btn btn-validate">
                            <i class="bi bi-check-circle-fill me-2"></i>Valider la distribution
                        </button>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/layouts/footer.php'; ?>
</body>
</html>