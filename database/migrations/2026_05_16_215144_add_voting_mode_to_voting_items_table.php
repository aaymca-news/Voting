<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('voting_items', function (Blueprint $table) {
            $table->string('voting_mode')
                ->default('anonymous')
                ->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('voting_items', function (Blueprint $table) {
            $table->dropColumn('voting_mode');
        });
    }
};