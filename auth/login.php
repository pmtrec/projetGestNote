<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduGrades - Connexion Futuriste</title>
    <link rel="stylesheet" href="/css/ultra-dynamic.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Particules d'arrière-plan -->
    <div class="particles" id="particles"></div>
    
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1>🚀 EduGrades</h1>
                <p>Système de Gestion Futuriste des Notes Étudiantes</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <strong>⚠️ Erreur :</strong> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <div class="login-tabs">
                <button class="tab-button active" onclick="switchTab('student')">
                    🎓 Étudiant
                </button>
                <button class="tab-button" onclick="switchTab('admin')">
                    👨‍💼 Administrateur
                </button>
            </div>

            <form method="POST" action="/" id="loginForm">
                <div id="student-tab" class="tab-content">
                    <div class="form-group">
                        <label for="matricule">🆔 Numéro de matricule</label>
                        <input type="text" id="matricule" name="login" class="form-control" 
                               placeholder="Ex: ETU001" required>
                    </div>
                </div>

                <div id="admin-tab" class="tab-content" style="display: none;">
                    <div class="form-group">
                        <label for="email">📧 Email administrateur</label>
                        <input type="email" id="email" name="login" class="form-control" 
                               placeholder="admin@school.com">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">🔒 Mot de passe</label>
                    <input type="password" id="password" name="password" class="form-control" 
                           placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn-primary" id="loginBtn">
                    <span id="loginText">🚀 Connexion Sécurisée</span>
                    <span id="loginLoader" class="loading" style="display: none;"></span>
                </button>
            </form>

            <div class="test-accounts">
                <h4>🧪 Comptes de démonstration</h4>
                <p><strong>👨‍💼 Admin:</strong> admin@school.com / password</p>
                <p><strong>🎓 Étudiants:</strong> ETU001, ETU002, ETU003 / password</p>
            </div>
        </div>
    </div>

    <script src="/js/ultra-dynamic.js"></script>
    <script>
    // Script spécifique à la page de connexion
    document.addEventListener('DOMContentLoaded', function() {
        // Changement d'onglet avec animation
        window.switchTab = function(type) {
            const buttons = document.querySelectorAll('.tab-button');
            const studentTab = document.getElementById('student-tab');
            const adminTab = document.getElementById('admin-tab');
            
            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            if (type === 'student') {
                studentTab.style.display = 'block';
                adminTab.style.display = 'none';
                studentTab.style.animation = 'slideInExplosion 0.5s ease';
            } else {
                studentTab.style.display = 'none';
                adminTab.style.display = 'block';
                adminTab.style.animation = 'slideInExplosion 0.5s ease';
            }
            
            // Vider les champs
            document.querySelectorAll('input[name="login"]').forEach(input => {
                input.value = '';
            });
        };

        // Animation du formulaire de connexion
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const loginBtn = document.getElementById('loginBtn');
            const loginText = document.getElementById('loginText');
            const loginLoader = document.getElementById('loginLoader');
            
            loginText.style.display = 'none';
            loginLoader.style.display = 'inline-block';
            loginBtn.disabled = true;
            
            // Laisser le formulaire se soumettre normalement après l'animation
            setTimeout(() => {
                // Le formulaire se soumet automatiquement
            }, 1000);
        });
    });
    </script>
</body>
</html>
