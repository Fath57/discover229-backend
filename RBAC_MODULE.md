# Module RBAC - Discover229

Documentation complète du module de gestion des rôles et permissions avec Spatie Laravel Permission.

## Vue d'ensemble

Le module RBAC (Role-Based Access Control) permet de gérer les rôles et permissions des utilisateurs dans l'application Discover229. Il utilise le package **Spatie Laravel Permission** pour une gestion flexible et puissante des autorisations.

## Rôles par défaut

Le système inclut 3 rôles prédéfinis :

### 1. **admin_system**
- **Description** : Administrateur système avec accès complet
- **Permissions** : Toutes les permissions (42 au total)
- **Cas d'usage** : Gestion globale de la plateforme

### 2. **admin_agency**
- **Description** : Propriétaire d'agence de tourisme
- **Permissions principales** :
  - Gestion de sa propre agence
  - Gestion des offres/circuits
  - Réception et réponse aux messages
  - Consultation des avis
  - Accès au tableau de bord agence
  - Utilisation des fonctionnalités IA
- **Cas d'usage** : Gérant d'une agence de tourisme

### 3. **user**
- **Description** : Visiteur/touriste standard
- **Permissions principales** :
  - Consultation des agences et offres
  - Création et gestion de ses avis
  - Envoi de messages aux agences
  - Utilisation du chatbot IA
- **Cas d'usage** : Utilisateur visitant le site

## Organisation des Permissions par Module

Les 42 permissions sont organisées en 10 modules :

### Module Users
```
users.view           - Voir les utilisateurs
users.create         - Créer des utilisateurs
users.update         - Modifier des utilisateurs
users.delete         - Supprimer des utilisateurs
users.manage-roles   - Gérer les rôles des utilisateurs
```

### Module Agencies
```
agencies.view        - Voir les agences
agencies.create      - Créer des agences
agencies.update      - Modifier des agences
agencies.delete      - Supprimer des agences
agencies.approve     - Approuver des agences
agencies.manage-own  - Gérer sa propre agence
```

### Module Offers
```
offers.view          - Voir les offres
offers.create        - Créer des offres
offers.update        - Modifier des offres
offers.delete        - Supprimer des offres
offers.manage-own    - Gérer ses propres offres
```

### Module Reviews
```
reviews.view         - Voir les avis
reviews.create       - Créer des avis
reviews.update       - Modifier des avis
reviews.delete       - Supprimer des avis
reviews.moderate     - Modérer les avis
reviews.manage-own   - Gérer ses propres avis
```

### Module Messages
```
messages.view        - Voir les messages
messages.send        - Envoyer des messages
messages.reply       - Répondre aux messages
messages.delete      - Supprimer des messages
messages.manage-own  - Gérer ses propres messages
```

### Module Settings
```
settings.view               - Voir les paramètres
settings.update             - Modifier les paramètres
settings.manage-categories  - Gérer les catégories
settings.manage-regions     - Gérer les régions
```

### Module Roles
```
roles.view           - Voir les rôles
roles.create         - Créer des rôles
roles.update         - Modifier des rôles
roles.delete         - Supprimer des rôles
```

### Module Permissions
```
permissions.view     - Voir les permissions
permissions.assign   - Assigner des permissions
```

### Module Dashboard
```
dashboard.view-admin       - Voir le tableau de bord admin
dashboard.view-agency      - Voir le tableau de bord agence
dashboard.view-statistics  - Voir les statistiques
```

### Module AI
```
ai.chat                - Utiliser le chatbot IA
ai.recommendations     - Utiliser les recommandations IA
```

## API Endpoints

### Gestion des Rôles

#### Lister tous les rôles
```http
GET /api/roles
Authorization: Bearer {token}
```

**Paramètres de requête :**
- `page` (optionnel) : Numéro de page (défaut: 1)
- `per_page` (optionnel) : Éléments par page (défaut: 15)

**Réponse :**
```json
{
  "success": true,
  "message": "Roles retrieved successfully",
  "data": [
    {
      "id": 1,
      "name": "admin_system",
      "guard_name": "web",
      "permissions": [...],
      "permissions_count": 42,
      "created_at": "2024-01-01T00:00:00.000000Z"
    }
  ],
  "pagination": {...}
}
```

#### Voir un rôle
```http
GET /api/roles/{id}
Authorization: Bearer {token}
```

#### Créer un rôle
```http
POST /api/roles
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "moderator",
  "permission_ids": [1, 2, 3, 4, 5]
}
```

**Permission requise :** `roles.create`

#### Modifier un rôle
```http
PUT /api/roles/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "moderator_updated",
  "permission_ids": [1, 2, 3, 4, 5, 6]
}
```

