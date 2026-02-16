<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Bootstrap MVC' ?> - Admin Dashboard</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="/assets/icons/favicon.svg">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
    
    <?php if (isset($extraCss)): ?>
        <?= $extraCss ?>
    <?php endif; ?>
</head>
<body data-page="<?= $page ?? 'dashboard' ?>" class="admin-layout">
    
    <!-- Main Wrapper -->
    <div class="admin-wrapper" id="admin-wrapper">
        
        <!-- Header -->
        <header class="admin-header">
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
                <div class="container-fluid">
                    <!-- Logo -->
                    <a class="navbar-brand d-flex align-items-center" href="/dashboard">
                        <i class="bi bi-bootstrap-fill text-primary fs-3 me-2"></i>
                        <h1 class="h4 mb-0 fw-bold text-primary">Metis</h1>
                    </a>

                    <!-- Right Side -->
                    <div class="navbar-nav flex-row ms-auto">
                        <!-- Theme Toggle -->
                        <button class="btn btn-outline-secondary me-2" type="button" id="themeToggle" title="Changer le thème">
                            <i class="bi bi-sun-fill" id="themeIcon"></i>
                        </button>

                        <!-- Messages -->
                        <a href="/messages" class="btn btn-outline-secondary position-relative me-2">
                            <i class="bi bi-chat-dots"></i>
                            <?php if (isset($unreadCount) && $unreadCount > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="unreadBadge">
                                <?= $unreadCount ?>
                            </span>
                            <?php endif; ?>
                        </a>

                        <!-- User Menu -->
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                                <img src="<?= $currentUser['avatar'] ?? '/assets/images/avatar-placeholder.svg' ?>" 
                                     alt="Avatar" width="24" height="24" class="rounded-circle me-2">
                                <span class="d-none d-md-inline"><?= htmlspecialchars($currentUser['username'] ?? 'Utilisateur') ?></span>
                                <i class="bi bi-chevron-down ms-1"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="/messages"><i class="bi bi-chat-dots me-2"></i>Messages</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="/logout"><i class="bi bi-box-arrow-right me-2"></i>Déconnexion</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        </header>

        <!-- Sidebar -->
        <aside class="admin-sidebar" id="admin-sidebar">
            <div class="sidebar-content">
                <nav class="sidebar-nav">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?= ($page ?? '') === 'dashboard' ? 'active' : '' ?>" href="/dashboard">
                                <i class="bi bi-speedometer2"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($page ?? '') === 'messages' ? 'active' : '' ?>" href="/messages">
                                <i class="bi bi-chat-dots"></i>
                                <span>Messages</span>
                                <?php if (isset($unreadCount) && $unreadCount > 0): ?>
                                <span class="badge bg-primary rounded-pill ms-auto"><?= $unreadCount ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item mt-3">
                            <small class="text-muted px-3 text-uppercase fw-bold">Compte</small>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-danger" href="/logout">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Déconnexion</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Hamburger Menu -->
        <button class="hamburger-menu" type="button" id="sidebarToggle" aria-label="Toggle sidebar">
            <i class="bi bi-list"></i>
        </button>

        <!-- Main Content -->
        <main class="admin-main">
            <?= $content ?? '' ?>
        </main>

        <!-- Footer -->
        <footer class="admin-footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-0 text-muted">© 2026 Bootstrap MVC - Système de messagerie</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="mb-0 text-muted">Construit avec FlightPHP & Bootstrap 5</p>
                    </div>
                </div>
            </div>
        </footer>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Theme Toggle Script -->
    <script>
        // Theme Management
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        
        function setTheme(theme) {
            document.documentElement.setAttribute('data-bs-theme', theme);
            localStorage.setItem('theme', theme);
            themeIcon.className = theme === 'dark' ? 'bi bi-moon-fill' : 'bi bi-sun-fill';
        }
        
        // Initialize theme
        const savedTheme = localStorage.getItem('theme') || 'light';
        setTheme(savedTheme);
        
        themeToggle.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-bs-theme');
            setTheme(currentTheme === 'dark' ? 'light' : 'dark');
        });

        // Sidebar Toggle
        const sidebarToggle = document.getElementById('sidebarToggle');
        const wrapper = document.getElementById('admin-wrapper');
        
        if (sidebarToggle && wrapper) {
            const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
            if (isCollapsed) {
                wrapper.classList.add('sidebar-collapsed');
            }
            
            sidebarToggle.addEventListener('click', () => {
                wrapper.classList.toggle('sidebar-collapsed');
                localStorage.setItem('sidebar-collapsed', wrapper.classList.contains('sidebar-collapsed'));
            });
        }

        // Heartbeat pour maintenir le statut en ligne
        setInterval(() => {
            fetch('/api/heartbeat', { method: 'POST' });
        }, 60000); // Toutes les minutes
    </script>
    
    <?php if (isset($extraJs)): ?>
        <?= $extraJs ?>
    <?php endif; ?>
</body>
</html>
