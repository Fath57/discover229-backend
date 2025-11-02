# Discover229 Backend - Architecture Documentation

## Vue d'ensemble

Ce projet Laravel utilise une architecture propre et maintenable basée sur le pattern **Service/Repository** avec une uniformisation complète des retours API.

## Technologies

- **Laravel 12** - Framework PHP
- **Laravel Sanctum** - Authentification API pour SPA
- **Swagger/OpenAPI** - Documentation API interactive
- **PHP 8.2+** - Langage de programmation

## Architecture

### 1. Pattern Service/Repository

#### Structure des dossiers

```
app/
├── Repositories/           # Couche d'accès aux données
│   ├── BaseRepository.php
│   └── User/
│       └── UserRepository.php
├── Services/              # Couche de logique métier
│   ├── BaseService.php
│   └── User/
│       └── UserService.php
├── Http/
│   ├── Controllers/Api/   # Contrôleurs API
│   │   ├── AuthController.php
│   │   └── UserController.php
│   ├── Requests/          # Form Requests pour validation
│   │   └── User/
│   │       ├── StoreUserRequest.php
│   │       └── UpdateUserRequest.php
│   └── Resources/         # Transformateurs de données
│       └── UserResource.php
└── Traits/
    └── ApiResponse.php    # Trait pour uniformiser les réponses API
```

#### BaseRepository

Classe abstraite contenant les méthodes CRUD de base :
- `all()` - Récupérer tous les enregistrements
- `paginate()` - Pagination
- `find()` - Trouver par ID
- `findOrFail()` - Trouver ou échouer
- `findBy()` - Trouver par critères
- `create()` - Créer un enregistrement
- `update()` - Mettre à jour
- `delete()` - Supprimer
- `exists()` - Vérifier l'existence

#### BaseService

Classe abstraite qui encapsule la logique métier et utilise les repositories.

### 2. Système de Retours API Uniformisés

Le trait `ApiResponse` fournit des méthodes standardisées pour tous les types de réponses :

#### Méthodes disponibles

```php
// Réponse de succès
$this->successResponse($data, 'Message', 200);

// Réponse de création
$this->createdResponse($data, 'Resource created successfully');

// Réponse paginée
$this->paginatedResponse($paginator, 'Success');

// Réponse d'erreur
$this->errorResponse('Error message', 400, $errors);

// Réponse de validation
$this->validationErrorResponse($errors, 'Validation failed');

// Réponse non trouvé
$this->notFoundResponse('Resource not found');

// Réponse non autorisé
$this->unauthorizedResponse('Unauthorized');

// Réponse interdite
$this->forbiddenResponse('Forbidden');

// Réponse sans contenu
$this->noContentResponse('Resource deleted successfully');
```

#### Format de réponse standard

**Succès :**
```json
{
  "success": true,
  "message": "Operation successful",
  "data": { ... }
}
```

**Erreur :**
```json
{
  "success": false,
  "message": "Error message",
  "errors": { ... }
}
```

**Paginée :**
```json
{
  "success": true,
  "message": "Data retrieved successfully",
  "data": [ ... ],
  "pagination": {
    "total": 100,
    "per_page": 15,
    "current_page": 1,
    "last_page": 7,
    "from": 1,
    "to": 15
  }
}
```

### 3. Authentification SPA avec Laravel Sanctum

#### Configuration

Les domaines stateful sont configurés dans `.env` :
```env
SANCTUM_STATEFUL_DOMAINS=localhost:3000,localhost:5173
FRONTEND_URL=http://localhost:3000
```

#### Endpoints d'authentification

- `POST /api/register` - Inscription
- `POST /api/login` - Connexion
- `POST /api/logout` - Déconnexion (authentifié)
- `GET /api/user` - Récupérer l'utilisateur authentifié

#### Utilisation du token

