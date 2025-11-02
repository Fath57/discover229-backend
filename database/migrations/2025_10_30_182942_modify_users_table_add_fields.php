<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Supprimer l'ancienne colonne name si elle existe
            if (Schema::hasColumn('users', 'name')) {
                $table->dropColumn('name');
            }

            // Ajouter les nouvelles colonnes
            $table->string('firstname')->after('id');
            $table->string('lastname')->after('firstname');
            $table->string('phone')->after('email');
            $table->string('whatsapp')->nullable()->after('phone');
            $table->unsignedBigInteger('country_id')->after('whatsapp');
            $table->string('address')->nullable()->after('country_id');
            $table->string('city')->nullable()->after('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Rollback: retirer les nouvelles colonnes
            if (Schema::hasColumn('users', 'city')) {
                $table->dropColumn('city');
            }
            if (Schema::hasColumn('users', 'address')) {
                $table->dropColumn('address');
            }
            if (Schema::hasColumn('users', 'country_id')) {
                $table->dropColumn('country_id');
            }
            if (Schema::hasColumn('users', 'whatsapp')) {
                $table->dropColumn('whatsapp');
            }
            if (Schema::hasColumn('users', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('users', 'lastname')) {
                $table->dropColumn('lastname');
            }
            if (Schema::hasColumn('users', 'firstname')) {
                $table->dropColumn('firstname');
            }

            // Restaurer la colonne name
            $table->string('name')->after('id');
        });
    }
};
