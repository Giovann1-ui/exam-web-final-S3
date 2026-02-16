<?php
$villes_satisfait = $villes_satisfait ?? [];
$besoins_attribues = $besoins_attribues ?? [];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DASHBOARD BNGRC</title>
    <link href="<?=$base_url?>/assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?=$base_url?>/assets/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        :root {
            --bngrc-dark: #2c3e50;
            --bngrc-light: #f8f9fa;
            --bngrc-success: #198754;
        }

        body {
            background-color: #f4f7f6;
            color: #333;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }

        .navbar-brand {
            font-weight: bold;
            letter-spacing: 1px;
        }

        .stat-card {
            border: none;
            border-left: 4px solid #0d6efd;
            transition: transform 0.2s;
        }

        .stat-card:hover { transform: translateY(-3px); }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--bngrc-dark);
        }

        .stat-label {
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
            color: #6c757d;
        }

        .section-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .table thead th {
            background-color: var(--bngrc-light);
            text-transform: uppercase;
            font-size: 0.8rem;
            color: #495057;
        }

        .progress { height: 12px; border-radius: 10px; }

        .badge-nature { background-color: #d1e7dd; color: #0f5132; }
        .badge-materiaux { background-color: #fff3cd; color: #664d03; }
        .badge-argent { background-color: #cff4fc; color: #055160; }

        .ville-header {
            border-bottom: 2px solid #dee2e6;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
        }

        .card-satisfied {
            border: 1px solid #d1e7dd;
            background-color: #fafffd;
            border-radius: 10px;
        }
        .icon-satisfied {
            color: var(--bngrc-success);
            font-size: 1.5rem;
        }

        /* Style pour les besoins attribués */
        .table-attribues {
            background-color: #f8fff9;
        }
        .table-attribues tbody tr {
            border-left: 3px solid var(--bngrc-success);
        }
    </style>
</head>
<body>

<nav class="navbar navbar-dark bg-dark mb-4 shadow-sm">
    <div class="container-fluid px-4">
        <span class="navbar-brand mb-0 h1">
            <i class="bi bi-shield-shaded me-2"></i> BNGRC DASHBOARD
        </span>
        <div class="d-flex">
            <!-- <a href="<?=$base_url?>/villes" class="btn btn-outline-light btn-sm me-2">Villes</a> -->
            <a href="<?=$base_url?>/dons" class="btn btn-outline-light btn-sm me-2">Gérer les dons</a>
            <a href="<?=$base_url?>/dons/give" class="btn btn-primary btn-sm">Ajouter un don</a>
        </div>
    </div>
</nav>

<div class="container-fluid px-4">
    
    <!-- Statistiques -->
    <div class="row g-3 mb-4">
        <div class="col-md-2">
            <div class="card stat-card shadow-sm p-3">
                <div class="stat-value"><?= $stats['total_villes'] ?></div>
                <div class="stat-label">Villes</div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card stat-card shadow-sm p-3" style="border-left-color: #6610f2;">
                <div class="stat-value"><?= number_format($stats['total_besoins']) ?></div>
                <div class="stat-label">Besoins Totaux</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card shadow-sm p-3" style="border-left-color: #fd7e14;">
                <div class="stat-value"><?= number_format($stats['total_besoins_restants']) ?></div>
                <div class="stat-label">Besoins Restants</div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card stat-card shadow-sm p-3" style="border-left-color: #198754;">
                <div class="stat-value"><?= $stats['total_dons'] ?></div>
                <div class="stat-label">Dons Reçus</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card shadow-sm p-3" style="border-left-color: #0dcaf0;">
                <div class="stat-value"><?= $stats['pourcentage_satisfaction'] ?>%</div>
                <div class="stat-label">Taux de Satisfaction Global</div>
                <div class="progress mt-2">
                    <div class="progress-bar bg-info" style="width: <?= $stats['pourcentage_satisfaction'] ?>%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Besoins en cours -->
            <div class="section-container">
                <h4 class="mb-4 text-secondary"><i class="bi bi-exclamation-triangle me-2"></i>Besoins en cours par localité</h4>
                
                <?php if (empty($villes)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-patch-check fs-1 text-success"></i>
                        <p class="mt-2">Tous les besoins actuels sont satisfaits !</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($villes as $ville): ?>
                        <div class="ville-group mb-5">
                            <div class="ville-header d-flex justify-content-between align-items-center">
                                <h5 class="fw-bold text-dark m-0"><?= htmlspecialchars($ville['nom_ville']) ?></h5>
                                <span class="badge bg-danger rounded-pill"><?= count($ville['besoins']) ?> en attente</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Désignation</th>
                                            <th>Type</th>
                                            <th class="text-end">Reste</th>
                                            <th style="width: 180px;">Progression</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($ville['besoins'] as $besoin): 
                                            $progression = $besoin['quantite'] > 0 
                                                ? round((($besoin['quantite'] - $besoin['quantite_restante']) / $besoin['quantite']) * 100, 1)
                                                : 0;
                                            $typeBadgeClass = 'badge-' . strtolower($besoin['nom_type_besoin']);
                                        ?>
                                        <tr>
                                            <td class="small text-muted"><?= date('d/m/Y', strtotime($besoin['date_besoin'])) ?></td>
                                            <td><span class="fw-semibold"><?= htmlspecialchars($besoin['nom_besoin']) ?></span></td>
                                            <td><span class="badge <?= $typeBadgeClass ?>"><?= htmlspecialchars($besoin['nom_type_besoin']) ?></span></td>
                                            <td class="text-end text-danger fw-bold"><?= number_format($besoin['quantite_restante']) ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress flex-grow-1 me-2">
                                                        <div class="progress-bar bg-warning" style="width: <?= $progression ?>%"></div>
                                                    </div>
                                                    <small style="font-size: 0.7rem;"><?= $progression ?>%</small>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Besoins attribués -->
            <div class="section-container border-top border-success border-4">
                <h4 class="mb-4 text-success"><i class="bi bi-check-circle me-2"></i>Besoins attribués par ville</h4>
                
                <?php if (empty($besoins_attribues)): ?>
                    <p class="text-muted small">Aucun besoin n'a encore été attribué.</p>
                <?php else: ?>
                    <?php foreach ($besoins_attribues as $ville): ?>
                        <div class="ville-group mb-4">
                            <div class="ville-header d-flex justify-content-between align-items-center">
                                <h5 class="fw-bold text-success m-0"><?= htmlspecialchars($ville['nom_ville']) ?></h5>
                                <span class="badge bg-success rounded-pill"><?= count($ville['besoins']) ?> attribués</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-attribues table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Désignation</th>
                                            <th>Type</th>
                                            <th class="text-end">Qté Initiale</th>
                                            <th class="text-end">Qté Attribuée</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($ville['besoins'] as $besoin): 
                                            $typeBadgeClass = 'badge-' . strtolower($besoin['nom_type_besoin']);
                                        ?>
                                        <tr>
                                            <td class="small text-muted"><?= date('d/m/Y', strtotime($besoin['date_besoin'])) ?></td>
                                            <td><span class="fw-semibold"><?= htmlspecialchars($besoin['nom_besoin']) ?></span></td>
                                            <td><span class="badge <?= $typeBadgeClass ?>"><?= htmlspecialchars($besoin['nom_type_besoin']) ?></span></td>
                                            <td class="text-end text-muted"><?= number_format($besoin['quantite_initiale']) ?></td>
                                            <td class="text-end">
                                                <span class="badge bg-success fs-6"><?= number_format($besoin['quantite_attribuee']) ?></span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Villes satisfaites -->
            <div class="section-container border-top border-primary border-4">
                <h4 class="mb-4 text-primary"><i class="bi bi-check-all me-2"></i>Villes entièrement satisfaites</h4>
                <?php if (empty($villes_satisfait)): ?>
                    <p class="text-muted small">Aucune ville n'a encore atteint 100% de satisfaction.</p>
                <?php else: ?>
                    <div class="row row-cols-1 row-cols-md-2 g-3">
                        <?php foreach ($villes_satisfait as $v_sat): ?>
                        <div class="col">
                            <div class="card card-satisfied p-3 d-flex flex-row align-items-center shadow-sm">
                                <div class="me-3">
                                    <i class="bi bi-check-circle-fill icon-satisfied"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold"><?= htmlspecialchars($v_sat['nom_ville']) ?></h6>
                                    <small class="text-success">Tous besoins comblés</small>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Dernières distributions -->
        <div class="col-lg-4">
            <div class="section-container">
                <h4 class="mb-4 text-secondary"><i class="bi bi-clock-history me-2"></i>Dernières distributions</h4>
                
                <?php if (empty($dons)): ?>
                    <div class="alert alert-light text-center border">Aucune distribution effectuée</div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($dons as $villeId => $donsList): ?>
                            <?php foreach ($donsList as $don): ?>
                                <div class="list-group-item px-0 py-3 border-0 border-bottom">
                                    <div class="d-flex w-100 justify-content-between align-items-start">
                                        <h6 class="mb-1 fw-bold text-primary"><?= htmlspecialchars($don['nom_besoin']) ?></h6>
                                        <small class="text-muted" style="font-size: 0.75rem;"><?= date('d/m/Y', strtotime($don['date_distribution'])) ?></small>
                                    </div>
                                    <p class="mb-1 text-muted small">
                                        Versé à : <strong><?= htmlspecialchars($don['nom_ville']) ?></strong><br>
                                        Volume : <span class="badge bg-success text-white"><?= number_format($don['quantite_distribuee']) ?> unités</span>
                                    </p>
                                    <?php if (!empty($don['nom_donneur'])): ?>
                                        <div class="mt-1 small text-secondary">
                                            <i class="bi bi-person-heart me-1"></i><?= htmlspecialchars($don['nom_donneur']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>