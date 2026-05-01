<?php 
require_once 'config.php';

// Si déjà connecté, rediriger vers l'accueil
if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

// ==================== INSCRIPTION ====================
if (isset($_POST['register'])) {
    $username = trim($_POST['reg_username']);
    $password = trim($_POST['reg_password']);
    $confirm  = trim($_POST['reg_confirm']);
    
    // Validation
    if (empty($username) || empty($password) || empty($confirm)) {
        $error = "Tous les champs sont obligatoires.";
    } elseif (strlen($username) < 3) {
        $error = "Le nom d'utilisateur doit contenir au moins 3 caractères.";
    } elseif (strlen($password) < 6) {
        $error = "Le mot de passe doit contenir au moins 6 caractères.";
    } elseif ($password !== $confirm) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        // Vérifier si l'utilisateur existe déjà
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        
        if ($stmt->fetch()) {
            $error = "Ce nom d'utilisateur existe déjà.";
        } else {
            // Créer le compte
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'user')");
            $stmt->execute([$username, $hash]);
            
            $success = "Compte créé avec succès ! Vous pouvez maintenant vous connecter.";
        }
    }
}

// ==================== CONNEXION ====================
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header('Location: index.php');
            exit;
        } else {
            $error = "Identifiants incorrects.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BiblioPlus - Connexion</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Styles spécifiques à la page login */
        .login-wrapper {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            padding: 20px;
        }
        
        .login-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 450px;
            overflow: hidden;
        }
        
        .login-header {
            background: #2563eb;
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .login-header h1 {
            font-size: 1.8rem;
            margin-bottom: 5px;
        }
        
        .login-header p {
            opacity: 0.8;
            font-size: 0.9rem;
        }
        
        .login-body {
            padding: 30px;
        }
        
        /* Onglets Connexion / Inscription */
        .tabs {
            display: flex;
            border-bottom: 2px solid #e2e8f0;
            margin-bottom: 25px;
        }
        
        .tab-btn {
            flex: 1;
            padding: 12px;
            border: none;
            background: none;
            font-size: 15px;
            font-weight: 600;
            color: #94a3b8;
            cursor: pointer;
            transition: all 0.3s;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px;
        }
        
        .tab-btn.active {
            color: #2563eb;
            border-bottom-color: #2563eb;
        }
        
        .tab-btn:hover {
            color: #1e293b;
        }
        
        /* Contenu des onglets */
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        /* Formulaire */
        .form-group {
            margin-bottom: 18px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #1e293b;
            font-size: 14px;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 15px;
            transition: border 0.3s;
            box-sizing: border-box;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        
        /* Messages */
        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .alert-error {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        
        .alert-success {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }
        
        /* Bouton */
        .btn-submit {
            width: 100%;
            padding: 13px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s, transform 0.1s;
        }
        
        .btn-submit:hover {
            background: #1d4ed8;
        }
        
        .btn-submit:active {
            transform: scale(0.98);
        }
        
        /* Info compte demo */
        .demo-info {
            text-align: center;
            margin-top: 20px;
            padding: 12px;
            background: #f8fafc;
            border-radius: 8px;
            font-size: 13px;
            color: #64748b;
        }
        
        .demo-info strong {
            color: #1e293b;
        }
        
        /* Pied */
        .login-footer {
            text-align: center;
            margin-top: 20px;
            color: #94a3b8;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            
            <!-- En-tête -->
            <div class="login-header">
                <h1>📚 BiblioPlus</h1>
                <p>Gestion de Bibliothèque</p>
            </div>
            
            <!-- Corps -->
            <div class="login-body">
                
                <!-- Onglets -->
                <div class="tabs">
                    <button class="tab-btn active" onclick="switchTab('login-tab', this)">Connexion</button>
                    <button class="tab-btn" onclick="switchTab('register-tab', this)">Inscription</button>
                </div>
                
                <!-- Messages -->
                <?php if ($error): ?>
                    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>
                
                <!-- ========== FORMULAIRE DE CONNEXION ========== -->
                <div id="login-tab" class="tab-content active">
                    <form method="POST">
                        <div class="form-group">
                            <label for="username">Nom d'utilisateur</label>
                            <input type="text" name="username" id="username" 
                                   placeholder="Entrez votre nom d'utilisateur" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Mot de passe</label>
                            <input type="password" name="password" id="password" 
                                   placeholder="Entrez votre mot de passe" required>
                        </div>
                        
                        <button type="submit" name="login" class="btn-submit">
                            Se connecter
                        </button>
                    </form>
                    
                    <div class="demo-info">
                        <strong>Compte démo :</strong> admin / admin123
                    </div>
                </div>
                
                <!-- ========== FORMULAIRE D'INSCRIPTION ========== -->
                <div id="register-tab" class="tab-content">
                    <form method="POST">
                        <div class="form-group">
                            <label for="reg_username">Nom d'utilisateur</label>
                            <input type="text" name="reg_username" id="reg_username" 
                                   placeholder="Choisissez un nom d'utilisateur" 
                                   minlength="3" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="reg_password">Mot de passe</label>
                            <input type="password" name="reg_password" id="reg_password" 
                                   placeholder="Minimum 6 caractères" 
                                   minlength="6" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="reg_confirm">Confirmer le mot de passe</label>
                            <input type="password" name="reg_confirm" id="reg_confirm" 
                                   placeholder="Répétez le mot de passe" 
                                   minlength="6" required>
                        </div>
                        
                        <button type="submit" name="register" class="btn-submit">
                            Créer un compte
                        </button>
                    </form>
                    
                    <p class="login-footer">
                        En créant un compte, vous acceptez nos conditions d'utilisation.
                    </p>
                </div>
                
            </div>
        </div>
    </div>
    
    <script>
        // Changement d'onglet Connexion / Inscription
        function switchTab(tabId, btn) {
            // Désactiver tous les onglets
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Activer l'onglet sélectionné
            document.getElementById(tabId).classList.add('active');
            btn.classList.add('active');
            
            // Effacer les messages d'erreur/succès
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => alert.style.display = 'none');
        }
        
        // Afficher le message d'erreur dans le bon onglet
        <?php if ($error && isset($_POST['register'])): ?>
            switchTab('register-tab', document.querySelectorAll('.tab-btn')[1]);
        <?php endif; ?>
        
        // Réafficher les alertes si elles étaient cachées
        document.querySelectorAll('.alert').forEach(alert => {
            alert.style.display = 'block';
        });
    </script>
</body>
</html>