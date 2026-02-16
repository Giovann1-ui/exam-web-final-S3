<?php 
$csp_nonce = $csp_nonce ?? '';
?>
<?php include __DIR__ . '/../layouts/navigation.php'; ?>

<title>Liste des Dons - BNGRC</title>

<div class="main-content">
    <div class="section-header">
        <h4><i class="bi bi-list-ul me-2"></i>Liste des Dons</h4>
        <a href="/dons/give" class="btn btn-success">
            <i class="bi bi-plus-circle"></i>
            Ajouter un Don
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nom Donneur</th>
                        <th>Type Besoin</th>
                        <th>Nom Besoin</th>
                        <th style="text-align: right;">Quantité</th>
                        <th style="text-align: right;">Restante en stock</th>
                        <th>Date Don</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dons as $don): ?>
                        <tr>
                            <td class="fw-semibold"><?= htmlspecialchars($don['nom_donneur']) ?></td>
                            <td>
                                <span class="badge badge-<?= strtolower($don['nom_type_besoin']) ?>">
                                    <?= htmlspecialchars($don['nom_type_besoin']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($don['nom_besoin']) ?></td>
                            <td style="text-align: right;"><?= number_format($don['quantite']) ?></td>
                            <td style="text-align: right;"><?= number_format($don['quantite_restante']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($don['date_don'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>