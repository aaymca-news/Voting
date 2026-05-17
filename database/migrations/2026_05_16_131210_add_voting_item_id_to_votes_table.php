<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('votes', function (Blueprint $table) {

            $table->foreignId('voting_item_id')
                ->nullable()
                ->after('election_id')
                ->constrained()
                ->cascadeOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {

            $table->dropConstrainedForeignId('voting_item_id');

        });
    }
};