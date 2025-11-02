<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(config('permission.table_names.permissions'), function (Blueprint $table) {
            if (!Schema::hasColumn(config('permission.table_names.permissions'), 'description')) {
                $table->string('description')->nullable()->after('name');
            }
        });

        Schema::table(config('permission.table_names.roles'), function (Blueprint $table) {
            if (!Schema::hasColumn(config('permission.table_names.roles'), 'description')) {
                $table->string('description')->nullable()->after('name');
            }
        });
    }

    public function down(): void
    {
        Schema::table(config('permission.table_names.permissions'), function (Blueprint $table) {
            if (Schema::hasColumn(config('permission.table_names.permissions'), 'description')) {
                $table->dropColumn('description');
            }
        });

        Schema::table(config('permission.table_names.roles'), function (Blueprint $table) {
            if (Schema::hasColumn(config('permission.table_names.roles'), 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};

