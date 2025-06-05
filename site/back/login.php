<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>PHOTOVOLTIS - Connexion</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Inclusion de Bootstrap CSS pour le style -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Inclusion des icônes Bootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #28C1B9;
            --secondary-color: #26c6da;
            --tertiary-color: #00acc1;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 50%, var(--tertiary-color) 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            overflow-x: hidden;
        }

        /* Animation de fond */
        .animated-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 50%, var(--tertiary-color) 100%);
            z-index: -2;
        }

        .animated-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(255,255,255,0.05) 0%, transparent 50%);
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        /* Navbar personnalisée */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            color: white !important;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-brand i {
            font-size: 1.8rem;
            color: #ffd700;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            transition: all 0.3s ease;
            margin: 0 0.5rem;
            padding: 0.5rem 1rem !important;
            border-radius: 25px;
        }

        .nav-link:hover {
            color: white !important;
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .nav-link.active {
            color: white !important;
            background: rgba(255, 255, 255, 0.2);
        }

        /* Container de connexion */
        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 80px);
            padding: 2rem 0;
        }

        .login-box {
            width: 100%;
            max-width: 450px;
            padding: 3rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 
                0 20px 60px rgba(0, 0, 0, 0.1),
                0 8px 32px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
        }

        .login-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color), var(--tertiary-color));
            border-radius: 20px 20px 0 0;
        }

        .login-title {
            text-align: center;
            margin-bottom: 2rem;
            color: #333;
            font-weight: 600;
            font-size: 1.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .login-title i {
            color: var(--primary-color);
            font-size: 2rem;
        }

        .form-label {
            font-weight: 600;
            color: #555;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(40, 193, 185, 0.25);
            background: white;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 50%, var(--tertiary-color) 100%);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(40, 193, 185, 0.4);
            background: linear-gradient(135deg, #1fa39a 0%, #1eb5c7 50%, #00939e 100%);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .error-message {
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            color: white;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            font-weight: 500;
            text-align: center;
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .fade-in {
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Pied de page */
        .footer-custom {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            margin-top: auto;
        }

        .footer-custom small {
            display: block;
            margin: 0.2rem 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .login-box {
                margin: 1rem;
                padding: 2rem;
            }
            
            .login-title {
                font-size: 1.5rem;
            }
        }

        /* Animation d'entrée */
        .login-box {
            animation: slideUp 0.8s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
    </style>
</head>
<body>
    <!-- Animation de fond -->
    <div class="animated-bg"></div>

    <!-- Barre de navigation principale -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container">
            <!-- Logo et nom du site -->
            <a class="navbar-brand" href="accueil.php">
                <i class="bi bi-lightning-charge"></i>
                PHOTOVOLTIS
            </a>
            <!-- Bouton pour menu mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Liens de navigation -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="accueil.php">
                            <i class="bi bi-house"></i> Accueil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="recherche.php">
                            <i class="bi bi-search"></i> Recherche
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="carte.php">
                            <i class="bi bi-map"></i> Carte
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Container de connexion -->
    <div class="login-container">
        <div class="login-box fade-in">
            <h4 class="login-title">
                <i class="bi bi-shield-lock"></i>
                Connexion Administrateur
            </h4>
            <form id="loginForm">
                <div class="mb-4">
                    <label for="password" class="form-label">
                        <i class="bi bi-key"></i> Mot de passe
                    </label>
                    <input type="password" class="form-control" id="password" placeholder="Entrez votre mot de passe" required>
                </div>
                <div id="error-message" class="error-message" style="display: none;">
                    <i class="bi bi-exclamation-triangle"></i> Mot de passe incorrect
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-box-arrow-in-right"></i> Se connecter
                </button>
            </form>
        </div>
    </div>

    <!-- Pied de page -->
    <footer class="footer-custom py-4 mt-auto">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <!-- Noms des auteurs -->
                    <div class="d-flex flex-wrap justify-content-center gap-4 mb-3">
                        <p>Mathis CHARTIER / Mathieu GICQUEL--BOURDEAU / Alexis ROCHON--SANZ</p>
                    </div>
                    <div class="text-center">
                        <small>CIR2 2024/2025</small>
                        <small>GROUPE 9</small>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Inclusion de Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const password = document.getElementById('password').value;
            const staticPassword = "123"; // <- Mettez ici votre mot de passe
            
            if (password === staticPassword) {
                // Animation de succès
                const button = e.target.querySelector('button');
                button.innerHTML = '<i class="bi bi-check-circle"></i> Connexion réussie !';
                button.style.background = 'linear-gradient(135deg, #51cf66, #40c057)';
                
                // Redirection après un délai
                setTimeout(() => {
                    window.location.href = "./recherche_developeur.php";
                }, 1000);
            } else {
                const errorMsg = document.getElementById('error-message');
                errorMsg.style.display = 'block';
                
                // Masquer le message d'erreur après 3 secondes
                setTimeout(() => {
                    errorMsg.style.display = 'none';
                }, 3000);
            }
        });

        // Animation au focus du champ de mot de passe
        document.getElementById('password').addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.02)';
            this.parentElement.style.transition = 'transform 0.3s ease';
        });

        document.getElementById('password').addEventListener('blur', function() {
            this.parentElement.style.transform = 'scale(1)';
        });
    </script>
</body>
</html>