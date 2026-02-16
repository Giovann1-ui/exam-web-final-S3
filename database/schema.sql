-- Script de création de la base de données pour Bootstrap MVC
-- Système de messagerie entre utilisateurs

-- Création de la base de données
CREATE DATABASE IF NOT EXISTS bootstrap_mvc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bootstrap_mvc;

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    avatar VARCHAR(255) DEFAULT '/assets/images/avatar-placeholder.svg',
    is_online TINYINT(1) DEFAULT 0,
    last_seen DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des messages
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    content TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    read_at DATETIME NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_sender (sender_id),
    INDEX idx_receiver (receiver_id),
    INDEX idx_is_read (is_read),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertion des utilisateurs de test (avec auto-login)
-- Mot de passe: "password" hashé avec password_hash()
INSERT INTO users (username, email, password, avatar, is_online) VALUES
('john_doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '/assets/images/avatar-placeholder.svg', 1),
('sarah_johnson', 'sarah@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '/assets/images/avatar-placeholder.svg', 1),
('mike_wilson', 'mike@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '/assets/images/avatar-placeholder.svg', 0),
('emma_davis', 'emma@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '/assets/images/avatar-placeholder.svg', 1),
('alex_brown', 'alex@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '/assets/images/avatar-placeholder.svg', 0);

-- Insertion de quelques messages de test
INSERT INTO messages (sender_id, receiver_id, content, is_read, created_at) VALUES
(2, 1, 'Bonjour John ! Comment vas-tu ?', 1, NOW() - INTERVAL 2 HOUR),
(1, 2, 'Salut Sarah ! Je vais bien, merci. Et toi ?', 1, NOW() - INTERVAL 1 HOUR 50 MINUTE),
(2, 1, 'Très bien ! J''ai une question sur le projet.', 1, NOW() - INTERVAL 1 HOUR 45 MINUTE),
(1, 2, 'Bien sûr, je t''écoute.', 1, NOW() - INTERVAL 1 HOUR 40 MINUTE),
(2, 1, 'Est-ce que tu peux m''aider avec la base de données ?', 0, NOW() - INTERVAL 30 MINUTE),
(3, 1, 'Hey John, tu as vu le match hier ?', 0, NOW() - INTERVAL 1 HOUR),
(4, 1, 'Réunion demain à 10h, n''oublie pas !', 0, NOW() - INTERVAL 45 MINUTE),
(1, 3, 'Oui Mike, c''était un super match !', 1, NOW() - INTERVAL 30 MINUTE),
(5, 1, 'Salut, as-tu le temps de parler du projet ?', 0, NOW() - INTERVAL 15 MINUTE);
