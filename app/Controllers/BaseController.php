<?php

namespace App\Controllers;

use Flight;

/**
 * Contrôleur de base avec les méthodes communes
 */
abstract class BaseController
{
    /**
     * Récupérer l'utilisateur connecté
     */
    protected function getCurrentUser(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    /**
     * Vérifier si l'utilisateur est connecté
     */
    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['user']);
    }

    /**
     * Retourner une réponse JSON
     */
    protected function json(array $data, int $status = 200): void
    {
        Flight::json($data, $status);
    }

    /**
     * Retourner une erreur JSON
     */
    protected function jsonError(string $message, int $status = 400): void
    {
        Flight::json(['error' => $message, 'success' => false], $status);
    }

    /**
     * Retourner un succès JSON
     */
    protected function jsonSuccess(array $data = [], string $message = 'Success'): void
    {
        Flight::json(array_merge(['success' => true, 'message' => $message], $data));
    }

    /**
     * Rediriger vers une URL
     */
    protected function redirect(string $url): void
    {
        Flight::redirect($url);
    }

    /**
     * Rendre une vue
     */
    protected function render(string $template, array $data = []): void
    {
        // Ajouter l'utilisateur courant aux données
        $data['currentUser'] = $this->getCurrentUser();
        Flight::render($template, $data);
    }

    /**
     * Récupérer les données POST
     */
    protected function getPostData(): array
    {
        $contentType = Flight::request()->getHeader('Content-Type');
        
        if (strpos($contentType, 'application/json') !== false) {
            $json = file_get_contents('php://input');
            return json_decode($json, true) ?? [];
        }
        
        return Flight::request()->data->getData();
    }

    /**
     * Valider les données requises
     */
    protected function validateRequired(array $data, array $required): array
    {
        $errors = [];
        foreach ($required as $field) {
            if (!isset($data[$field]) || empty(trim($data[$field]))) {
                $errors[] = "Le champ {$field} est requis";
            }
        }
        return $errors;
    }

    /**
     * Formater une date relative (il y a X minutes, etc.)
     */
    protected function formatRelativeTime(string $datetime): string
    {
        $timestamp = strtotime($datetime);
        $diff = time() - $timestamp;
        
        if ($diff < 60) {
            return 'À l\'instant';
        } elseif ($diff < 3600) {
            $minutes = floor($diff / 60);
            return "Il y a {$minutes}m";
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return "Il y a {$hours}h";
        } elseif ($diff < 604800) {
            $days = floor($diff / 86400);
            return "Il y a {$days}j";
        } else {
            return date('d/m/Y', $timestamp);
        }
    }

    /**
     * Formater une heure
     */
    protected function formatTime(string $datetime): string
    {
        return date('H:i', strtotime($datetime));
    }
}
