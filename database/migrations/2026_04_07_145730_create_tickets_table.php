<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('class_department'); // Klase/Nodaļa
            $table->enum('category', ['Hardware', 'Software', 'Network', 'Other']);
            $table->enum('priority', ['Low', 'Medium', 'Urgent']);
            $table->string('title');
            $table->text('description');
            $table->enum('status', ['Open', 'In Progress', 'Closed'])->default('Open');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
