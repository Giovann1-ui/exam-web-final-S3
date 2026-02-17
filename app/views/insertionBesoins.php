<?php 
$csp_nonce = $csp_nonce ?? '';
?>
<?php include __DIR__ . '/layouts/navigation.php'; ?>

<title>Insertion de Besoins - BNGRC</title>

<style nonce="<?= $csp_nonce ?>">
    .insertion-container {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .insertion-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    
    .insertion-card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem 2rem;
        border-bottom: none;
    }
    
    .insertion-card-header h4 {
        margin: 0;
        font-weight: 600;
        font-size: 1.5rem;
        color: white;
    }
    
    .insertion-card-body {
        padding: 2rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .form-label i {
        color: var(--accent-color);
    }
    
    .form-control,
    .form-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        font-size: 0.95rem;
        transition: var(--transition);
    }
    
    .form-control:focus,
    .form-select:focus {
        outline: none;
        border-color: var(--accent-color);
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
    }
    
    .form-actions {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid var(--border-color);
    }
    
    .btn-secondary {
        background-color: transparent;
        border: 1px solid var(--border-color);
        color: var(--dark-text);
        padding: 0.75rem 2rem;
    }
    
    .btn-secondary:hover {
        background-color: var(--light-bg);
    }
    
    .btn-primary {
        background-color: var(--accent-color);
        color: white;
        padding: 0.75rem 2rem;
        font-weight: 600;
    }
    
    .btn-primary:hover {
        background-color: #2980b9;
        transform: translateY(-1px);
    }
    
    .alert {
        padding: 1rem 1.25rem;
        border-radius: 6px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .alert i {
        font-size: 1.25rem;
    }
    
    .alert-danger {
        background-color: #f8d7da;
        border-left: 4px solid var(--danger-color);
        color: #721c24;
    }
    
    .alert-success {
        background-color: #d1f2eb;
        border-left: 4px solid var(--success-color);
        color: #0c5e4c;
    }
    
    .form-help-text {
        font-size: 0.85rem;
        color: var(--muted-text);
        margin-top: 0.25rem;
        display: block;
    }
    
    .required-indicator {
        color: var(--danger-color);
        margin-left: 0.25rem;
    }
</style>

<div class="main-content">
    <div class="insertion-container">
        <div class="insertion-card">
            <div class="insertion-card-header">
                <h4>
                    <i class="bi bi-plus-circle me-2"></i>
                    Insertion de Besoins par Ville
                </h4>
            </div>
            
            <div class="insertion-card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($success)): ?>
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle-fill"></i>
                        <?= htmlspecialchars($success) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?= base_url('/besoins/add') ?>">
                    <div class="form-group">
                        <label for="ville_id" class="form-label">
                            <i class="bi bi-geo-alt"></i>
                            Ville<span class="required-indicator">*</span>
                        </label>
                        <select class="form-select" id="ville_id" name="ville_id" required>
                            <option value="">Sélectionnez une ville</option>
                            <?php foreach ($villes as $ville): ?>
                                <option value="<?= $ville['id'] ?>">
                                    <?= htmlspecialchars($ville['nom_ville']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="form-help-text">Choisissez la ville concernée par le besoin</small>
                    </div>

                    <div class="form-group">
                        <label for="besoin_id" class="form-label">
                            <i class="bi bi-box"></i>
                            Besoin<span class="required-indicator">*</span>
                        </label>
                        <select class="form-select" id="besoin_id" name="besoin_id" required>
                            <option value="">Sélectionnez un besoin</option>
                            <?php foreach ($besoins as $besoin): ?>
                                <option value="<?= $besoin['id'] ?>"
                                        data-prix="<?= $besoin['prix_unitaire'] ?>">
                                    <?= htmlspecialchars($besoin['nom_besoin']) ?>
                                    (<?= htmlspecialchars($besoin['nom_type_besoin']) ?>) -
                                    <?= number_format($besoin['prix_unitaire'], 2, ',', ' ') ?> Ar
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="form-help-text">Type de ressource nécessaire</small>
                    </div>

                    <div class="form-group">
                        <label for="quantite" class="form-label">
                            <i class="bi bi-hash"></i>
                            Quantité<span class="required-indicator">*</span>
                        </label>
                        <input type="number" class="form-control" id="quantite" name="quantite"
                               min="1" placeholder="Entrez la quantité nécessaire" required>
                        <small class="form-help-text">Nombre d'unités requises</small>
                    </div>

                    <div class="form-group">
                        <label for="date_besoin" class="form-label">
                            <i class="bi bi-calendar"></i>
                            Date du Besoin<span class="required-indicator">*</span>
                        </label>
                        <input type="date" class="form-control" id="date_besoin" name="date_besoin"
                               value="<?= date('Y-m-d') ?>" required>
                        <small class="form-help-text">Date à laquelle le besoin a été identifié</small>
                    </div>

                    <div class="form-actions">
                        <a href="<?= base_url('/dashboard') ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>
                            Retour
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>
                            Ajouter le Besoin
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/layouts/footer.php'; ?>

</body>
</html>