<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduGrades - Dashboard Admin Futuriste</title>
    <link rel="stylesheet" href="/css/ultra-dynamic.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body class="dashboard">
    <!-- Particules d'arrière-plan -->
    <div class="particles" id="particles"></div>
    
    <nav class="navbar">
        <div class="navbar-content">
            <div class="navbar-brand">🚀 EduGrades Admin</div>
            <div class="navbar-nav">
                <a href="/admin/dashboard" class="nav-link">📊 Dashboard</a>
                <a href="/admin/students" class="nav-link">👥 Étudiants</a>
                <a href="/admin/formations" class="nav-link">🎓 Formations</a>
                <a href="/admin/grades" class="nav-link">📝 Notes</a>
                <a href="/logout" class="nav-link">🚪 Déconnexion</a>
            </div>
        </div>
    </nav>

    <main class="main-content">
        <div class="page-header">
            <h1>🌟 Centre de Contrôle Administrateur</h1>
            <p>Tableau de bord futuriste pour la gestion académique</p>
        </div>

        <!-- Boutons d'actions rapides -->
        <div class="quick-actions" style="display: flex; gap: 20px; justify-content: center; margin-bottom: 40px;">
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
                <div class="stat-number" data-target="<?= $stats['total_students'] ?>">0</div>
                <div class="stat-label">Étudiants Inscrits</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">📝</div>
                <div class="stat-number" data-target="<?= $stats['total_grades'] ?>">0</div>
                <div class="stat-label">Notes Enregistrées</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">📊</div>
                <div class="stat-number" data-target="<?= number_format($stats['average_general'], 2) ?>">0</div>
                <div class="stat-label">Moyenne Générale</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">🎯</div>
                <div class="stat-number" data-target="3">0</div>
                <div class="stat-label">Formations Actives</div>
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
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($students, 0, 5) as $student): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($student['matricule']) ?></strong></td>
                            <td><?= htmlspecialchars($student['prenom'] . ' ' . $student['nom']) ?></td>
                            <td><?= htmlspecialchars($student['formation_libelle'] ?? 'Non assigné') ?></td>
                            <td><span class="badge badge-success">✅ Actif</span></td>
                            <td>
                                <button class="btn btn-info" onclick="editStudent('<?= $student['matricule'] ?>')">
                                    ✏️ Modifier
                                </button>
                            </td>
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
    <div class="form-modal" id="studentModal">
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
                        <option value="1">Informatique</option>
                        <option value="2">Mathématiques</option>
                        <option value="3">Physique</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary">🚀 Inscrire l'Étudiant</button>
            </form>
        </div>
    </div>

    <!-- Modal Formation -->
    <div class="form-modal" id="formationModal">
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
    <div class="form-modal" id="gradeModal">
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
                        <option value="INFO101">INFO101 - Programmation Web</option>
                        <option value="INFO102">INFO102 - Base de Données</option>
                        <option value="INFO103">INFO103 - Algorithmes</option>
                        <option value="MATH101">MATH101 - Analyse</option>
                        <option value="MATH102">MATH102 - Algèbre</option>
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
    <div class="form-modal" id="adminModal">
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

    <script src="/js/ultra-dynamic.js"></script>
    <script>
    // Script spécifique au dashboard admin
    document.addEventListener('DOMContentLoaded', function() {
        // Les effets sont maintenant gérés par ultra-dynamic.js
        console.log('Dashboard Admin chargé avec succès');
    });
    </script>
</body>
</html>
