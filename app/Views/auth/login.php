<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Bootstrap MVC</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --primary-color: #6366f1;
            --primary-hover: #4f46e5;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: rgb(59, 99, 140) !important;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            max-width: 450px;
            width: 100%;
            padding: 20px;
        }
        
        .login-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            padding: 2.5rem;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-header .logo {
            width: 80px;
            height: 80px;
            background: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }
        
        .login-header .logo i {
            font-size: 2.5rem;
            color: white;
        }
        
        .login-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }
        
        .login-header p {
            color: #6b7280;
            margin-bottom: 0;
        }
        
        .user-select-grid {
            display: grid;
            gap: 12px;
        }
        
        .user-select-btn {
            display: flex;
            align-items: center;
            padding: 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            background: white;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: left;
            width: 100%;
        }
        
        .user-select-btn:hover {
            border-color: var(--primary-color);
            background: #f5f3ff;
        }
        
        .user-select-btn.selected {
            border-color: var(--primary-color);
            background: #f5f3ff;
        }
        
        .user-select-btn .avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-weight: 600;
            color: #4b5563;
            font-size: 1.25rem;
        }
        
        .user-select-btn .user-info h6 {
            margin: 0;
            font-weight: 600;
            color: #1f2937;
        }
        
        .user-select-btn .user-info small {
            color: #6b7280;
        }
        
        .user-select-btn .check-icon {
            margin-left: auto;
            color: var(--primary-color);
            opacity: 0;
            transition: opacity 0.2s;
        }
        
        .user-select-btn.selected .check-icon {
            opacity: 1;
        }
        
        .btn-login {
            background: var(--primary-color);
            border: none;
            padding: 0.875rem 1.5rem;
            font-weight: 600;
            border-radius: 10px;
            width: 100%;
            margin-top: 1.5rem;
            transition: all 0.2s;
        }
        
        .btn-login:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
        }
        
        .btn-login:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            transform: none;
        }
        
        .alert {
            border-radius: 10px;
        }
        
        .info-box {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 10px;
            padding: 1rem;
            margin-top: 1.5rem;
        }
        
        .info-box i {
            color: #0284c7;
        }
        
        .info-box p {
            color: #0369a1;
            margin: 0;
            font-size: 0.875rem;
        }
    </style>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-box-arrow-in-right me-2 text-primary"></i>
                            Connexion
                        </h5>
                    </div>
                    <div class="card-body">
                        <form x-data="loginForm()" @submit.prevent="submitForm()">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Email ou Username</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                        <input
                                            type="text"
                                            class="form-control"
                                            x-model="form.login"
                                            @input="validateField('login')"
                                            :class="getFieldClass('login')"
                                            placeholder="Email ou username"
                                            required
                                        >
                                    </div>
                                    <div class="invalid-feedback" x-show="errors.login" x-text="errors.login"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Mot de passe</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                        <input
                                            :type="showPassword ? 'text' : 'password'"
                                            class="form-control"
                                            x-model="form.password"
                                            @input="validatePassword()"
                                            :class="getFieldClass('password')"
                                            placeholder="Mot de passe"
                                            required
                                        >
                                        <button
                                            type="button"
                                            class="btn btn-outline-secondary"
                                            @click="showPassword = !showPassword"
                                        >
                                            <i :class="showPassword ? 'bi-eye-slash' : 'bi-eye'"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback" x-show="errors.password" x-text="errors.password"></div>

                                    <div class="password-strength mt-2" x-show="form.password">
                                        <div class="strength-bar">
                                            <div
                                                class="strength-fill"
                                                :class="passwordStrength.level"
                                                :style="`width: ${passwordStrength.percentage}%`"
                                            ></div>
                                        </div>
                                        <small :class="`text-${passwordStrength.color}`" x-text="`Force : ${passwordStrength.text}`"></small>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" x-model="form.remember">
                                        <label class="form-check-label">Se souvenir de moi</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-success" :disabled="isSubmitting || !isFormValid">
                                        <span x-show="!isSubmitting">
                                            <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
                                        </span>
                                        <span x-show="isSubmitting">
                                            <div class="spinner-border spinner-border-sm me-2"></div>
                                            Connexion...
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Exigences du mot de passe</h6>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Au moins 8 caractères</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Une lettre majuscule</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Une lettre minuscule</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Un chiffre</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>Un caractère spécial</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function loginForm() {
            return {
                form: { login: '', password: '', remember: false },
                errors: {},
                showPassword: false,
                isSubmitting: false,
                passwordStrength: { level: '', percentage: 0, text: 'Faible', color: 'danger' },

                getFieldClass(field) {
                    return this.errors[field] ? 'is-invalid' : '';
                },

                validateField(field) {
                    if (!this.form[field]) {
                        this.errors[field] = 'Ce champ est requis';
                    } else {
                        delete this.errors[field];
                    }
                },

                validatePassword() {
                    const p = this.form.password || '';
                    let score = 0;
                    if (p.length >= 8) score += 1;
                    if (/[A-Z]/.test(p)) score += 1;
                    if (/[a-z]/.test(p)) score += 1;
                    if (/[0-9]/.test(p)) score += 1;
                    if (/[^A-Za-z0-9]/.test(p)) score += 1;

                    const percentage = (score / 5) * 100;
                    let text = 'Très faible', color = 'danger', level = 'level-1';
                    if (score >= 4) { text = 'Fort'; color = 'success'; level = 'level-4'; }
                    else if (score === 3) { text = 'Moyen'; color = 'warning'; level = 'level-3'; }
                    else if (score === 2) { text = 'Faible'; color = 'warning'; level = 'level-2'; }

                    this.passwordStrength = { level, percentage, text, color };
                    this.validateField('password');
                },

                get isFormValid() {
                    return this.form.login && this.form.password && Object.keys(this.errors).length === 0;
                },

                async submitForm() {
                    this.validateField('login');
                    this.validateField('password');
                    if (!this.isFormValid) return;

                    this.isSubmitting = true;
                    try {
                        const res = await fetch('/login', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify(this.form)
                        });
                        const data = await res.json();
                        if (data.success) {
                            window.location.href = data.redirect || '/dashboard';
                        } else {
                            alert(data.error || 'Échec de la connexion');
                        }
                    } catch (e) {
                        alert('Erreur de connexion au serveur');
                    } finally {
                        this.isSubmitting = false;
                    }
                }
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
