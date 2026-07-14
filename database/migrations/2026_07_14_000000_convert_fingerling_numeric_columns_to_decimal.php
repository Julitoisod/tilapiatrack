<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Convert the free-text numeric columns on fingerlings to real decimals
     * so math, sorting and aggregation behave correctly.
     */
    public function up(): void
    {
        Schema::table('fingerlings', function (Blueprint $table) {
            $table->decimal('weight', 10, 2)->nullable()->change();
            $table->decimal('feed_amount', 10, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('fingerlings', function (Blueprint $table) {
            $table->string('weight')->change();
            $table->string('feed_amount')->change();
        });
    }
};
