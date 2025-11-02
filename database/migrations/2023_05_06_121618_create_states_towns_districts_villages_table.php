<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::unprepared(file_get_contents(database_path('sql/countries.sql'))); // states, towns, districts, villages
        //DB::unprepared(file_get_contents(database_path('sql/departements.sql'))); // states, towns, districts, villages
        //DB::unprepared(file_get_contents(database_path('sql/communes.sql'))); // states, towns, districts, villages
        //DB::unprepared(file_get_contents(database_path('sql/arrondissements.sql'))); // states, towns, districts, villages
        //DB::unprepared(file_get_contents(database_path('sql/villages.sql'))); // states, towns, districts, villages
        //DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Schema::dropIfExists('villages');
        Schema::dropIfExists('districts');
        Schema::dropIfExists('towns');
        Schema::dropIfExists('states');
        Schema::dropIfExists('countries');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    }
};
