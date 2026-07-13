<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feeding_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feed_id')->constrained()->onDelete('cascade');
            $table->string('name');
            
            // Fingerling Stage
            $table->string('fingerling_age_range')->nullable();
            $table->string('fingerling_feeding_frequency')->nullable();
            $table->json('fingerling_feed_time')->nullable();
            $table->decimal('fingerling_fish_amount', 8, 2)->nullable();
            $table->decimal('fingerling_protein_content', 8, 2)->nullable();
            $table->string('fingerling_weight_range')->nullable();
            
            // Juvenile Stage
            $table->string('juvenile_age_range')->nullable();
            $table->string('juvenile_feeding_frequency')->nullable();
            $table->json('juvenile_feed_time')->nullable();
            $table->decimal('juvenile_fish_amount', 8, 2)->nullable();
            $table->decimal('juvenile_protein_content', 8, 2)->nullable();
            $table->string('juvenile_weight_range')->nullable();
            
            // Sub-Adult Stage
            $table->string('subadult_age_range')->nullable();
            $table->string('subadult_feeding_frequency')->nullable();
            $table->json('subadult_feed_time')->nullable();
            $table->decimal('subadult_fish_amount', 8, 2)->nullable();
            $table->decimal('subadult_protein_content', 8, 2)->nullable();
            $table->string('subadult_weight_range')->nullable();
            
            // Adult Stage
            $table->string('adult_age_range')->nullable();
            $table->string('adult_feeding_frequency')->nullable();
            $table->json('adult_feed_time')->nullable();
            $table->decimal('adult_fish_amount', 8, 2)->nullable();
            $table->decimal('adult_protein_content', 8, 2)->nullable();
            $table->string('adult_weight_range')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feeding_programs');
    }
};