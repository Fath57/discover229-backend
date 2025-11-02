# 📮 Discover229 API - Postman Collection Documentation

> **API Documentation via Postman Collection uniquement**
> Swagger a été retiré. La documentation complète se trouve dans `POSTMAN_COLLECTION.json`

## 🚀 Quick Start

### 1. Importer la Collection dans Postman

```
1. Ouvrir Postman
2. Cliquer sur "Import" (en haut à gauche)
3. Sélectionner "Upload Files"
4. Choisir: discover229-backend/POSTMAN_COLLECTION.json
5. Cliquer "Import"
```

### 2. Configuration

La collection a une variable `base_url` pré-configurée à `http://localhost:8000`

**Pour changer:**
1. Clic droit sur la collection → "Edit"
2. Aller dans l'onglet "Variables"
3. Modifier `base_url` selon votre environnement

## 📚 Structure de la Collection

```
Discover229 - API Complete
│
├── 🔐 Authentication - Session Initialization
│   └── 1️⃣ GET /sanctum/csrf-cookie
│
├── 🔐 Authentication - Login/Register
│   ├── 2️⃣ POST /api/auth/login
│   └── 3️⃣ POST /api/auth/register
│
├── 👤 User - Protected Routes
│   ├── 4️⃣ GET /api/user
│   └── 5️⃣ POST /api/auth/logout
│
├── 👥 Users - CRUD
│   ├── GET /api/users
│   ├── GET /api/users/{id}
│   ├── POST /api/users
│   ├── PUT /api/users/{id}
│   └── DELETE /api/users/{id}
│
├── 🔑 Roles - CRUD
│   ├── GET /api/roles
│   ├── GET /api/roles/{id}
│   ├── POST /api/roles
│   ├── POST /api/roles/{id}/permissions
│   └── GET /api/roles/{id}/users
│
├── 🔒 Permissions
│   ├── GET /api/permissions
│   └── GET /api/permissions/grouped
│
├── 📦 Modules
│   └── GET /api/modules
│
└── 👨‍💼 User Roles Management
    ├── POST /api/users/{userId}/assign-role
    └── DELETE /api/users/{userId}/remove-role
```

## 🔑 Flux d'Authentification Complet

### Étape 1: Initialiser la Session
```
GET /sanctum/csrf-cookie
```
✅ Cela initialise votre session et définit les cookies CSRF
- Pas de body required
- Réponse: 200 OK

### Étape 2: Login (se connecter)
```
POST /api/auth/login
Body: {
  "email": "admin@discover229.com",
  "password": "password"
}
```
✅ Cela authentifie l'utilisateur
- Réponse: 200 + User data
- Variables sauvegardées: `user-id`, `user-email`

### Étape 3: Utiliser les endpoints protégés
```
GET /api/user
GET /api/users
POST /api/users
... tous les endpoints protégés
```
✅ Les cookies sont envoyés automatiquement par Postman
- Vous êtes maintenant authentifié
- Tous les endpoints protégés fonctionnent

### Étape 4: Logout (se déconnecter)
```
POST /api/auth/logout
```
✅ Termine votre session
- Variables: `user-id` et `user-email` sont effacées

## 🧪 Tests Automatisés

Chaque endpoint a des tests Postman qui:
1. ✅ Vérifient le code HTTP de réponse
2. ✅ Valident la structure JSON
3. ✅ Sauvegardent les variables importantes
4. ✅ Affichent des messages de succès/erreur

**Pour voir les résultats des tests:**
1. Exécuter une requête
2. Cliquer sur l'onglet "Tests" en bas

## 📝 Exemples d'Utilisation

### Login et récupérer le user courant
```
1. GET /sanctum/csrf-cookie
2. POST /api/auth/login (avec credentials)
3. GET /api/user
```

### Créer un nouvel utilisateur
```
1. GET /sanctum/csrf-cookie
2. POST /api/auth/register
```

### Gérer les rôles
```
1. GET /api/roles (lister)
2. POST /api/roles (créer)
3. POST /api/roles/{id}/permissions (assigner permissions)
```

### Assigner un rôle à un utilisateur
```
POST /api/users/{userId}/assign-role
Body: { "role_id": 2 }
```

## 🌍 Variables Collection

Les variables suivantes sont disponibles:

