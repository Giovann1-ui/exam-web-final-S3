<?php 
$csp_nonce = $csp_nonce ?? '';
$besoin = $besoin ?? [];
$argent_disponible = $argent_disponible ?? 0;
$frais_pourcentage = $frais_pourcentage ?? 0;
$montant_base = $montant_base ?? 0;
$montant_frais = $montant_frais ?? 0;
$montant_total = $montant_total ?? 0;
?>
<?php include __DIR__ . '/../layouts/navigation.php'; ?>

<title>Saisie Achat - BNGRC</title>

<style nonce="<?= $csp_nonce ?>">
    .form-container {
        max-width: 900px;
        margin: 0 auto;
    }
    
    .info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    
    .info-card h5 {
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid rgba(255,255,255,0.3);
    }
    
    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
    }
    
    .montant-card {
        background: white;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .montant-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #e0e0e0;
    }
    
    .montant-row:last-child {
        border-bottom: none;
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--primary-color);
        padding-top: 15px;
        margin-top: 10px;
        border-top: 2px solid var(--primary-color);
    }
    
    .alert-funds {
        background-color: #fff3cd;
        border-left: 4px solid var(--warning-color);
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 20px;
    }
</style>

<div class="main-content">
    <div class="form-container">
        <div class="section-header">
            <h4><i class="bi bi-cart-plus me-2"></i>Effectuer un Achat</h4>
            <a href="<?= $base_url ?>/achats/besoins" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
                Retour
            </a>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php endif; ?>

        <!-- Informations du besoin -->
        <div class="info-card">
            <h5><i class="bi bi-info-circle me-2"></i>Informations du Besoin</h5>
            <div class="info-row">
                <span><i class="bi bi-geo-alt me-2"></i>Ville :</span>
                <strong><?= htmlspecialchars($besoin['nom_ville']) ?></strong>
            </div>
            <div class="info-row">
                <span><i class="bi bi-box me-2"></i>Article :</span>
                <strong><?= htmlspecialchars($besoin['nom_besoin']) ?></strong>
            </div>
            <div class="info-row">
                <span><i class="bi bi-tag me-2"></i>Type :</span>
                <strong><?= htmlspecialchars($besoin['nom_type_besoin']) ?></strong>
            </div>
            <div class="info-row">
                <span><i class="bi bi-cash me-2"></i>Prix Unitaire :</span>
                <strong><?= number_format($besoin['prix_unitaire'], 2) ?> Ar</strong>
            </div>
            <div class="info-row">
                <span><i class="bi bi-calculator me-2"></i>Quantité Restante :</span>
                <strong><?= number_format($besoin['quantite_restante']) ?> unités</strong>
            </div>
        </div>

        <!-- Calcul des montants -->
        <div class="montant-card">
            <h5 style="color: var(--primary-color); margin-bottom: 15px;">
                <i class="bi bi-calculator me-2"></i>Calcul du Montant
            </h5>
            <div class="montant-row">
                <span>Montant de base :</span>
                <span><?= number_format($montant_base, 2) ?> Ar</span>
            </div>
            <div class="montant-row">
                <span>Frais (<?= $frais_pourcentage ?>%) :</span>
                <span><?= number_format($montant_frais, 2) ?> Ar</span>
            </div>
            <div class="montant-row">
                <span>MONTANT TOTAL À PAYER :</span>
                <span><?= number_format($montant_total, 2) ?> Ar</span>
            </div>
        </div>

        <!-- Argent disponible -->
        <div class="alert-funds">
            <strong><i class="bi bi-wallet2 me-2"></i>Argent disponible :</strong>
            <span style="float: right; font-size: 1.2rem; font-weight: 700;">
                <?= number_format($argent_disponible, 2) ?> Ar
            </span>
        </div>

        <?php if ($montant_total > $argent_disponible): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Fonds insuffisants !</strong> Vous avez besoin de <?= number_format($montant_total - $argent_disponible, 2) ?> Ar supplémentaires.
            </div>
        <?php endif; ?>

        <!-- Formulaire -->
        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?= $base_url ?>/achats/add">
                    <input type="hidden" name="besoin_ville_id" value="<?= $besoin['besoin_ville_id'] ?>">

                    <div class="form-group">
                        <label for="quantite" class="form-label">
                            <i class="bi bi-123 me-1"></i>Quantité à Acheter
                        </label>
                        <div class="input-icon-wrapper">
                            <i class="bi bi-hash"></i>
                            <input type="number" class="form-control" id="quantite" name="quantite" 
                                   min="1" max="<?= $besoin['quantite_restante'] ?>"
                                   value="<?= $besoin['quantite_restante'] ?>" required>
                        </div>
                        <small class="text-muted">
                            Quantité pré-remplie avec le besoin restant (modifiable)
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="date_achat" class="form-label">
                            <i class="bi bi-calendar me-1"></i>Date de l'Achat
                        </label>
                        <div class="input-icon-wrapper">
                            <i class="bi bi-calendar"></i>
                            <input type="datetime-local" class="form-control" id="date_achat" name="date_achat" 
                                   value="<?= date('Y-m-d\TH:i') ?>" required>
                        </div>
                    </div>

                    <div style="display: grid; gap: 15px; margin-top: 30px;">
                        <button type="submit" class="btn btn-success btn-submit" 
                                <?= $montant_total > $argent_disponible ? 'disabled' : '' ?>>
                            <i class="bi bi-check-circle"></i>
                            Confirmer l'Achat
                        </button>
                        <a href="<?= $base_url ?>/achats/besoins" class="btn btn-outline-secondary" style="text-align: center;">
                            <i class="bi bi-arrow-left"></i>
                            Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script nonce="<?= $csp_nonce ?>">
    // Calculer dynamiquement le montant en fonction de la quantité
    document.getElementById('quantite').addEventListener('input', function() {
        const quantite = parseFloat(this.value) || 0;
        const prixUnitaire = <?= $besoin['prix_unitaire'] ?>;
        const fraisPourcentage = <?= $frais_pourcentage ?>;
        
        const montantBase = quantite * prixUnitaire;
        const montantFrais = montantBase * (fraisPourcentage / 100);
        const montantTotal = montantBase + montantFrais;
        
        console.log('Nouveau montant total:', montantTotal.toFixed(2), 'Ar');
    });
</script>

</body>
</html>