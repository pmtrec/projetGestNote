<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduGrades - Mon Espace</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            min-height: 100vh;
            color: #333;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 15px 0;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .navbar-nav {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .nav-link {
            text-decoration: none;
            color: #666;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            transform: translateY(-2px);
        }

        .main-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        .page-header {
            text-align: center;
            margin-bottom: 40px;
            color: white;
        }

        .page-header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .page-header p {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        .quick-actions {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-success {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        .btn-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .btn-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            font-size: 3rem;
            margin-bottom: 15px;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #4facfe;
            margin-bottom: 10px;
        }

        .stat-label {
            color: #666;
            font-weight: 500;
        }

        .content-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .content-card h2 {
            margin-bottom: 20px;
            color: #333;
            font-size: 1.5rem;
        }

        .table-container {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .table th {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }

        .table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            background: white;
        }

        .table tr:hover td {
            background: #f8f9fa;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-success {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        .badge-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .badge-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .badge-danger {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            color: white;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background: white;
            margin: 5% auto;
            padding: 30px;
            border-radius: 20px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            position: relative;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f8f9fa;
        }

        .modal-header h3 {
            color: #333;
            font-size: 1.3rem;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #999;
            padding: 5px;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .close-modal:hover {
            background: #f8f9fa;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #4facfe;
            box-shadow: 0 0 0 3px rgba(79, 172, 254, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(79, 172, 254, 0.4);
        }

        /* Toast Notifications */
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            z-index: 1001;
            transform: translateX(400px);
            transition: all 0.3s ease;
        }

        .toast.show {
            transform: translateX(0);
        }

        .toast.success {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .toast.error {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .info-card {
            background: rgba(255, 255, 255, 0.1);
            padding: 25px;
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .info-card h4 {
            color: white;
            margin-bottom: 15px;
            font-size: 1.2rem;
        }

        .info-card p {
            color: rgba(255, 255, 255, 0.9);
            margin: 8px 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar-content {
                flex-direction: column;
                gap: 15px;
            }

            .navbar-nav {
                flex-wrap: wrap;
                justify-content: center;
            }

            .quick-actions {
                flex-direction: column;
                align-items: center;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .page-header h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-content">
            <div class="navbar-brand">🚀 EduGrades Étudiant</div>
            <div class="navbar-nav">
                <span class="nav-link">👋 Salut, <?= htmlspecialchars($student['prenom']) ?>!</span>
                <a href="/student/dashboard" class="nav-link">📊 Dashboard</a>
                <a href="/logout" class="nav-link">🚪 Déconnexion</a>
            </div>
        </div>
    </nav>

    <main class="main-content">
        <div class="page-header">
            <h1>🌟 Mon Espace Personnel</h1>
            <p>Bienvenue dans votre cockpit académique, <?= htmlspecialchars($student['prenom'] . ' ' . $student['nom']) ?>!</p>
        </div>

        <div class="quick-actions">
            <button class="btn btn-success" onclick="downloadTranscript()">
                📄 Télécharger Relevé
            </button>
            <button class="btn btn-warning" onclick="openModal('passwordModal')">
                🔒 Changer Mot de Passe
            </button>
            <button class="btn btn-info" onclick="openModal('settingsModal')">
                ⚙️ Paramètres
            </button>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">📊</div>
                <div class="stat-number"><?= number_format($average, 2) ?></div>
                <div class="stat-label">Moyenne Générale</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">✅</div>
                <div class="stat-number"><?= $validatedCount ?></div>
                <div class="stat-label">Matières Validées</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">📚</div>
                <div class="stat-number"><?= count($grades) ?></div>
                <div class="stat-label">Notes Disponibles</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">🎯</div>
                <div class="stat-number"><?= $average >= 10 ? '✅' : '❌' ?></div>
                <div class="stat-label"><?= $average >= 10 ? 'Admis' : 'Non Admis' ?></div>
            </div>
        </div>

        <div class="content-card">
            <h2>🏆 Mes Performances Académiques</h2>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Matière</th>
                            <th>Note</th>
                            <th>Coefficient</th>
                            <th>Points</th>
                            <th>Performance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($grades as $grade): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($grade['matiere_libelle']) ?></strong></td>
                            <td style="font-size: 1.2rem; font-weight: bold; color: #4facfe;">
                                <?= $grade['note'] ?>/20
                            </td>
                            <td><?= $grade['coefficient'] ?></td>
                            <td><?= number_format($grade['note'] * $grade['coefficient'], 1) ?></td>
                            <td>
                                <?php if ($grade['note'] >= 18): ?>
                                    <span class="badge badge-success">🌟 Excellent</span>
                                <?php elseif ($grade['note'] >= 16): ?>
                                    <span class="badge badge-info">⭐ Très Bien</span>
                                <?php elseif ($grade['note'] >= 14): ?>
                                    <span class="badge badge-warning">👍 Bien</span>
                                <?php elseif ($grade['note'] >= 12): ?>
                                    <span class="badge badge-info">👌 Assez Bien</span>
                                <?php elseif ($grade['note'] >= 10): ?>
                                    <span class="badge badge-success">✅ Passable</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">❌ Insuffisant</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="content-card">
            <h2>👤 Mes Informations Personnelles</h2>
            <div class="info-grid">
                <div class="info-card">
                    <h4>📋 Données Académiques</h4>
                    <p><strong>Matricule:</strong> <?= htmlspecialchars($student['matricule']) ?></p>
                    <p><strong>Nom:</strong> <?= htmlspecialchars($student['nom']) ?></p>
                    <p><strong>Prénom:</strong> <?= htmlspecialchars($student['prenom']) ?></p>
                    <p><strong>Formation:</strong> <?= htmlspecialchars($student['formation_libelle']) ?></p>
                </div>
                <div class="info-card">
                    <h4>📞 Coordonnées</h4>
                    <p><strong>Téléphone:</strong> <?= htmlspecialchars($student['telephone']) ?></p>
                    <p><strong>Adresse:</strong> <?= htmlspecialchars($student['adresse']) ?></p>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Changement de mot de passe -->
    <div class="modal" id="passwordModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>🔒 Changer le Mot de Passe</h3>
                <button class="close-modal" onclick="closeModal('passwordModal')">&times;</button>
            </div>
            <form id="passwordForm">
                <div class="form-group">
                    <label>🔐 Mot de passe actuel</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>🔒 Nouveau mot de passe</label>
                    <input type="password" name="new_password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>🔒 Confirmer le nouveau mot de passe</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>
                <button type="submit" class="btn-primary">🚀 Modifier le Mot de Passe</button>
            </form>
        </div>
    </div>

    <!-- Modal Paramètres -->
    <div class="modal" id="settingsModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>⚙️ Paramètres du Compte</h3>
                <button class="close-modal" onclick="closeModal('settingsModal')">&times;</button>
            </div>
            <form id="settingsForm">
                <div class="form-group">
                    <label>👤 Nom</label>
                    <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($student['nom']) ?>">
                </div>
                <div class="form-group">
                    <label>👤 Prénom</label>
                    <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($student['prenom']) ?>">
                </div>
                <div class="form-group">
                    <label>📞 Téléphone</label>
                    <input type="tel" name="telephone" class="form-control" value="<?= htmlspecialchars($student['telephone']) ?>">
                </div>
                <div class="form-group">
                    <label>🏠 Adresse</label>
                    <textarea name="adresse" class="form-control" rows="3"><?= htmlspecialchars($student['adresse']) ?></textarea>
                </div>
                <button type="submit" class="btn-primary">💾 Sauvegarder les Paramètres</button>
            </form>
        </div>
    </div>

    <!-- Toast Notifications -->
    <div id="toast" class="toast"></div>

    <script>
        // Gestion des modals
        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Fermer modal en cliquant à l'extérieur
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }

        // Affichage des toasts
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.className = `toast ${type} show`;
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }

        // Téléchargement du relevé
        function downloadTranscript() {
            window.location.href = '/student/download-transcript';
        }

        // Gestion du formulaire de changement de mot de passe
        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('/student/change-password', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    closeModal('passwordModal');
                    this.reset();
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                showToast('Erreur de connexion', 'error');
            });
        });

        // Gestion du formulaire de paramètres
        document.getElementById('settingsForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('/student/update-settings', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    closeModal('settingsModal');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                showToast('Erreur de connexion', 'error');
            });
        });
    </script>
</body>
</html>
