<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voting_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('election_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title');
            $table->string('type')->default('candidate');
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voting_items');
    }
};