**Permission requise :** `roles.update`

**Note :** Les rôles système (`admin_system`, `admin_agency`, `user`) ne peuvent pas être modifiés.

#### Supprimer un rôle
```http
DELETE /api/roles/{id}
Authorization: Bearer {token}
```

**Permission requise :** `roles.delete`

**Note :** Les rôles système ne peuvent pas être supprimés.

#### Assigner des permissions à un rôle
```http
POST /api/roles/{id}/permissions
Authorization: Bearer {token}
Content-Type: application/json

{
  "permission_ids": [1, 2, 3, 4, 5]
}
```

**Permission requise :** `permissions.assign`

#### Voir les utilisateurs d'un rôle
```http
GET /api/roles/{id}/users
Authorization: Bearer {token}
```

### Gestion des Permissions

#### Lister toutes les permissions
```http
GET /api/permissions
Authorization: Bearer {token}
```

**Réponse :**
```json
{
  "success": true,
  "message": "Permissions retrieved successfully",
  "data": [
    {
      "id": 1,
      "name": "users.view",
      "guard_name": "web",
      "module": "users",
      "action": "view",
      "created_at": "2024-01-01T00:00:00.000000Z"
    }
  ]
}
```

#### Permissions groupées par module
```http
GET /api/permissions/grouped
Authorization: Bearer {token}
```

**Réponse :**
```json
{
  "success": true,
  "message": "Permissions grouped successfully",
  "data": {
    "users": [
      {
        "id": 1,
        "name": "users.view",
        "module": "users",
        "action": "view"
      }
    ],
    "agencies": [...]
  }
}
```

### Gestion des Rôles Utilisateurs

#### Assigner un rôle à un utilisateur
```http
POST /api/users/{userId}/assign-role
Authorization: Bearer {token}
Content-Type: application/json

{
  "role_name": "admin_agency"
}
```

**Permission requise :** `users.manage-roles`

#### Retirer un rôle d'un utilisateur
```http
DELETE /api/users/{userId}/remove-role
Authorization: Bearer {token}
Content-Type: application/json

{
  "role_name": "admin_agency"
}
```

**Permission requise :** `users.manage-roles`

## Utilisation dans le Code

### Vérifier les permissions

#### Dans les contrôleurs
```php
// Vérifier une permission
if ($request->user()->can('agencies.create')) {
    // L'utilisateur peut créer des agences
}

// Vérifier un rôle
if ($request->user()->hasRole('admin_system')) {
    // L'utilisateur est admin système
}

// Vérifier plusieurs permissions (OU)
if ($request->user()->canAny(['agencies.update', 'agencies.delete'])) {
    // L'utilisateur peut modifier OU supprimer
}

// Vérifier plusieurs permissions (ET)
if ($request->user()->hasAllPermissions(['agencies.view', 'agencies.create'])) {
    // L'utilisateur peut voir ET créer
}
```

#### Dans les Form Requests
```php
public function authorize(): bool
{
    return $this->user()->can('agencies.create');
}
```

#### Dans les routes (middleware)
```php
// Vérifier une permission
Route::middleware(['auth:sanctum', 'permission:agencies.create'])
    ->post('/agencies', [AgencyController::class, 'store']);

// Vérifier un rôle
Route::middleware(['auth:sanctum', 'role:admin_system'])
    ->get('/admin/dashboard', [AdminController::class, 'dashboard']);

// Vérifier plusieurs permissions
Route::middleware(['auth:sanctum', 'permission:users.view|users.create'])
    ->group(function () {
        // Routes...
    });
```

#### Dans les vues Blade
```blade
@can('agencies.create')
    <button>Créer une agence</button>
@endcan

@role('admin_system')
    <a href="/admin">Panel Admin</a>
@endrole

@hasanyrole('admin_system|admin_agency')
    <a href="/dashboard">Dashboard</a>
@endhasanyrole
```

### Assigner des rôles et permissions

#### Assigner un rôle
```php
$user = User::find(1);
$user->assignRole('admin_agency');

// Ou avec plusieurs rôles
$user->assignRole(['admin_agency', 'moderator']);
```

#### Retirer un rôle
```php
$user->removeRole('admin_agency');
```

#### Synchroniser les rôles (remplace tous les rôles existants)
```php
$user->syncRoles(['admin_agency']);
```

#### Assigner une permission directement
```php
$user->givePermissionTo('agencies.create');
```

#### Retirer une permission
```php
$user->revokePermissionTo('agencies.create');
```

## Exemples de Scénarios

### Scénario 1 : Inscription d'un utilisateur visiteur
```php
$user = User::create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => Hash::make('password'),
]);

$user->assignRole('user');
```

