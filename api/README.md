# 🚀 API REST - Système de Gestion des Notes

Une API REST complète pour la gestion des étudiants, notes et formations avec authentification JWT, rate limiting et documentation interactive.

## ✨ Fonctionnalités

### 🔐 Authentification & Sécurité
- **JWT (JSON Web Tokens)** pour l'authentification
- **Refresh tokens** pour le renouvellement automatique
- **Rate limiting** (100 requêtes/heure par IP)
- **Validation des données** côté serveur
- **Gestion des permissions** (Admin/Student)

### 📊 Endpoints Disponibles

#### Authentification
- `POST /auth/login` - Connexion et obtention du token
- `POST /auth/refresh` - Renouvellement du token

#### Étudiants
- `GET /api/v1/students` - Liste paginée des étudiants
- `GET /api/v1/students/{matricule}` - Détails d'un étudiant
- `POST /api/v1/students` - Créer un étudiant (admin)
- `PUT /api/v1/students/{matricule}` - Modifier un étudiant (admin)
- `DELETE /api/v1/students/{matricule}` - Supprimer un étudiant (admin)

#### Notes
- `GET /api/v1/students/{matricule}/grades` - Notes d'un étudiant
- `GET /api/v1/grades` - Toutes les notes (paginées)
- `POST /api/v1/grades` - Créer une note (admin)
- `PUT /api/v1/grades/{id}` - Modifier une note (admin)
- `DELETE /api/v1/grades/{id}` - Supprimer une note (admin)

#### Formations
- `GET /api/v1/formations` - Liste des formations
- `POST /api/v1/formations` - Créer une formation (admin)

#### Statistiques
- `GET /api/v1/stats/overview` - Statistiques générales
- `GET /api/v1/stats/students/{matricule}` - Stats d'un étudiant

## 🛠️ Installation

1. **Configurer la base de données** dans `index.php`:
\`\`\`php
$config = [
    'host' => 'localhost',
    'dbname' => 'student_grades_system',
    'username' => 'root',
    'password' => '',
    'jwt_secret' => 'your-super-secret-jwt-key-change-in-production'
];
\`\`\`

2. **Créer les tables** en exécutant le script SQL fourni

3. **Configurer le serveur web** pour pointer vers le dossier `api/`

4. **Tester l'API** avec le client de test: `/api/test-client.html`

## 📖 Documentation

- **Documentation interactive**: `/api/docs.html`
- **Client de test**: `/api/test-client.html`
- **Spécification OpenAPI**: `/api/docs` (endpoint JSON)

## 🔑 Authentification

### Obtenir un token
\`\`\`bash
curl -X POST http://votre-domaine.com/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"login": "admin@school.com", "password": "password"}'
\`\`\`

### Utiliser le token
\`\`\`bash
curl -X GET http://votre-domaine.com/api/v1/students \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
\`\`\`

## 📝 Exemples d'utilisation

### Créer un étudiant
\`\`\`javascript
const response = await fetch('/api/v1/students', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': 'Bearer ' + token
  },
  body: JSON.stringify({
    matricule: 'ETU004',
    nom: 'Dupont',
    prenom: 'Jean',
    email: 'jean.dupont@email.com',
    formation_id: 1
  })
});
\`\`\`

### Ajouter une note
\`\`\`javascript
const response = await fetch('/api/v1/grades', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': 'Bearer ' + token
  },
  body: JSON.stringify({
    matricule: 'ETU001',
    matiere_code: 'INFO101',
    note: 15.5
  })
});
\`\`\`

### Récupérer les statistiques
\`\`\`javascript
const response = await fetch('/api/v1/stats/overview', {
  headers: {
    'Authorization': 'Bearer ' + token
  }
});

const stats = await response.json();
console.log(stats.data);
\`\`\`

## 🚦 Codes de réponse

- **200** - Succès
- **201** - Créé avec succès
- **400** - Requête invalide
- **401** - Non authentifié
- **403** - Accès interdit
- **404** - Ressource non trouvée
- **409** - Conflit (ressource existante)
- **429** - Limite de taux dépassée
- **500** - Erreur serveur

## 🔧 Configuration avancée

### Rate Limiting
Modifiez la limite dans `index.php`:
\`\`\`php
'api_rate_limit' => 100 // requêtes par heure
\`\`\`

### JWT Secret
**Important**: Changez la clé secrète en production:
\`\`\`php
'jwt_secret' => 'your-super-secret-jwt-key-change-in-production'
\`\`\`

### CORS
Les headers CORS sont configurés pour accepter toutes les origines. Modifiez selon vos besoins:
\`\`\`php
header('Access-Control-Allow-Origin: *'); // Changez * par votre domaine
\`\`\`

## 🧪 Tests

Utilisez le client de test intégré à `/api/test-client.html` pour tester tous les endpoints de manière interactive.

## 📊 Monitoring

L'API inclut des headers de rate limiting dans chaque réponse:
- `X-RateLimit-Limit`: Limite par heure
- `X-RateLimit-Remaining`: Requêtes restantes
- `X-RateLimit-Reset`: Timestamp de reset

## 🔒 Sécurité

- Validation stricte des données d'entrée
- Protection contre les injections SQL avec PDO
- Hashage sécurisé des mots de passe
- Tokens JWT avec expiration
- Rate limiting par IP
- Headers de sécurité CORS

## 🚀 Déploiement

1. Uploadez les fichiers sur votre serveur
2. Configurez la base de données
3. Modifiez les paramètres de sécurité
4. Testez avec le client intégré
5. Intégrez dans vos applications

L'API est maintenant prête pour les intégrations externes ! 🎉
