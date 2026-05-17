<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('votes', function (Blueprint $table) {

            $table->unique(
                ['user_id', 'voting_item_id'],
                'unique_user_vote_per_motion'
            );

        });
    }

    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {

            $table->dropUnique('unique_user_vote_per_motion');

        });
    }
};