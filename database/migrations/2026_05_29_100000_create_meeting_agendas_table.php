<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meeting_agendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained()->onDelete('cascade');
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->string('file_path');
            $table->string('original_filename');
            $table->string('file_type'); // pdf or docx
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meeting_agendas');
    }
};
