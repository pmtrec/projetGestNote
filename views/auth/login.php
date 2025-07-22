<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduGrades - Connexion</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Particules animées */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
            position: relative;
            z-index: 2;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h1 {
            font-size: 2.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .login-header p {
            color: #666;
            font-size: 1.1rem;
        }

        .login-tabs {
            display: flex;
            margin-bottom: 30px;
            background: #f8f9fa;
            border-radius: 12px;
            padding: 4px;
        }

        .tab-button {
            flex: 1;
            padding: 12px 20px;
            border: none;
            background: transparent;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            color: #666;
        }

        .tab-button.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 0.95rem;
        }

        .form-control {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #fff;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        .btn-primary {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-primary:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .test-accounts {
            margin-top: 30px;
            padding: 20px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border-radius: 12px;
            color: white;
            text-align: center;
        }

        .test-accounts h4 {
            margin-bottom: 15px;
            font-size: 1.1rem;
        }

        .test-accounts p {
            margin: 8px 0;
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .alert-danger {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .tab-content {
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-container {
                margin: 20px;
                padding: 30px 25px;
            }
            
            .login-header h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="particles" id="particles"></div>
    
    <div class="login-container">
        <div class="login-header">
            <h1>🚀 EduGrades</h1>
            <p>Système de Gestion des Notes Étudiantes</p>
        </div>

        <div id="alert-container"></div>

        <div class="login-tabs">
            <button class="tab-button active" onclick="switchTab('student')">
                🎓 Étudiant
            </button>
            <button class="tab-button" onclick="switchTab('admin')">
                👨‍💼 Administrateur
            </button>
        </div>

        <form id="loginForm">
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

    <script>
        // Création des particules
        function createParticles() {
            const particles = document.getElementById('particles');
            for (let i = 0; i < 50; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                particle.style.width = Math.random() * 10 + 5 + 'px';
                particle.style.height = particle.style.width;
                particle.style.animationDelay = Math.random() * 6 + 's';
                particle.style.animationDuration = (Math.random() * 3 + 3) + 's';
                particles.appendChild(particle);
            }
        }

        // Changement d'onglet
        function switchTab(type) {
            const buttons = document.querySelectorAll('.tab-button');
            const studentTab = document.getElementById('student-tab');
            const adminTab = document.getElementById('admin-tab');
            
            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            if (type === 'student') {
                studentTab.style.display = 'block';
                adminTab.style.display = 'none';
            } else {
                studentTab.style.display = 'none';
                adminTab.style.display = 'block';
            }
            
            // Vider les champs
            document.querySelectorAll('input[name="login"]').forEach(input => {
                input.value = '';
            });
        }

        // Affichage des alertes
        function showAlert(message, type = 'danger') {
            const alertContainer = document.getElementById('alert-container');
            alertContainer.innerHTML = `
                <div class="alert alert-${type}">
                    <strong>${type === 'danger' ? '⚠️ Erreur :' : '✅ Succès :'}</strong> ${message}
                </div>
            `;
            
            setTimeout(() => {
                alertContainer.innerHTML = '';
            }, 5000);
        }

        // Gestion du formulaire de connexion
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const loginBtn = document.getElementById('loginBtn');
            const loginText = document.getElementById('loginText');
            const loginLoader = document.getElementById('loginLoader');
            
            loginText.style.display = 'none';
            loginLoader.style.display = 'inline-block';
            loginBtn.disabled = true;
            
            const formData = new FormData(this);
            
            fetch('/', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Connexion réussie ! Redirection...', 'success');
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1000);
                } else {
                    showAlert(data.message);
                    loginText.style.display = 'inline';
                    loginLoader.style.display = 'none';
                    loginBtn.disabled = false;
                }
            })
            .catch(error => {
                showAlert('Erreur de connexion au serveur');
                loginText.style.display = 'inline';
                loginLoader.style.display = 'none';
                loginBtn.disabled = false;
            });
        });

        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            createParticles();
        });
    </script>
</body>
</html>
