<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduGrades - Mon Espace Futuriste</title>
    <link rel="stylesheet" href="/css/ultra-dynamic.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body class="dashboard">
    <!-- Particules d'arrière-plan -->
    <div class="particles" id="particles"></div>
    
    <nav class="navbar">
        <div class="navbar-content">
            <div class="navbar-brand">🚀 EduGrades Étudiant</div>
            <div class="navbar-nav">
                <span class="nav-link">👋 Salut, <?= htmlspecialchars($student['prenom']) ?>!</span>
                <a href="/student/dashboard" class="nav-link">📊 Dashboard</a>
                <a href="/student/grades" class="nav-link">📝 Mes Notes</a>
                <a href="/student/transcript" class="nav-link">📄 Relevé</a>
                <a href="/logout" class="nav-link">🚪 Déconnexion</a>
            </div>
        </div>
    </nav>

    <main class="main-content">
        <div class="page-header">
            <h1>🌟 Mon Espace Personnel</h1>
            <p>Bienvenue dans votre cockpit académique, <?= htmlspecialchars($student['prenom'] . ' ' . $student['nom']) ?>!</p>
        </div>

        <!-- Boutons d'actions rapides -->
        <div class="quick-actions" style="display: flex; gap: 20px; justify-content: center; margin-bottom: 40px;">
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
            <div class="stat-card gradient-primary" style="color: white;">
                <div class="stat-icon">📊</div>
                <div class="stat-number" data-target="<?= number_format($average, 2) ?>">0</div>
                <div class="stat-label">Moyenne Générale</div>
            </div>

            <div class="stat-card gradient-success" style="color: white;">
                <div class="stat-icon">✅</div>
                <div class="stat-number" data-target="<?= $validatedCount ?>">0</div>
                <div class="stat-label">Matières Validées</div>
            </div>

            <div class="stat-card gradient-warning" style="color: white;">
                <div class="stat-icon">📚</div>
                <div class="stat-number" data-target="<?= count($grades) ?>">0</div>
                <div class="stat-label">Notes Disponibles</div>
            </div>

            <div class="stat-card gradient-secondary" style="color: white;">
                <div class="stat-icon">🎯</div>
                <div class="stat-number" data-target="<?= strlen($student['formation_libelle']) ?>">0</div>
                <div class="stat-label"><?= htmlspecialchars($student['formation_libelle']) ?></div>
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
                            <td class="stat-number" style="font-size: 1.5rem; color: var(--neon-blue);">
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
            <div class="stats-grid">
                <div style="background: rgba(255,255,255,0.05); padding: 30px; border-radius: 20px; border: 1px solid rgba(255,255,255,0.1);">
                    <h4 style="color: white; margin-bottom: 20px; font-size: 1.3rem;">📋 Données Académiques</h4>
                    <p style="color: rgba(255,255,255,0.8); margin: 10px 0;"><strong>Matricule:</strong> <?= htmlspecialchars($student['matricule']) ?></p>
                    <p style="color: rgba(255,255,255,0.8); margin: 10px 0;"><strong>Nom:</strong> <?= htmlspecialchars($student['nom']) ?></p>
                    <p style="color: rgba(255,255,255,0.8); margin: 10px 0;"><strong>Prénom:</strong> <?= htmlspecialchars($student['prenom']) ?></p>
                    <p style="color: rgba(255,255,255,0.8); margin: 10px 0;"><strong>Formation:</strong> <?= htmlspecialchars($student['formation_libelle']) ?></p>
                </div>
                <div style="background: rgba(255,255,255,0.05); padding: 30px; border-radius: 20px; border: 1px solid rgba(255,255,255,0.1);">
                    <h4 style="color: white; margin-bottom: 20px; font-size: 1.3rem;">📞 Coordonnées</h4>
                    <p style="color: rgba(255,255,255,0.8); margin: 10px 0;"><strong>Téléphone:</strong> <?= htmlspecialchars($student['telephone']) ?></p>
                    <p style="color: rgba(255,255,255,0.8); margin: 10px 0;"><strong>Adresse:</strong> <?= htmlspecialchars($student['adresse']) ?></p>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Changement de mot de passe -->
    <div class="form-modal" id="passwordModal">
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
    <div class="form-modal" id="settingsModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>⚙️ Paramètres du Compte</h3>
                <button class="close-modal" onclick="closeModal('settingsModal')">&times;</button>
            </div>
            <form id="settingsForm">
                <div class="form-group">
                    <label>📞 Téléphone</label>
                    <input type="tel" name="telephone" class="form-control" value="<?= htmlspecialchars($student['telephone']) ?>">
                </div>
                <div class="form-group">
                    <label>🏠 Adresse</label>
                    <textarea name="adresse" class="form-control" rows="3"><?= htmlspecialchars($student['adresse']) ?></textarea>
                </div>
                <div class="form-group">
                    <label>🔔 Notifications</label>
                    <select name="notifications" class="form-control">
                        <option value="1">Activées</option>
                        <option value="0">Désactivées</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary">💾 Sauvegarder les Paramètres</button>
            </form>
        </div>
    </div>

    <!-- Toast Notifications -->
    <div id="toast" class="toast"></div>

    <script src="/js/ultra-dynamic.js"></script>
    <script>
    // Script spécifique au dashboard étudiant
    document.addEventListener('DOMContentLoaded', function() {
        // Les effets sont maintenant gérés par ultra-dynamic.js
        console.log('Dashboard Étudiant chargé avec succès');
    });
    </script>
</body>
</html>
