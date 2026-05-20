<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('voting_items', 'voting_mode')) {
            Schema::table('voting_items', function (Blueprint $table) {
                $table->string('voting_mode')->default('anonymous');
            });
        }
    }

    public function down(): void
    {
        // This migration is kept for compatibility only.
        // The voting_mode column is created by the earlier migration.
    }
};