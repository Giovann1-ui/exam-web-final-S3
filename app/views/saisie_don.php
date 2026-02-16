<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saisie de Dons</title>
</head>
<body>
    
    <h2>Enregistrement d'un Don</h2>

    <div style="background: #fdf2e9; border-left: 5px solid #e67e22; padding: 10px; margin-bottom: 20px;">
        <strong>Règle de gestion :</strong> Le dispatch se fera par ordre de date et de saisie.
    </div>

    <form style="display: flex; flex-direction: column; width: 300px; gap: 10px;">
        <label>Donateur / Libellé :</label>
        <input type="text" name="donateur" placeholder="Ex: Association X">

        <label>Type de don :</label>
        <select name="type">
            <?php foreach ($type_besoins as $type_besoin): ?>
                <option value="<?= $type_besoin['id'] ?>"><?= $type_besoin['nom_type_besoin'] ?></option>
            <?php endforeach; ?>
        </select>

        <label>Quantité offerte :</label>
        <input type="number" name="quantite_don">

        <label>Date de réception :</label>
        <input type="datetime-local" name="date_saisie" value="<?= date('Y-m-d\TH:i') ?>">

        <button type="submit" style="background: #27ae60; color: white; padding: 10px; border: none; cursor: pointer;">
            Simuler le Dispatch
        </button>
    </form>

</body>
</html>