| Variable | Valeur | Modifiable |
|----------|--------|-----------|
| `base_url` | `http://localhost:8000` | ✅ Oui |
| `user-id` | Sauvegardé au login | ❌ Auto |
| `user-email` | Sauvegardé au login | ❌ Auto |

**Utiliser une variable:**
```
{{base_url}}/api/users/{{user-id}}
```

## 🔐 Sécurité

✅ **HTTP-only Cookies**: Postman gère automatiquement les cookies (non accessibles via JavaScript)
✅ **Session CSRF**: Initialisée automatiquement via `/sanctum/csrf-cookie`
✅ **Rate Limiting**: 5 tentatives de login max par IP
✅ **Regeneration**: Session régénérée après chaque login

## 🐛 Dépannage

### Problème: "401 Unauthenticated"
**Solutions:**
1. Assurez-vous d'avoir exécuté `/sanctum/csrf-cookie` en premier
2. Exécutez `/api/auth/login` avec les bons identifiants
3. Vérifiez que les cookies sont stockés (voir "Cookies" dans Postman)

### Problème: "Credentials Invalid"
**Solutions:**
1. Vérifiez l'email: `admin@discover229.com`
2. Vérifiez le password: `password`
3. Assurez-vous que l'utilisateur existe en base de données

### Problème: Les variables ne se sauvegardent pas
**Solutions:**
1. Cliquez sur l'onglet "Tests" pour voir les scripts
2. Vérifiez que la réponse est en JSON valide
3. Relancez la requête pour réessayer

## 🚀 Utilisation Avancée

### Exporter la Collection
```
Clic droit sur la collection → Export
Choisir le format: JSON v2.1
```

### Utiliser avec Newman (CLI)
```bash
# Installer Newman
npm install -g newman

# Exécuter la collection
newman run POSTMAN_COLLECTION.json
```

### Environnements Multiples
Créer plusieurs fichiers d'env:
- `discover229-dev.json` (localhost:8000)
- `discover229-prod.json` (api.discover229.com)

Puis dans Postman, choisir l'environnement actif.

### Tests en CI/CD
```bash
newman run POSTMAN_COLLECTION.json \
  --environment discover229-dev.json \
  --reporters cli,json \
  --reporter-json-export report.json
```

## 📖 Endpoints Par Catégorie

### Authentication (Public)
- `POST /api/auth/login`
- `POST /api/auth/register`
- `GET /sanctum/csrf-cookie`

### User (Protégé)
- `GET /api/user` - User courant
- `POST /api/auth/logout`

### Users (Protégé)
- `GET /api/users` - Lister tous
- `GET /api/users/{id}` - Détails
- `POST /api/users` - Créer
- `PUT /api/users/{id}` - Modifier
- `DELETE /api/users/{id}` - Supprimer

### Roles (Protégé)
- `GET /api/roles` - Lister tous
- `GET /api/roles/{id}` - Détails
- `POST /api/roles` - Créer
- `POST /api/roles/{id}/permissions` - Assigner permissions
- `GET /api/roles/{id}/users` - Users par rôle

### Permissions (Protégé)
- `GET /api/permissions` - Lister toutes
- `GET /api/permissions/grouped` - Groupées par module

### Modules (Protégé)
- `GET /api/modules` - Lister tous

### User Roles (Protégé)
- `POST /api/users/{userId}/assign-role` - Assigner
- `DELETE /api/users/{userId}/remove-role` - Retirer

## 💡 Tips & Tricks

1. **Dupliquer une requête**: Clic droit → "Duplicate"
2. **Partager la collection**: Clic droit → "Share collection"
3. **Visualiser les réponses**: Clic sur l'onglet "Body" → "Preview"
4. **Debugging**: Ouvrir "Postman Console" (Ctrl+Alt+C)
5. **Favoris**: ⭐ cliquer sur la requête pour l'ajouter aux favoris

## 📞 Support

Pour des questions:
1. Vérifiez la documentation: [Postman Learning Center](https://learning.postman.com)
2. Consultez: [Laravel Sanctum Docs](https://laravel.com/docs/12.x/sanctum)
3. Explorez les tests: Clic sur l'onglet "Tests" de chaque requête

---

**Dernière mise à jour:** Janvier 2025
**Version:** 1.0.0
