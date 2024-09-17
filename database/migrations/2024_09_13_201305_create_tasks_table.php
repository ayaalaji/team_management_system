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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title')->min(3)->max(100);
            $table->text('description')->min(3)->max(255);
            $table->enum('status',['new','in_progress','finished'])->default('new');
            $table->enum('priority',['low', 'medium', 'high'])->nullable();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->date('due_date')->nullable(); 
            $table->string('added_by')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
