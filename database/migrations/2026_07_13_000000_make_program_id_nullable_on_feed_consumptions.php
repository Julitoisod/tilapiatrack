<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Fixes: feed_consumptions.program_id was added NOT NULL with no default, but the
// create form never supplies it (program select is disabled), so every insert
// failed under MySQL strict mode. Make it nullable to match actual usage.
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('feed_consumptions', function (Blueprint $table) {
            $table->unsignedBigInteger('program_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('feed_consumptions', function (Blueprint $table) {
            $table->unsignedBigInteger('program_id')->nullable(false)->change();
        });
    }
};
