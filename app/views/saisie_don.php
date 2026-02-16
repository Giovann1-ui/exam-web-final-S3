<?php 
$csp_nonce = $csp_nonce ?? '';
?>
<?php include __DIR__ . '/layouts/navigation.php'; ?>

<title>Enregistrement d'un Don - BNGRC</title>
<style nonce="<?= $csp_nonce ?>">
    .form-container {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .form-section {
        background: white;
        border-radius: 8px;
        padding: 30px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .form-section-header {
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--border-color);
    }
    
    .form-section-header h2 {
        color: var(--primary-color);
        margin-bottom: 5px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .alert-rule {
        background-color: #fff3cd;
        border-left: 4px solid var(--warning-color);
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 25px;
    }
    
    .input-icon-wrapper {
        position: relative;
    }
    
    .input-icon-wrapper i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--muted-text);
    }
    
    .input-icon-wrapper .form-control,
    .input-icon-wrapper .form-select {
        padding-left: 45px;
    }
    
    .btn-submit {
        width: 100%;
        padding: 15px;
        font-size: 1.1rem;
        font-weight: 600;
    }
</style>

<div class="main-content">
    <div class="form-container">
        <div class="form-section">
            <div class="form-section-header">
                <h2><i class="bi bi-gift me-2"></i>Enregistrement d'un Don</h2>
                <p class="text-muted">Merci pour votre générosité</p>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?= htmlspecialchars($_GET['error']) ?>
                </div>
            <?php endif; ?>

            <div class="alert-rule">
                <strong><i class="bi bi-info-circle me-2"></i>Règle de gestion :</strong>
                <p style="margin: 10px 0 0 0;">
                    Le dispatch se fera automatiquement par ordre de date et de saisie 
                    vers les villes ayant des besoins correspondants.
                </p>
            </div>

            <form method="POST" action="/dons/add">
                <div class="form-group">
                    <label for="donateur" class="form-label">
                        <i class="bi bi-person me-1"></i>Donateur / Libellé
                    </label>
                    <div class="input-icon-wrapper">
                        <i class="bi bi-person"></i>
                        <input type="text" class="form-control" id="donateur" name="donateur" 
                               placeholder="Ex: Association Humanitaire X" required>
                    </div>
                    <small class="text-muted">Nom de l'organisation ou de la personne qui fait le don</small>
                </div>

                <div class="form-group">
                    <label for="besoin_id" class="form-label">
                        <i class="bi bi-box me-1"></i>Type de Don
                    </label>
                    <div class="input-icon-wrapper">
                        <i class="bi bi-box"></i>
                        <select class="form-select" id="besoin_id" name="besoin_id" required>
                            <option value="">-- Sélectionnez un type de don --</option>
                            <?php if (!empty($besoins)): ?>
                                <?php foreach ($besoins as $besoin): ?>
                                    <option value="<?= $besoin['id'] ?>">
                                        <?= htmlspecialchars($besoin['nom_besoin']) ?> 
                                        (<?= htmlspecialchars($besoin['nom_type_besoin']) ?>) 
                                        - <?= number_format($besoin['prix_unitaire'], 2) ?> Ar
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <small class="text-muted">Choisissez le type de ressource que vous souhaitez donner</small>
                </div>

                <div class="form-group">
                    <label for="quantite_don" class="form-label">
                        <i class="bi bi-123 me-1"></i>Quantité Offerte
                    </label>
                    <div class="input-icon-wrapper">
                        <i class="bi bi-hash"></i>
                        <input type="number" class="form-control" id="quantite_don" name="quantite_don" 
                               min="1" placeholder="Entrez la quantité" required>
                    </div>
                    <small class="text-muted">Nombre d'unités que vous souhaitez donner</small>
                </div>

                <div class="form-group">
                    <label for="date_saisie" class="form-label">
                        <i class="bi bi-calendar me-1"></i>Date de Réception
                    </label>
                    <div class="input-icon-wrapper">
                        <i class="bi bi-calendar"></i>
                        <input type="datetime-local" class="form-control" id="date_saisie" name="date_saisie" 
                               value="<?= date('Y-m-d\TH:i') ?>" required>
                    </div>
                    <small class="text-muted">Date et heure de réception du don</small>
                </div>

                <div style="display: grid; gap: 15px; margin-top: 30px;">
                    <button type="submit" class="btn btn-success btn-submit">
                        <i class="bi bi-check-circle"></i>
                        Enregistrer et Simuler le Dispatch
                    </button>
                    <a href="/dons" class="btn btn-outline-secondary" style="text-align: center;">
                        <i class="bi bi-arrow-left"></i>
                        Retour à la liste
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>