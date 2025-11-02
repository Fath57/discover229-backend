<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Define modules
        $modules = [
            'users' => ['name' => 'Users', 'description' => 'Gestion des utilisateurs'],
            'agencies' => ['name' => 'Agencies', 'description' => 'Gestion des agences de tourisme'],
            'offers' => ['name' => 'Offers', 'description' => 'Gestion des offres et circuits'],
            'reviews' => ['name' => 'Reviews', 'description' => 'Gestion des avis et commentaires'],
            'messages' => ['name' => 'Messages', 'description' => 'Gestion des messages et communications'],
            'settings' => ['name' => 'Settings', 'description' => 'Paramètres généraux de l\'application'],
            'roles' => ['name' => 'Roles', 'description' => 'Gestion des rôles'],
            'permissions' => ['name' => 'Permissions', 'description' => 'Gestion des permissions'],
            'dashboard' => ['name' => 'Dashboard', 'description' => 'Tableaux de bord'],
            'ai' => ['name' => 'AI', 'description' => 'Fonctionnalités d\'intelligence artificielle'],
        ];

        foreach ($modules as $key => $details) {
            \App\Models\Module::create([
                'key' => $key,
                'name' => $details['name'],
                'description' => $details['description'],
            ]);
        }

        // Define permissions organized by module
        $permissions = [
            // Module: Users Management
            'users' => [
                'users.view' => 'Voir les utilisateurs',
                'users.create' => 'Créer des utilisateurs',
                'users.update' => 'Modifier des utilisateurs',
                'users.delete' => 'Supprimer des utilisateurs',
                'users.manage-roles' => 'Gérer les rôles des utilisateurs',
            ],

            // Module: Agencies Management
            'agencies' => [
                'agencies.view' => 'Voir les agences',
                'agencies.create' => 'Créer des agences',
                'agencies.update' => 'Modifier des agences',
                'agencies.delete' => 'Supprimer des agences',
                'agencies.approve' => 'Approuver des agences',
                'agencies.manage-own' => 'Gérer sa propre agence',
            ],

            // Module: Offers Management
            'offers' => [
                'offers.view' => 'Voir les offres',
                'offers.create' => 'Créer des offres',
                'offers.update' => 'Modifier des offres',
                'offers.delete' => 'Supprimer des offres',
                'offers.manage-own' => 'Gérer ses propres offres',
            ],

            // Module: Reviews Management
            'reviews' => [
                'reviews.view' => 'Voir les avis',
                'reviews.create' => 'Créer des avis',
                'reviews.update' => 'Modifier des avis',
                'reviews.delete' => 'Supprimer des avis',
                'reviews.moderate' => 'Modérer les avis',
                'reviews.manage-own' => 'Gérer ses propres avis',
            ],

            // Module: Messages Management
            'messages' => [
                'messages.view' => 'Voir les messages',
                'messages.send' => 'Envoyer des messages',
                'messages.reply' => 'Répondre aux messages',
                'messages.delete' => 'Supprimer des messages',
                'messages.manage-own' => 'Gérer ses propres messages',
            ],

            // Module: Settings
            'settings' => [
                'settings.view' => 'Voir les paramètres',
                'settings.update' => 'Modifier les paramètres',
                'settings.manage-categories' => 'Gérer les catégories',
                'settings.manage-regions' => 'Gérer les régions',
            ],

            // Module: Roles & Permissions
            'roles' => [
                'roles.view' => 'Voir les rôles',
                'roles.create' => 'Créer des rôles',
                'roles.update' => 'Modifier des rôles',
                'roles.delete' => 'Supprimer des rôles',
            ],

            'permissions' => [
                'permissions.view' => 'Voir les permissions',
                'permissions.assign' => 'Assigner des permissions',
            ],

            // Module: Dashboard & Reports
            'dashboard' => [
                'dashboard.view-admin' => 'Voir le tableau de bord admin',
                'dashboard.view-agency' => 'Voir le tableau de bord agence',
                'dashboard.view-statistics' => 'Voir les statistiques',
            ],

            // Module: AI Features (for future use)
            'ai' => [
                'ai.chat' => 'Utiliser le chatbot IA',
                'ai.recommendations' => 'Utiliser les recommandations IA',
            ],
        ];

        // Create all permissions
        foreach ($permissions as $module => $modulePermissions) {
            foreach ($modulePermissions as $permissionName => $description) {
                Permission::create([
                    'name' => $permissionName,
                    'description' => $description,
                    'guard_name' => 'web',
                ]);
            }
        }

        // Create roles and assign permissions

        // 1. Admin System - Full access to everything
        $adminSystem = Role::create([
            'name' => 'admin_system',
            'description' => 'Administrateur système avec accès complet',
            'guard_name' => 'web'
        ]);
        $adminSystem->givePermissionTo(Permission::all());

        // 2. Admin Agency - Agency owner with limited admin capabilities
        $adminAgency = Role::create([
            'name' => 'admin_agency',
            'description' => 'Propriétaire d\'agence de tourisme',
            'guard_name' => 'web'
        ]);
        $adminAgency->givePermissionTo([
            // Own agency management
            'agencies.view',
            'agencies.manage-own',

            // Offers management
            'offers.view',
            'offers.create',
            'offers.update',
            'offers.delete',
            'offers.manage-own',

            // Messages
            'messages.view',
            'messages.reply',
            'messages.manage-own',

            // Reviews
            'reviews.view',
            'reviews.manage-own',

            // Dashboard
            'dashboard.view-agency',
            'dashboard.view-statistics',

            // AI Features
            'ai.chat',
            'ai.recommendations',
        ]);

        // 3. User - Regular visitor/tourist
        $user = Role::create([
            'name' => 'user',
            'description' => 'Visiteur/touriste standard',
            'guard_name' => 'web'
        ]);
        $user->givePermissionTo([
            // View agencies and offers
            'agencies.view',
            'offers.view',

            // Create and manage own reviews
            'reviews.view',
            'reviews.create',
            'reviews.manage-own',

            // Send messages to agencies
            'messages.send',
            'messages.manage-own',

            // AI Features
            'ai.chat',
            'ai.recommendations',
        ]);

        $this->command->info('Roles and Permissions created successfully!');
        $this->command->info('');
        $this->command->info('Created Roles:');
        $this->command->info('- admin_system: Full system access');
        $this->command->info('- admin_agency: Agency owner with management capabilities');
        $this->command->info('- user: Regular visitor/tourist');
        $this->command->info('');
        $this->command->info('Total Permissions created: ' . Permission::count());
    }
}
