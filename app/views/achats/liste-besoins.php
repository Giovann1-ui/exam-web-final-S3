<?php 
$csp_nonce = $csp_nonce ?? '';
$argent_disponible = $argent_disponible ?? 0;
?>
<?php include __DIR__ . '/../layouts/navigation.php'; ?>

<title>Besoins à Acheter - BNGRC</title>

<style nonce="<?= $csp_nonce ?>">
    .info-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .info-box h5 {
        margin: 0;
        font-size: 1.1rem;
    }
    
    .info-box .montant {
        font-size: 2rem;
        font-weight: 700;
    }
    
    .alert-instruction {
        background-color: #e3f2fd;
        border-left: 4px solid #2196f3;
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 20px;
    }
    
    .besoin-clickable {
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .besoin-clickable:hover {
        background-color: #f8f9fa;
        transform: translateX(5px);
    }
</style>

<div class="main-content">
    <div class="section-header">
        <h4><i class="bi bi-cart me-2"></i>Besoins Restants à Acheter</h4>
        <a href="<?= $base_url ?>/dashboard" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
            Retour au Dashboard
        </a>
    </div>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?= htmlspecialchars($_GET['error']) ?>
        </div>
    <?php endif; ?>

    <div class="info-box">
        <div>
            <h5><i class="bi bi-cash-stack me-2"></i>Argent Disponible pour Achats</h5>
            <p class="mb-0" style="opacity: 0.9;">Provenant des dons en argent</p>
        </div>
        <div class="montant">
            <?= number_format($argent_disponible, 2) ?> Ar
        </div>
    </div>

    <div class="alert-instruction">
        <strong><i class="bi bi-info-circle me-2"></i>Instructions :</strong>
        <p style="margin: 10px 0 0 0;">
            Cliquez sur un besoin pour effectuer un achat avec les dons en argent. 
            L'argent sera utilisé par ordre d'arrivée des dons.
        </p>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if (empty($besoins_groupes)): ?>
                <div style="text-align: center; padding: 40px; color: var(--muted-text);">
                    <i class="bi bi-check-circle" style="font-size: 3rem; color: var(--success-color);"></i>
                    <p style="margin-top: 15px;">Tous les besoins actuels peuvent être comblés avec les dons disponibles !</p>
                </div>
            <?php else: ?>
                <?php foreach ($besoins_groupes as $ville): ?>
                    <div class="ville-group">
                        <div class="ville-header">
                            <h5><?= htmlspecialchars($ville['nom_ville']) ?></h5>
                            <span class="badge-pill badge-warning"><?= count($ville['besoins']) ?> à acheter</span>
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width: 100px;">Date</th>
                                    <th>Désignation</th>
                                    <th style="width: 120px;">Type</th>
                                    <th style="width: 120px; text-align: right;">Prix Unit.</th>
                                    <th style="width: 100px; text-align: right;">Quantité</th>
                                    <th style="width: 150px; text-align: right;">Montant Estimé</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ville['besoins'] as $besoin): 
                                    $montant_estime = $besoin['quantite_restante'] * $besoin['prix_unitaire'];
                                    $typeBadgeClass = 'badge-' . strtolower($besoin['nom_type_besoin']);
                                ?>
                                    <tr class="besoin-clickable" onclick="window.location.href='<?= $base_url ?>/achats/form/<?= $besoin['besoin_ville_id'] ?>'">
                                        <a href="">
                                            <td style="color: var(--muted-text); font-size: 0.9rem;">
                                            <?= date('d/m/Y', strtotime($besoin['date_besoin'])) ?>
                                        </td>
                                        <td class="fw-semibold">
                                            <i class="bi bi-box me-1"></i>
                                            <?= htmlspecialchars($besoin['nom_besoin']) ?>
                                        </td>
                                        <td>
                                            <span class="badge <?= $typeBadgeClass ?>">
                                                <?= htmlspecialchars($besoin['nom_type_besoin']) ?>
                                            </span>
                                        </td>
                                        <td style="text-align: right;">
                                            <?= number_format($besoin['prix_unitaire'], 2) ?> Ar
                                        </td>
                                        <td style="text-align: right; color: var(--danger-color); font-weight: 600;">
                                            <?= number_format($besoin['quantite_restante']) ?>
                                        </td>
                                        <td style="text-align: right; font-weight: 600; color: var(--warning-color);">
                                            <?= number_format($montant_estime, 2) ?> Ar
                                        </td>
                                        </a>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>