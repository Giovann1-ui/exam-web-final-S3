<?php
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Liste de tous les Dons</title>
</head>
<body>
<h1>Liste des Dons</h1>
<table border="1">
    <thead>
    <tr>
        <th>Nom Donneur</th>
        <th>Quantité</th>
        <th>Quantité Restante</th>
        <th>Date Don</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($dons as $don) { ?>
        <tr>
            <td><?php echo ($don['nom_donneur']); ?></td>
            <td><?php echo ($don['quantite']); ?></td>
            <td><?php echo ($don['quantite_restante']); ?></td>
            <td><?php echo ($don['date_don']); ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<a href="/dons/give">Inserer Dons</a>
</body>
</html>
