<?php
$villes_satisfait = $villes_satisfait ?? [];
$besoins_attribues = $besoins_attribues ?? [];
$csp_nonce = $csp_nonce ?? '';
?>
<?php include __DIR__ . '/layouts/navigation.php'; ?>

<title>Tableau de Bord - BNGRC</title>
 <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/bootstrap-icons/font/bootstrap-icons.css">
<style nonce="<?= $csp_nonce ?>">
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--border-color);
    }
    
    .section-header h4 {
        margin: 0;
        color: var(--primary-color);
        font-weight: 600;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .ville-group {
        margin-bottom: 30px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        overflow: hidden;
    }
    
    .ville-header {
        background-color: var(--light-bg);
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid var(--border-color);
    }
    
    .ville-header h5 {
        margin: 0;
        color: var(--primary-color);
        font-weight: 600;
    }
    
    .badge-pill {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    
    .badge-danger {
        background-color: #f8d7da;
        color: #721c24;
    }
    
    .badge-success {
        background-color: #d1f2eb;
        color: #0c5e4c;
    }
    
    .list-group-item {
        border: none;
        border-bottom: 1px solid var(--border-color);
        padding: 15px;
    }
    
    .list-group-item:last-child {
        border-bottom: none;
    }
</style>

<div class="main-content">
    <div class="section-header">
        <h4><i class="bi bi-speedometer2 me-2"></i>Tableau de Bord</h4>
        <div>
            <a href="/dons" class="btn btn-outline-secondary">
                <i class="bi bi-list-ul"></i>
                Gérer les dons
            </a>
            <a href="/dons/give" class="btn btn-success">
                <i class="bi bi-plus-circle"></i>
                Ajouter un don
            </a>
        </div>
    </div>
    
    <!-- Statistiques -->
    <div class="stats-grid">
        <div class="card stat-card" style="border-left-color: var(--accent-color);">
            <div class="stat-value"><?= $stats['total_villes'] ?></div>
            <div class="stat-label">Villes</div>
        </div>
        <div class="card stat-card" style="border-left-color: var(--warning-color);">
            <div class="stat-value"><?= number_format($stats['total_besoins']) ?></div>
            <div class="stat-label">Besoins Totaux</div>
        </div>
        <div class="card stat-card" style="border-left-color: var(--danger-color);">
            <div class="stat-value"><?= number_format($stats['total_besoins_restants']) ?></div>
            <div class="stat-label">Besoins Restants</div>
        </div>
        <div class="card stat-card" style="border-left-color: var(--success-color);">
            <div class="stat-value"><?= $stats['total_dons'] ?></div>
            <div class="stat-label">Dons Reçus</div>
        </div>
        <div class="card stat-card" style="border-left-color: var(--accent-color);">
            <div class="stat-value"><?= $stats['pourcentage_satisfaction'] ?>%</div>
            <div class="stat-label">Satisfaction Globale</div>
            <div class="progress" style="margin-top: 10px;">
                <div class="progress-bar bg-info" style="width: <?= $stats['pourcentage_satisfaction'] ?>%"></div>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
        <!-- Besoins en cours -->
        <div>
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-exclamation-triangle me-2"></i>Besoins en cours par localité
                </div>
                <div class="card-body">
                    <?php if (empty($villes)): ?>
                        <div style="text-align: center; padding: 40px; color: var(--muted-text);">
                            <i class="bi bi-check-circle" style="font-size: 3rem; color: var(--success-color);"></i>
                            <p style="margin-top: 15px;">Tous les besoins actuels sont satisfaits !</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($villes as $ville): ?>
                            <div class="ville-group">
                                <div class="ville-header">
                                    <h5><?= htmlspecialchars($ville['nom_ville']) ?></h5>
                                    <span class="badge-pill badge-danger"><?= count($ville['besoins']) ?> en attente</span>
                                </div>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th style="width: 100px;">Date</th>
                                            <th>Désignation</th>
                                            <th style="width: 120px;">Type</th>
                                            <th style="width: 100px; text-align: right;">Reste</th>
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
                                            <td style="color: var(--muted-text); font-size: 0.9rem;">
                                                <?= date('d/m/Y', strtotime($besoin['date_besoin'])) ?>
                                            </td>
                                            <td class="fw-semibold"><?= htmlspecialchars($besoin['nom_besoin']) ?></td>
                                            <td><span class="badge <?= $typeBadgeClass ?>"><?= htmlspecialchars($besoin['nom_type_besoin']) ?></span></td>
                                            <td style="text-align: right; color: var(--danger-color); font-weight: 600;">
                                                <?= number_format($besoin['quantite_restante']) ?>
                                            </td>
                                            <td>
                                                <div style="display: flex; align-items: center; gap: 10px;">
                                                    <div class="progress" style="flex: 1;">
                                                        <div class="progress-bar bg-warning" style="width: <?= $progression ?>%"></div>
                                                    </div>
                                                    <span style="font-size: 0.85rem; color: var(--muted-text);"><?= $progression ?>%</span>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
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

        <!-- Sidebar droit -->
        <div>
            <!-- Villes satisfaites -->
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-check-circle me-2"></i>Villes Satisfaites
                </div>
                <div class="card-body">
                    <?php if (empty($villes_satisfait)): ?>
                        <p class="text-muted" style="text-align: center;">Aucune ville entièrement satisfaite</p>
                    <?php else: ?>
                        <?php foreach ($villes_satisfait as $v_sat): ?>
                            <div style="display: flex; align-items: center; padding: 12px; background-color: rgba(39, 174, 96, 0.05); border-radius: 6px; margin-bottom: 10px;">
                                <i class="bi bi-check-circle-fill" style="color: var(--success-color); font-size: 1.5rem; margin-right: 12px;"></i>
                                <div>
                                    <div class="fw-bold"><?= htmlspecialchars($v_sat['nom_ville']) ?></div>
                                    <small class="text-success">Tous besoins comblés</small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Dernières distributions -->
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-clock-history me-2"></i>Dernières Distributions
                </div>
                <div class="card-body">
                    <?php if (empty($dons)): ?>
                        <p class="text-muted" style="text-align: center;">Aucune distribution</p>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($dons as $villeId => $donsList): ?>
                                <?php foreach ($donsList as $don): ?>
                                    <div class="list-group-item">
                                        <div style="display: flex; justify-content: between; align-items: start;">
                                            <h6 class="fw-bold" style="color: var(--accent-color); margin-bottom: 5px;">
                                                <?= htmlspecialchars($don['nom_besoin']) ?>
                                            </h6>
                                            <small class="text-muted">
                                                <?= date('d/m/Y', strtotime($don['date_distribution'])) ?>
                                            </small>
                                        </div>
                                        <p class="text-muted" style="font-size: 0.9rem; margin: 5px 0;">
                                            <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($don['nom_ville']) ?><br>
                                            <i class="bi bi-box"></i> <?= number_format($don['quantite_distribuee']) ?> unités
                                        </p>
                                        <?php if (!empty($don['nom_donneur'])): ?>
                                            <small class="text-muted">
                                                <i class="bi bi-person"></i> <?= htmlspecialchars($don['nom_donneur']) ?>
                                            </small>
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
</div>

</body>
</html>