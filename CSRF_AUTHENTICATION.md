# Authentification Stateful avec Sanctum et CSRF

## Problem: Token Mismatch Error

Quand vous utilisiez `statefulApi()` dans Laravel, le middleware CSRF était appliqué à toutes les routes de l'API. Swagger et les clients API ne pouvaient pas envoyer les requêtes correctement.

## Solution: Exemptions CSRF pour les routes publiques

### 1. Configuration du Middleware CSRF

Le middleware CSRF a été configuré pour exempter toutes les routes API (`api/*`) car :
- L'API utilise Sanctum avec des tokens
- Les routes publiques (login, register) n'ont pas besoin de vérification CSRF
- Les routes protégées utilisent les tokens Sanctum, pas les sessions

```php
// app/Http/Middleware/VerifyCsrfToken.php
protected $except = [
    'api/*',
];
```

### 2. Stateful API activé

Le middleware `statefulApi()` est activé dans `bootstrap/app.php` pour supporter les SPAs selon les recommandations de Sanctum:

```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->statefulApi();
})
```

### 3. CSRF Token Endpoint (Optionnel)

Un endpoint GET `/api/csrf-token` est disponible pour les clients qui veulent récupérer le token manuellement:

```bash
GET /api/csrf-token
```

Response:
```json
{
  "csrf_token": "eyJpdiI6IkJnRWg2..."
}
```

### 4. Sanctum Built-in CSRF Endpoint

Sanctum fournit aussi un endpoint natif :

```bash
GET /sanctum/csrf-cookie
```

Cet endpoint configure automatiquement le cookie CSRF dans la réponse.

## Usage pour Swagger

Dans Swagger, vous pouvez maintenant :

1. **Directement appeler login** sans token préalable:
```bash
POST /api/login
Content-Type: application/json

{
  "email": "admin@discover229.com",
  "password": "password"
}
```

2. **Copier le token de réponse** et l'utiliser dans les requêtes protégées :
```bash
POST /api/users
Authorization: Bearer {token_from_login}
```

## Testing

```bash
# Test login (devrait fonctionner maintenant)
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@discover229.com",
    "password": "password"
  }'

# Response devrait inclure le token
{
  "success": true,
  "message": "Login successful",
  "data": {
    "token": "1|ta2qvDY0ZSFKFSPyKzWHCCeDWkUmnCxjkjKpH2er8d178474"
  }
}
```

## Frontend (React/SPA)

Pour le frontend, le flux est:

1. Appeler `/api/csrf-token` ou `/sanctum/csrf-cookie` au démarrage
2. Faire le login avec email/password
3. Stocker le token retourné
4. Envoyer le token dans les headers `Authorization: Bearer {token}` pour les requêtes protégées

```javascript
// Fetch CSRF token
const csrfResponse = await fetch('/api/csrf-token');
const { csrf_token } = await csrfResponse.json();

// Login
const loginResponse = await fetch('/api/login', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'X-CSRF-Token': csrf_token
  },
  body: JSON.stringify({ email, password })
});

const { data } = await loginResponse.json();
localStorage.setItem('auth_token', data.token);

// Use token in protected routes
const protectedResponse = await fetch('/api/users', {
  headers: {
    'Authorization': `Bearer ${data.token}`
  }
});
```
