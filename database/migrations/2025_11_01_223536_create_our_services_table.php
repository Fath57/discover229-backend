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
        Schema::create('our_services', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique()->index();
            $table->timestamps();
        });

        Schema::create('our_service_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('our_service_id')->constrained('our_services')->onDelete('cascade');
            $table->string('locale')->index();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('our_services');
        Schema::dropIfExists('our_service_translations');
    }
};
