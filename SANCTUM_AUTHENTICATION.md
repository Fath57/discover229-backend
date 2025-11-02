# Sanctum SPA Authentication Documentation

## Overview

L'application utilise **Laravel Sanctum** avec authentification **stateful** pour les SPAs. Les tokens d'authentification sont stockés dans des **HTTP-only cookies** sécurisés, et non retournés au frontend.

**Documentation officielle**: [Laravel Sanctum - SPA Authentication](https://laravel.com/docs/12.x/sanctum#spa-authentication)

## Architecture

### Flux d'authentification

```
┌─────────────────┐         ┌──────────────────┐
│   Frontend SPA  │         │  Laravel Backend │
│  (React/Vue)    │         │   + Sanctum      │
└────────┬────────┘         └────────┬─────────┘
         │                           │
         │── GET /sanctum/csrf-cookie│
         │◄──── Set CSRF+Session     │
         │                           │
         │── POST /api/auth/login    │
         │      (email, password)    │
         │◄────── HTTP-only token    │
         │        in cookie          │
         │                           │
         │── GET /api/user           │
         │  (cookie sent auto)       │
         │◄────── User data          │
```

### Points clés

✅ **HTTP-only Cookies**: Les tokens sont stockés dans des cookies HTTP-only (non accessibles via JavaScript)
✅ **Session Regeneration**: Prévention des attaques de fixation de session
✅ **CSRF Protection**: Automatique avec Sanctum
✅ **Stateful**: Sanctum utilise la session Laravel pour les SPAs du même domaine
✅ **Token-based**: Pour les clients externes (mobiles, APIs tierces)

## Configuration

### 1. Fichier `.env`

```env
# Domains that can use stateful authentication (same-origin SPAs)
SANCTUM_STATEFUL_DOMAINS=localhost,localhost:3000,localhost:5173,127.0.0.1,127.0.0.1:3000,127.0.0.1:5173,127.0.0.1:8000

# Frontend URL for OAuth redirects
APP_FRONTEND_URL=http://localhost:3000
```

### 2. Configuration `config/sanctum.php`

```php
'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', 
    'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1'
)),

'guard' => ['web'],  // Uses Laravel's web guard for sessions
```

### 3. Middleware `bootstrap/app.php`

```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->statefulApi();  // Enable stateful API authentication
})
```

## Routes Disponibles

### Authentification Publique

| Method | Route | Description |
|--------|-------|-------------|
| POST | `/api/auth/login` | Connexion utilisateur |
| POST | `/api/auth/register` | Inscription utilisateur |
| GET | `/sanctum/csrf-cookie` | Initialise la session (obligatoire avant login) |

### Authentification Sociale (OAuth2)

| Method | Route | Description |
|--------|-------|-------------|
| GET | `/api/auth/providers/{provider}/redirect` | Redirige vers OAuth provider |
| GET | `/api/auth/providers/{provider}/callback` | Callback OAuth (gestion auto) |

**Providers supportés**: `google`, `facebook`, `github`

### Routes Protégées

| Method | Route | Description |
|--------|-------|-------------|
| GET | `/api/user` | Récupère l'utilisateur actuel |
| POST | `/api/auth/logout` | Déconnexion |

## Flux d'authentification Frontend

### 1. Initialiser la session (une seule fois au démarrage)

```javascript
// Initialiser la session CSRF
await fetch('/sanctum/csrf-cookie', {
  credentials: 'include'  // Important: inclure les cookies
});
```

### 2. Login

```javascript
const response = await fetch('/api/auth/login', {
  method: 'POST',
  credentials: 'include',  // Important: inclure les cookies
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
  body: JSON.stringify({
    email: 'admin@discover229.com',
    password: 'password'
  })
});

const data = await response.json();

if (response.ok) {
  // Le token est maintenant dans le HTTP-only cookie
  // Pas besoin de le stocker en localStorage
  console.log('Logged in:', data.data.user);
}
```

### 3. Accéder aux routes protégées

```javascript
// Les cookies sont envoyés automatiquement par le navigateur
const response = await fetch('/api/user', {
  credentials: 'include'  // Important: inclure les cookies
});

const userData = await response.json();
```

### 4. Logout

```javascript
await fetch('/api/auth/logout', {
  method: 'POST',
  credentials: 'include'
});
```

## Swagger/Testing dans Postman

### Étapes pour tester dans Swagger/Postman:

1. **Initialiser la session**:
   ```
   GET /sanctum/csrf-cookie
   ```

2. **Login**:
   ```
   POST /api/auth/login
   Content-Type: application/json
   
   {
     "email": "admin@discover229.com",
     "password": "password"
   }
   ```

3. **Accéder aux ressources protégées**:
   ```
   GET /api/user
   ```

> **Note**: Swagger/Postman gère automatiquement les cookies. Les réponses ne contiennent PAS le token (il est dans le cookie HTTP-only).

## Modèle de Requête: LoginRequest

Le `LoginRequest` utilise la méthode `authenticate()` de Sanctum:

```php
public function authenticate(): void
{
    $this->ensureIsNotRateLimited();

    if (!$this->request->authenticate()) {
        // Authentification échouée
        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    RateLimiter::clear($this->throttleKey());
}
```

**Validation**:
- ✅ Vérifie email et password
- ✅ Rate limiting (5 tentatives par IP)
- ✅ Protection contre les attaques par force brute

## Structure des Contrôleurs

```
app/Http/Controllers/Api/Auth/
├── AuthenticatedSessionController.php  # Login/Logout
├── RegisteredUserController.php         # Registration
├── ProvidersAuthController.php          # OAuth (Google, Facebook, GitHub)
└── UserProfileController.php            # Profile info
```

## Sécurité

### ✅ Mesures de Sécurité Implémentées

1. **HTTP-only Cookies**: Tokens non accessibles via JavaScript
2. **CSRF Protection**: Automatique avec Sanctum + middleware
3. **Session Regeneration**: Après chaque login (prévention fixation)
4. **Rate Limiting**: 5 tentatives de login par IP
5. **Secure SameSite**: Cookies avec `SameSite=Lax`
6. **HTTPS Recommended**: En production, utiliser HTTPS

### Configuration de Sécurité dans `config/session.php`

```php
'secure' => env('SESSION_SECURE_COOKIE'),     // HTTPS only
'http_only' => env('SESSION_HTTP_ONLY', true), // No JS access
'same_site' => env('SESSION_SAME_SITE', 'lax'), // CSRF protection
```

## Implémentation du Google Auth

### 1. Configuration dans `config/services.php`

```php
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI', '/api/auth/providers/google/callback'),
],
```

### 2. Variables d'environnement

```env
GOOGLE_CLIENT_ID=your-client-id
GOOGLE_CLIENT_SECRET=your-client-secret
GOOGLE_REDIRECT_URI=http://localhost:8000/api/auth/providers/google/callback
```

### 3. Route OAuth

```
GET /api/auth/providers/google/redirect  # Vers Google
GET /api/auth/providers/google/callback  # Callback depuis Google
```

### 4. Flux Frontend

```javascript
// Redirection vers Google
window.location.href = '/api/auth/providers/google/redirect';

// Après callback, l'utilisateur est redirigé vers:
// http://localhost:3000/auth/finalize (avec session active)
```

## Modèle de Données

### User Model avec Traits

```php
class User extends Model
{
    use HasApiTokens;  // Pour Sanctum
    use HasFactory;
    
    // Relations pour OAuth
    public function providers()
    {
        return $this->hasMany(UserOAuthProvider::class);
    }
}
```

## Dépannage

### Problème: "Token Mismatch" ou "CSRF Token Invalid"

**Cause**: Vous tentez d'appeler les routes sans initialiser la session d'abord.

**Solution**:
```javascript
// 1. Toujours initialiser d'abord
await fetch('/sanctum/csrf-cookie', { credentials: 'include' });

// 2. Puis faire vos appels avec credentials: 'include'
```

### Problème: Cookies non persistants

**Cause**: `credentials: 'include'` manquant dans les requêtes

**Solution**:
```javascript
fetch('/api/auth/login', {
  method: 'POST',
  credentials: 'include',  // ← Obligatoire
  // ...
})
```

### Problème: Accès par domaine différent

**Cause**: Frontend sur `localhost:3000`, backend sur `localhost:8000`

**Solution**: Configurer dans `.env`:
```env
SANCTUM_STATEFUL_DOMAINS=localhost,localhost:3000,localhost:8000
```

Et dans le navigateur, s'assurer que les cookies cross-site sont acceptés.

## Ressources Supplémentaires

- [Laravel Sanctum Documentation](https://laravel.com/docs/12.x/sanctum)
- [Laravel Authentication](https://laravel.com/docs/12.x/authentication)
- [Laravel Socialite (OAuth)](https://laravel.com/docs/12.x/socialite)
- [OWASP: Session Management](https://owasp.org/www-community/attacks/Session_fixation)
