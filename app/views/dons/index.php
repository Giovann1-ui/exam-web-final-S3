<?php
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Liste de tous les Dons</title>
    <link href="<?=$base_url?>/assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?=$base_url?>/assets/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>
<div class="container mt-4">
    <h1 class="mb-4">Liste des Dons</h1>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
            <tr>
                <th>Nom Donneur</th>
                <th>Type Besoin</th>
                <th>Nom Besoin</th>
                <th>Quantité</th>
                <th>Quantité Restante</th>
                <th>Date Don</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($dons as $don) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($don['nom_donneur']); ?></td>
                    <td><span class="badge bg-primary"><?php echo htmlspecialchars($don['nom_type_besoin']); ?></span></td>
                    <td><span class="badge bg-info"><?php echo htmlspecialchars($don['nom_besoin']); ?></span></td>
                    <td><?php echo number_format($don['quantite']); ?></td>
                    <td><?php echo number_format($don['quantite_restante']); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($don['date_don'])); ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <a href="<?=$base_url?>/dons/give" class="btn btn-primary">Inserer Dons</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
