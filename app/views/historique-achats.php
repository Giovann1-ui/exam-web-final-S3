<?php 
$csp_nonce = $csp_nonce ?? '';
?>
<?php include __DIR__ . '/layouts/navigation.php'; ?>

<title>Historique des Achats - BNGRC</title>

<style nonce="<?= $csp_nonce ?>">
    .filter-section {
        background: white;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .filter-form {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        align-items: end;
    }
    
    .total-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .summary-card {
        background: white;
        padding: 15px;
        border-radius: 8px;
        border-left: 4px solid var(--accent-color);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .summary-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
    }
    
    .summary-label {
        font-size: 0.85rem;
        color: var(--muted-text);
        margin-top: 5px;
    }
</style>

<div class="main-content">
    <div class="section-header">
        <h4><i class="bi bi-receipt me-2"></i>Historique des Achats</h4>
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
            Retour au tableau de bord
        </a>
    </div>

    <!-- Filtres -->
    <div class="filter-section">
        <h6 class="mb-3"><i class="bi bi-funnel me-2"></i>Filtres</h6>
        <form method="GET" action="<?= base_url('/historique-achats') ?>" class="filter-form">
            <div>
                <label for="ville_filter" class="form-label">Ville</label>
                <select name="ville_id" id="ville_filter" class="form-select">
                    <option value="">Toutes les villes</option>
                    <?php foreach ($villes as $ville): ?>
                        <option value="<?= $ville['id'] ?>" <?= isset($_GET['ville_id']) && $_GET['ville_id'] == $ville['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($ville['nom_ville']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div>
                <label for="date_debut" class="form-label">Date début</label>
                <input type="date" name="date_debut" id="date_debut" class="form-control" 
                       value="<?= $_GET['date_debut'] ?? '' ?>">
            </div>
            
            <div>
                <label for="date_fin" class="form-label">Date fin</label>
                <input type="date" name="date_fin" id="date_fin" class="form-control" 
                       value="<?= $_GET['date_fin'] ?? '' ?>">
            </div>
            
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i>
                    Filtrer
                </button>
                <a href="<?= base_url('/historique-achats') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle"></i>
                    Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Résumé -->
    <?php if (!empty($achats)): ?>
        <div class="total-summary">
            <div class="summary-card">
                <div class="summary-value"><?= count($achats) ?></div>
                <div class="summary-label">Achats effectués</div>
            </div>
            <div class="summary-card">
                <div class="summary-value"><?= $total_quantite ?></div>
                <div class="summary-label">Quantité totale</div>
            </div>
            <div class="summary-card" style="border-left-color: var(--warning-color);">
                <div class="summary-value"><?= $total_montant ?> Ar</div>
                <div class="summary-label">Montant total</div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Table des achats -->
    <div class="card">
        <div class="card-body">
            <?php if (empty($achats)): ?>
                <div style="text-align: center; padding: 40px; color: var(--muted-text);">
                    <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                    <p style="margin-top: 15px;">Aucun achat trouvé pour les critères sélectionnés</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date d'achat</th>
                                <th>Ville</th>
                                <th>Article acheté</th>
                                <th>Type</th>
                                <th style="text-align: right;">Quantité</th>
                                <th style="text-align: right;">Prix unitaire</th>
                                <th style="text-align: right;">Frais (%)</th>
                                <th style="text-align: right;">Total payé</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($achats as $achat): ?>
                                <tr>
                                    <td style="color: var(--muted-text); font-size: 0.9rem;">
                                        <?= date('d/m/Y H:i', strtotime($achat['date_achat'])) ?>
                                    </td>
                                    <td class="fw-semibold">
                                        <i class="bi bi-geo-alt me-1"></i>
                                        <?= htmlspecialchars($achat['nom_ville']) ?>
                                    </td>
                                    <td><?= htmlspecialchars($achat['nom_besoin']) ?></td>
                                    <td>
                                        <span class="badge badge-<?= $achat['nom_type_besoin'] ?>">
                                            <?=$achat['nom_type_besoin'] ?>
                                        </span>
                                    </td>
                                    <td style="text-align: right; font-weight: 600;">
                                        <?= number_format($achat['quantite']) ?>
                                    </td>
                                    <td style="text-align: right;">
                                        <?= $achat['prix_unitaire'] ?> Ar
                                    </td>
                                    <td style="text-align: right; color: var(--warning-color);">
                                        <?= $achat['frais'] ?>%
                                        <br>
                                        <!-- <small style="color: var(--muted-text);">
                                            <?= $achat['montant_frais'] ?> Ar
                                        </small> -->
                                    </td>
                                    <td style="text-align: right; font-weight: 700; color: var(--primary-color);">
                                        <?= number_format($achat['total_paye'], 2) ?> Ar
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr style="background-color: var(--light-bg); font-weight: 700;">
                                <td colspan="4" style="text-align: right;">TOTAL</td>
                                <td style="text-align: right;"><?= $total_quantite ?></td>
                                <td colspan="2"></td>
                                <td style="text-align: right; color: var(--primary-color);">
                                    <?= $total_montant ?> Ar
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php include __DIR__ . '/layouts/footer.php'; ?>

</body>
</html>