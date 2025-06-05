<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .login-box {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            background: white;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            border-radius: 12px;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h4 class="mb-4 text-center">Connexion <i class="bi bi-gear-fill"></i></h4>
    <form id="loginForm">
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" id="password" required>
        </div>
        <div id="error-message" class="text-danger mb-3" style="display: none;">Mot de passe incorrect</div>
        <a href="./recherche_developpeur.php">
        <button type="submit" class="btn btn-primary w-100">Se connecter</button>
        </a>
    </form>
</div>

<!-- Bootstrap JS (optionnel) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const password = document.getElementById('password').value;
        const staticPassword = "123"; // <- Mettez ici votre mot de passe
        
        if (password === staticPassword) {
            // Redirection simulée (modifiez la page cible si besoin)
            window.location.href = "./recherche_developeur.php";
        } else {
            document.getElementById('error-message').style.display = 'block';
        }
    });
</script>

</body>
</html>