Après login/register, un token est retourné :
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": { ... },
    "token": "your-access-token"
  }
}
```

Pour les requêtes authentifiées, ajouter le header :
```
Authorization: Bearer your-access-token
```

### 4. Documentation API avec Swagger

#### Accès à la documentation

Une fois le serveur lancé, accédez à :
```
http://localhost:8000/api/documentation
```

#### Génération de la documentation

```bash
php artisan l5-swagger:generate
```

#### Utilisation dans Swagger UI

1. Cliquez sur "Authorize" en haut à droite
2. Entrez votre token dans le format : `your-token-here`
3. Cliquez sur "Authorize" puis "Close"
4. Toutes vos requêtes incluront maintenant le token

## Exemple d'implémentation : Module User

Le module User démontre l'utilisation complète de l'architecture :

### UserRepository
```php
class UserRepository extends BaseRepository
{
    protected function getModel(): Model
    {
        return new User();
    }

    // Méthodes personnalisées
    public function findByEmail(string $email): ?Model { ... }
}
```

### UserService
```php
class UserService extends BaseService
{
    protected function getRepository(): BaseRepository
    {
        return new UserRepository();
    }

    // Logique métier personnalisée
    public function create(array $data): Model { ... }
}
```

### UserController
```php
class UserController extends Controller
{
    use ApiResponse;

    public function __construct(protected UserService $userService) {}

    public function index(): JsonResponse
    {
        $users = $this->userService->getPaginated(15);
        return $this->paginatedResponse($users, 'Users retrieved');
    }
}
```

## Créer un nouveau module

Pour créer un nouveau module (exemple : Product), suivez ces étapes :

### 1. Créer le Repository

```php
// app/Repositories/Product/ProductRepository.php
namespace App\Repositories\Product;

use App\Models\Product;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class ProductRepository extends BaseRepository
{
    protected function getModel(): Model
    {
        return new Product();
    }

    // Ajoutez vos méthodes personnalisées ici
}
```

### 2. Créer le Service

```php
// app/Services/Product/ProductService.php
namespace App\Services\Product;

use App\Repositories\BaseRepository;
use App\Repositories\Product\ProductRepository;
use App\Services\BaseService;

class ProductService extends BaseService
{
    protected function getRepository(): BaseRepository
    {
        return new ProductRepository();
    }

    // Ajoutez votre logique métier ici
}
```

### 3. Créer les Form Requests

```php
// app/Http/Requests/Product/StoreProductRequest.php
namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            // ... autres règles
        ];
    }
}
```

### 4. Créer la Resource

```php
// app/Http/Resources/ProductResource.php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
```

### 5. Créer le Controller avec Swagger

```php
// app/Http/Controllers/Api/ProductController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Product\ProductService;
use App\Traits\ApiResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Products', description: 'Product management')]
class ProductController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected ProductService $productService
    ) {}

    #[OA\Get(
        path: '/api/products',
        summary: 'Get all products',
        security: [['sanctum' => []]],
        tags: ['Products'],
        // ... autres attributs Swagger
    )]
    public function index()
    {
        $products = $this->productService->getPaginated(15);
        return $this->paginatedResponse($products, 'Products retrieved');
    }

    // ... autres méthodes CRUD
}
```

### 6. Ajouter les routes

```php
// routes/api.php
Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('products', ProductController::class);
});
```

### 7. Générer la documentation

```bash
php artisan l5-swagger:generate
```

## Commandes utiles

```bash
# Installer les dépendances
composer install

# Générer la clé d'application
php artisan key:generate

# Exécuter les migrations
php artisan migrate

# Générer la documentation Swagger
php artisan l5-swagger:generate

# Lancer le serveur de développement
php artisan serve

# Nettoyer le cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

## Tests API avec Postman/Insomnia

### 1. Inscription
```
POST http://localhost:8000/api/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

### 2. Connexion
```
POST http://localhost:8000/api/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}
```

### 3. Requête authentifiée
```
GET http://localhost:8000/api/users
Authorization: Bearer your-token-here
```

## Bonnes pratiques

1. **Toujours utiliser les Form Requests** pour la validation
2. **Utiliser les Resources** pour transformer les données de sortie
3. **Garder la logique métier dans les Services**
4. **Utiliser le trait ApiResponse** pour toutes les réponses
5. **Documenter tous les endpoints** avec les annotations Swagger
6. **Tester les endpoints** via Swagger UI ou Postman

## Support

Pour toute question ou problème, consultez la documentation Laravel ou Swagger.