### Scénario 2 : Création d'une agence et assignation du rôle
```php
$user = User::create([...]);
$agency = Agency::create([
    'name' => 'Discover Benin Tours',
    'owner_id' => $user->id,
    ...
]);

$user->assignRole('admin_agency');
```

### Scénario 3 : Création d'un rôle personnalisé "Modérateur"
```php
$role = Role::create(['name' => 'moderator']);

$role->givePermissionTo([
    'reviews.moderate',
    'messages.view',
    'agencies.approve',
]);

$user->assignRole('moderator');
```

### Scénario 4 : Vérification d'autorisation dans un controller
```php
public function approveAgency(Request $request, $id)
{
    // Vérifier la permission
    if (!$request->user()->can('agencies.approve')) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized'
        ], 403);
    }

    $agency = Agency::findOrFail($id);
    $agency->update(['approved' => true]);

    return response()->json([
        'success' => true,
        'message' => 'Agency approved successfully'
    ]);
}
```

## Tests avec Swagger UI

1. **Connectez-vous** avec un compte admin :
   ```
   POST /api/login
   {
     "email": "admin@discover229.com",
     "password": "password"
   }
   ```

2. **Copiez le token** retourné

3. **Autorisez dans Swagger** : Cliquez sur "Authorize" et collez le token

4. **Testez les endpoints RBAC** :
   - `GET /api/roles` - Voir tous les rôles
   - `GET /api/permissions/grouped` - Voir les permissions par module
   - `POST /api/users/1/assign-role` - Assigner un rôle

## Structure des Fichiers

```
app/
├── Http/
│   ├── Controllers/Api/
│   │   ├── RoleController.php         # Gestion des rôles
│   │   └── PermissionController.php   # Gestion des permissions
│   ├── Requests/Role/
│   │   ├── StoreRoleRequest.php
│   │   ├── UpdateRoleRequest.php
│   │   ├── AssignPermissionsRequest.php
│   │   └── AssignRoleRequest.php
│   └── Resources/Role/
│       ├── RoleResource.php
│       └── PermissionResource.php
├── Models/
│   ├── User.php                       # Trait HasRoles
│   ├── Role.php                       # Schéma Swagger
│   └── Permission.php                 # Schéma Swagger
├── Repositories/Role/
│   └── RoleRepository.php
├── Services/Role/
│   └── RoleService.php
database/
└── seeders/
    └── RolePermissionSeeder.php       # Seeder des rôles/permissions
config/
└── permission.php                      # Configuration Spatie
```

## Commandes Utiles

```bash
# Exécuter les migrations
php artisan migrate

# Exécuter le seeder
php artisan db:seed --class=RolePermissionSeeder

# Nettoyer le cache des permissions
php artisan permission:cache-reset

# Afficher toutes les permissions
php artisan permission:show

# Créer une permission
php artisan permission:create-permission "module.action"

# Créer un rôle
php artisan permission:create-role "role_name"

# Générer la doc Swagger
php artisan l5-swagger:generate
```

## Bonnes Pratiques

1. **Nommer les permissions** : Utilisez le format `module.action` (ex: `users.view`, `agencies.create`)

2. **Vérifier les permissions** plutôt que les rôles dans le code :
   ```php
   // ✅ Bon
   if ($user->can('agencies.create')) { ... }

   // ❌ À éviter
   if ($user->hasRole('admin_agency')) { ... }
   ```

3. **Protéger les rôles système** : Ne permettez pas la modification/suppression des rôles par défaut

4. **Utiliser les Form Requests** pour la validation et l'autorisation

5. **Grouper les permissions** par module pour une meilleure organisation

6. **Documenter** les permissions utilisées dans chaque contrôleur

## Sécurité

- ✅ Toutes les routes RBAC sont protégées par `auth:sanctum`
- ✅ Les Form Requests vérifient les permissions appropriées
- ✅ Les rôles système ne peuvent pas être modifiés/supprimés
- ✅ Validation stricte des données d'entrée
- ✅ Messages d'erreur appropriés (403, 404, 422)

## Extension

Pour ajouter de nouveaux modules avec permissions :

1. **Définir les permissions** dans le seeder
2. **Créer les Form Requests** avec vérification de permission
3. **Protéger les routes** avec middleware
4. **Documenter** dans Swagger

Exemple pour un module "Blog" :
```php
// Dans RolePermissionSeeder.php
'blog' => [
    'blog.view',
    'blog.create',
    'blog.update',
    'blog.delete',
    'blog.publish',
],
```

## Support

Pour toute question ou problème :
- Documentation Spatie : https://spatie.be/docs/laravel-permission
- Documentation complète du projet : `README_ARCHITECTURE.md`
