<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fishponds', function (Blueprint $table) {
            // Set when the pond is harvested; archives it out of the active list
            // without deleting the pond, its fingerlings, or the harvest record.
            $table->timestamp('harvested_at')->nullable()->after('picture');
        });
    }

    public function down(): void
    {
        Schema::table('fishponds', function (Blueprint $table) {
            $table->dropColumn('harvested_at');
        });
    }
};
