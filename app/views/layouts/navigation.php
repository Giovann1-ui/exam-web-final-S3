<?php
$current_page = $_SERVER['REQUEST_URI'];
$csp_nonce = $csp_nonce ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/bootstrap-icons/font/bootstrap-icons.css">
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/global.css">
</head>
<body>

<!-- Topbar -->
<div class="topbar">
    <div class="brand">
        <i class="bi bi-shield-fill-check"></i>
        BNGRC
    </div>
    <button class="menu-toggle" id="menuToggle">
        <i class="bi bi-list"></i>
    </button>
    <div class="user-section">
        <span style="color: var(--muted-text);">
            <i class="bi bi-person-circle"></i> Administrateur
        </span>
    </div>
</div>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-section-title">Navigation</div>
    
    <a href="/" class="menu-item <?= $current_page === '/' ? 'active' : '' ?>">
        <i class="bi bi-house-door"></i>
        <span>Accueil</span>
    </a>
    
    <a href="/" class="menu-item <?= str_contains($current_page, '/dashboard') ? 'active' : '' ?>">
        <i class="bi bi-speedometer2"></i>
        <span>Tableau de Bord</span>
    </a>
    
    <div class="sidebar-section-title">Gestion des Dons</div>
    
    <a href="/dons" class="menu-item <?= $current_page === '/dons' ? 'active' : '' ?>">
        <i class="bi bi-list-ul"></i>
        <span>Liste des Dons</span>
    </a>
    
    <a href="/dons/give" class="menu-item <?= $current_page === '/dons/give' ? 'active' : '' ?>">
        <i class="bi bi-plus-circle"></i>
        <span>Ajouter un Don</span>
    </a>

    <a href="/dons/simulation" class="menu-item <?= $current_page === '/dons/simulation' ? 'active' : '' ?>">
        <i class="bi bi-graph-up-arrow"></i>
        <span>Simulation / Distribution</span>
    </a>
    
    <a href="/historique-achats" class="menu-item <?= str_contains($current_page, '/historique-achats') ? 'active' : '' ?>">
        <i class="bi bi-receipt"></i>
        <span>Historique des Achats</span>
    </a>
    
    <a href="/achats/besoins" class="menu-item <?= str_contains($current_page, '/achats') ? 'active' : '' ?>">
        <i class="bi bi-cart"></i>
        <span>Besoins à Acheter</span>
    </a>
    
    <div class="sidebar-section-title">Données</div>
    
    <a href="/recap" class="menu-item <?= str_contains($current_page, '/recap') ? 'active' : '' ?>">
        <i class="bi bi-graph-up"></i>
        <span>Récapitulatif</span>
    </a>
    
    <a href="/villes" class="menu-item <?= str_contains($current_page, '/villes') ? 'active' : '' ?>">
        <i class="bi bi-geo-alt"></i>
        <span>Villes</span>
    </a>
    
    <a href="/besoins" class="menu-item <?= str_contains($current_page, '/besoins') ? 'active' : '' ?>">
        <i class="bi bi-exclamation-triangle"></i>
        <span>Besoins</span>
    </a>
</div>

<script nonce="<?= $csp_nonce ?>">
    document.getElementById('menuToggle')?.addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('active');
    });
</script>