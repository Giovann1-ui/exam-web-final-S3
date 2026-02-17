<?php

/**
 * Fichier de fonctions helper pour l'application
 */

/**
 * Retourne l'URL de base de l'application
 * @return string
 */
function base_url($path = '') {
    $base = Flight::get('flight.base_url') ?? '';
    
    // S'assurer que le path commence par /
    if (!empty($path) && $path[0] !== '/') {
        $path = '/' . $path;
    }
    
    return $base . $path;
}

/**
 * Retourne l'URL complète d'un asset
 * @param string $path Chemin relatif de l'asset (ex: 'css/style.css')
 * @return string
 */
function asset_url($path) {
    $base = Flight::get('flight.base_url') ?? '';
    
    // S'assurer que le path commence par /
    if ($path[0] !== '/') {
        $path = '/' . $path;
    }
    
    return $base . '/assets' . $path;
}

/**
 * Redirige vers une URL relative à la base de l'application
 * @param string $path
 * @return void
 */
function redirect($path = '') {
    $url = base_url($path);
    Flight::redirect($url);
}
