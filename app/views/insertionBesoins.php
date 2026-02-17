<?php include __DIR__ . '/layouts/navigation.php'; ?>

    <div class="container" style="margin-top: 155px">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h4>Insertion de Besoins par Ville</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>

                        <?php if (isset($success)): ?>
                            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                        <?php endif; ?>

                        <form method="POST" action="/besoins/add">
                            <div class="mb-3">
                                <label for="ville_id" class="form-label">Ville</label>
                                <select class="form-select" id="ville_id" name="ville_id" required>
                                    <option value="">Sélectionnez une ville</option>
                                    <?php foreach ($villes as $ville): ?>
                                        <option value="<?= $ville['id'] ?>"><?= htmlspecialchars($ville['nom_ville']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="besoin_id" class="form-label">Besoin</label>
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
                            </div>

                            <div class="mb-3">
                                <label for="quantite" class="form-label">Quantité</label>
                                <input type="number" class="form-control" id="quantite" name="quantite"
                                       min="1" required>
                            </div>

                            <div class="mb-3">
                                <label for="date_besoin" class="form-label">Date du Besoin</label>
                                <input type="date" class="form-control" id="date_besoin" name="date_besoin"
                                       value="<?= date('Y-m-d') ?>" required>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="/dashboard" class="btn btn-secondary">Retour</a>
                                <button type="submit" class="btn btn-primary">Ajouter le Besoin</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include __DIR__ . '/layouts/footer.php'; ?>