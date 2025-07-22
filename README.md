# EduGrades - Système de Gestion des Notes Étudiantes

## Description
EduGrades est une plateforme moderne et dynamique de gestion des notes étudiantes développée en PHP avec une architecture MVC respectant les principes SOLID.

## Fonctionnalités

### Pour les Administrateurs
- ✅ **Inscription d'étudiants** - Créer de nouveaux comptes étudiants
- ✅ **Gestion des formations** - Ajouter et gérer les formations
- ✅ **Gestion des notes** - Ajouter et modifier les notes
- ✅ **Création d'administrateurs** - Ajouter de nouveaux administrateurs
- ✅ **Tableau de bord dynamique** - Statistiques en temps réel

### Pour les Étudiants
- ✅ **Consultation des notes** - Voir toutes ses notes avec détails
- ✅ **Changement de mot de passe** - Sécurité du compte
- ✅ **Mise à jour des paramètres** - Modifier ses informations personnelles
- ✅ **Téléchargement du relevé** - Générer un relevé de notes au format HTML
- ✅ **Tableau de bord personnalisé** - Vue d'ensemble de ses performances

## Architecture

### Pattern MVC
- **Models** : Entités métier (User, Student, Grade, Formation)
- **Views** : Interface utilisateur avec design moderne
- **Controllers** : Logique de contrôle (AuthController, AdminController, StudentController)

### Principes SOLID
- **S** - Single Responsibility : Chaque classe a une responsabilité unique
- **O** - Open/Closed : Extensible sans modification
- **L** - Liskov Substitution : Interfaces respectées
- **I** - Interface Segregation : Interfaces spécialisées
- **D** - Dependency Inversion : Injection de dépendances

### Services
- **AuthService** : Gestion de l'authentification
- **GradeCalculatorService** : Calculs des moyennes
- **PDFService** : Génération des relevés

## Installation

1. **Cloner le projet**
\`\`\`bash
git clone [url-du-projet]
cd studentgradessystem
\`\`\`

2. **Configuration de la base de données**
- Créer une base de données MySQL
- Importer le fichier `database/schema.sql`
- Modifier la configuration dans `src/Config/Database.php`

3. **Configuration du serveur web**
- Pointer le document root vers le dossier racine
- Activer la réécriture d'URL (mod_rewrite pour Apache)

4. **Permissions**
\`\`\`bash
chmod 755 public/downloads/
\`\`\`

## Comptes de test

### Administrateur
- **Email** : admin@school.com
- **Mot de passe** : password

### Étudiants
- **Matricules** : ETU001, ETU002, ETU003
- **Mot de passe** : password

## Design

### Couleurs principales
- **Primaire** : Dégradé bleu (#667eea → #764ba2)
- **Succès** : Dégradé cyan (#4facfe → #00f2fe)
- **Attention** : Dégradé rose (#f093fb → #f5576c)
- **Danger** : Dégradé rouge (#ff6b6b → #ee5a52)

### Caractéristiques du design
- Interface moderne avec effets de glassmorphism
- Animations fluides et transitions
- Design responsive pour mobile et desktop
- Particules animées en arrière-plan
- Notifications toast dynamiques

## Fonctionnalités dynamiques

### AJAX et JavaScript
- Soumission de formulaires sans rechargement
- Notifications en temps réel
- Modals interactives
- Validation côté client

### Sécurité
- Hashage des mots de passe avec password_hash()
- Protection contre les injections SQL avec PDO
- Validation des données côté serveur
- Sessions sécurisées

## Structure des fichiers

\`\`\`
studentgradessystem/
├── index.php                 # Point d'entrée principal
├── src/
│   ├── Config/
│   │   └── Database.php      # Configuration BDD
│   ├── Models/               # Entités métier
│   ├── Controllers/          # Contrôleurs
│   ├── Services/            # Services métier
│   └── Repositories/        # Accès aux données
├── views/                   # Templates
│   ├── auth/
│   ├── admin/
│   └── student/
├── public/
│   └── downloads/           # Fichiers générés
└── database/
    └── schema.sql           # Structure BDD
\`\`\`

## API Endpoints

### Authentification
- `POST /` - Connexion
- `GET /logout` - Déconnexion

### Administration
- `GET /admin/dashboard` - Tableau de bord admin
- `POST /admin/students/create` - Créer un étudiant
- `POST /admin/formations/create` - Créer une formation
- `POST /admin/grades/create` - Ajouter une note
- `POST /admin/grades/update` - Modifier une note
- `POST /admin/create-admin` - Créer un administrateur

### Étudiant
- `GET /student/dashboard` - Tableau de bord étudiant
- `POST /student/change-password` - Changer mot de passe
- `POST /student/update-settings` - Mettre à jour profil
- `GET /student/download-transcript` - Télécharger relevé

## Technologies utilisées

- **Backend** : PHP 7.4+, PDO MySQL
- **Frontend** : HTML5, CSS3, JavaScript ES6+
- **Base de données** : MySQL 5.7+
- **Architecture** : MVC, SOLID, Repository Pattern
- **Sécurité** : Password hashing, Prepared statements
- **Design** : CSS Grid, Flexbox, Animations CSS

## Contribution

1. Fork le projet
2. Créer une branche feature (`git checkout -b feature/AmazingFeature`)
3. Commit les changements (`git commit -m 'Add AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

## Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

## Support

Pour toute question ou problème, ouvrir une issue sur GitHub ou contacter l'équipe de développement.

---

**EduGrades** - Révolutionner la gestion académique avec style et efficacité ! 🚀
\`\`\`
