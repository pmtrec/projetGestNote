<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduGrades - Dashboard Admin</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        .btn-danger {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
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
            color: #667eea;
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
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
            <div class="navbar-brand">🚀 EduGrades Admin</div>
            <div class="navbar-nav">
                <a href="/admin/dashboard" class="nav-link">📊 Dashboard</a>
                <a href="/logout" class="nav-link">🚪 Déconnexion</a>
            </div>
        </div>
    </nav>

    <main class="main-content">
        <div class="page-header">
            <h1>🌟 Centre de Contrôle Administrateur</h1>
            <p>Tableau de bord pour la gestion académique</p>
        </div>

        <div class="quick-actions">
            <button class="btn btn-success" onclick="openModal('studentModal')">
                ➕ Inscrire Étudiant
            </button>
            <button class="btn btn-warning" onclick="openModal('formationModal')">
                🎓 Ajouter Formation
            </button>
            <button class="btn btn-info" onclick="openModal('gradeModal')">
                📝 Ajouter Note
            </button>
            <button class="btn btn-danger" onclick="openModal('adminModal')">
                👨‍💼 Nouvel Admin
            </button>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-number"><?= $stats['total_students'] ?></div>
                <div class="stat-label">Étudiants Inscrits</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">📝</div>
                <div class="stat-number"><?= $stats['total_grades'] ?></div>
                <div class="stat-label">Notes Enregistrées</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">🎓</div>
                <div class="stat-number"><?= $stats['total_formations'] ?></div>
                <div class="stat-label">Formations Actives</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">📊</div>
                <div class="stat-number"><?= number_format($stats['average_general'], 2) ?></div>
                <div class="stat-label">Moyenne Générale</div>
            </div>
        </div>

        <div class="content-card">
            <h2>🏆 Étudiants Récemment Inscrits</h2>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Matricule</th>
                            <th>Nom Complet</th>
                            <th>Formation</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($students, 0, 5) as $student): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($student['matricule']) ?></strong></td>
                            <td><?= htmlspecialchars($student['prenom'] . ' ' . $student['nom']) ?></td>
                            <td><?= htmlspecialchars($student['formation_libelle'] ?? 'Non assigné') ?></td>
                            <td><span class="badge badge-success">✅ Actif</span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="content-card">
            <h2>🌟 Meilleures Performances</h2>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Étudiant</th>
                            <th>Matière</th>
                            <th>Note</th>
                            <th>Performance</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $topGrades = array_filter($grades, function($grade) {
                            return $grade['note'] >= 16;
                        });
                        usort($topGrades, function($a, $b) {
                            return $b['note'] <=> $a['note'];
                        });
                        ?>
                        <?php foreach (array_slice($topGrades, 0, 5) as $grade): ?>
                        <tr>
                            <td><?= htmlspecialchars($grade['prenom'] . ' ' . $grade['nom']) ?></td>
                            <td><?= htmlspecialchars($grade['matiere_libelle']) ?></td>
                            <td><strong><?= $grade['note'] ?>/20</strong></td>
                            <td>
                                <?php if ($grade['note'] >= 18): ?>
                                    <span class="badge badge-success">🌟 Excellent</span>
                                <?php elseif ($grade['note'] >= 16): ?>
                                    <span class="badge badge-info">⭐ Très bien</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-warning" onclick="editGrade(<?= $grade['id'] ?>, <?= $grade['note'] ?>)">
                                    ✏️ Modifier
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Modals -->
    <!-- Modal Inscription Étudiant -->
    <div class="modal" id="studentModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>➕ Inscrire un Nouvel Étudiant</h3>
                <button class="close-modal" onclick="closeModal('studentModal')">&times;</button>
            </div>
            <form id="studentForm">
                <div class="form-group">
                    <label>🆔 Matricule</label>
                    <input type="text" name="matricule" class="form-control" placeholder="ETU004" required>
                </div>
                <div class="form-group">
                    <label>👤 Nom</label>
                    <input type="text" name="nom" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>👤 Prénom</label>
                    <input type="text" name="prenom" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>🏠 Adresse</label>
                    <input type="text" name="adresse" class="form-control">
                </div>
                <div class="form-group">
                    <label>📞 Téléphone</label>
                    <input type="tel" name="telephone" class="form-control">
                </div>
                <div class="form-group">
                    <label>🎓 Formation</label>
                    <select name="formation_id" class="form-control" required>
                        <option value="">Choisir une formation</option>
                        <?php foreach ($formations as $formation): ?>
                        <option value="<?= $formation['id'] ?>"><?= htmlspecialchars($formation['libelle']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn-primary">🚀 Inscrire l'Étudiant</button>
            </form>
        </div>
    </div>

    <!-- Modal Formation -->
    <div class="modal" id="formationModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>🎓 Ajouter une Formation</h3>
                <button class="close-modal" onclick="closeModal('formationModal')">&times;</button>
            </div>
            <form id="formationForm">
                <div class="form-group">
                    <label>📚 Libellé de la Formation</label>
                    <input type="text" name="libelle" class="form-control" placeholder="Ex: Intelligence Artificielle" required>
                </div>
                <button type="submit" class="btn-primary">🚀 Créer la Formation</button>
            </form>
        </div>
    </div>

    <!-- Modal Note -->
    <div class="modal" id="gradeModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>📝 Ajouter une Note</h3>
                <button class="close-modal" onclick="closeModal('gradeModal')">&times;</button>
            </div>
            <form id="gradeForm">
                <div class="form-group">
                    <label>👤 Étudiant</label>
                    <select name="matricule" class="form-control" required>
                        <option value="">Choisir un étudiant</option>
                        <?php foreach ($students as $student): ?>
                        <option value="<?= $student['matricule'] ?>">
                            <?= $student['matricule'] ?> - <?= $student['prenom'] . ' ' . $student['nom'] ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>📚 Matière</label>
                    <select name="matiere_code" class="form-control" required>
                        <option value="">Choisir une matière</option>
                        <?php foreach ($matieres as $matiere): ?>
                        <option value="<?= $matiere['code'] ?>"><?= $matiere['code'] ?> - <?= htmlspecialchars($matiere['libelle']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>📊 Note (sur 20)</label>
                    <input type="number" name="note" class="form-control" min="0" max="20" step="0.1" required>
                </div>
                <button type="submit" class="btn-primary">🚀 Ajouter la Note</button>
            </form>
        </div>
    </div>

    <!-- Modal Administrateur -->
    <div class="modal" id="adminModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>👨‍💼 Ajouter un Administrateur</h3>
                <button class="close-modal" onclick="closeModal('adminModal')">&times;</button>
            </div>
            <form id="adminForm">
                <div class="form-group">
                    <label>📧 Email</label>
                    <input type="email" name="email" class="form-control" placeholder="admin@school.com" required>
                </div>
                <div class="form-group">
                    <label>🔒 Mot de passe</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>🔒 Confirmer le mot de passe</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>
                <button type="submit" class="btn-primary">🚀 Créer l'Administrateur</button>
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

        // Gestion du formulaire étudiant
        document.getElementById('studentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('/admin/students/create', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    closeModal('studentModal');
                    this.reset();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                showToast('Erreur de connexion', 'error');
            });
        });

        // Gestion du formulaire formation
        document.getElementById('formationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('/admin/formations/create', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    closeModal('formationModal');
                    this.reset();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                showToast('Erreur de connexion', 'error');
            });
        });

        // Gestion du formulaire note
        document.getElementById('gradeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('/admin/grades/create', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    closeModal('gradeModal');
                    this.reset();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                showToast('Erreur de connexion', 'error');
            });
        });

        // Gestion du formulaire admin
        document.getElementById('adminForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('/admin/create-admin', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    closeModal('adminModal');
                    this.reset();
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                showToast('Erreur de connexion', 'error');
            });
        });

        // Fonction pour modifier une note
        function editGrade(id, currentNote) {
            const newNote = prompt('Nouvelle note (0-20):', currentNote);
            if (newNote !== null && newNote >= 0 && newNote <= 20) {
                const formData = new FormData();
                formData.append('id', id);
                formData.append('note', newNote);
                
                fetch('/admin/grades/update', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showToast(data.message, 'error');
                    }
                })
                .catch(error => {
                    showToast('Erreur de connexion', 'error');
                });
            }
        }
    </script>
</body>
</html>
