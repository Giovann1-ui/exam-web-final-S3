<?php
$title = 'Dashboard';
$page = 'dashboard';
ob_start();
?>

<div class="container-fluid p-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Dashboard</h1>
            <p class="text-muted mb-0">Bienvenue, <?= htmlspecialchars($currentUser['username']) ?> !</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-envelope text-primary fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-0">Messages non lus</p>
                            <h3 class="mb-0"><?= $unreadCount ?? 0 ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-people text-success fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-0">Utilisateurs en ligne</p>
                            <h3 class="mb-0"><?= count($onlineUsers ?? []) ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-lightning me-2"></i>Actions rapides
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <a href="/messages" class="btn btn-primary btn-lg">
                            <i class="bi bi-chat-dots me-2"></i>
                            Accéder aux messages
                            <?php if (($unreadCount ?? 0) > 0): ?>
                            <span class="badge bg-white text-primary ms-2"><?= $unreadCount ?> non lus</span>
                            <?php endif; ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-people me-2"></i>Utilisateurs en ligne
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($onlineUsers)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($onlineUsers as $user): ?>
                        <?php if ($user['id'] != $currentUser['id']): ?>
                        <div class="list-group-item d-flex align-items-center px-0">
                            <div class="position-relative me-3">
                                <img src="<?= $user['avatar'] ?>" alt="" 
                                     width="40" height="40" class="rounded-circle">
                                <span class="position-absolute bottom-0 end-0 bg-success rounded-circle border border-2 border-white" 
                                      style="width: 12px; height: 12px;"></span>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0"><?= htmlspecialchars($user['username']) ?></h6>
                                <small class="text-muted"><?= $user['email'] ?></small>
                            </div>
                            <a href="/messages" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-chat"></i>
                            </a>
                        </div>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <p class="text-muted mb-0">Aucun autre utilisateur en ligne</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>
