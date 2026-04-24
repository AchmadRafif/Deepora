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
        Schema::create('pomodoro_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_id')->nullable()->constrained('focus_rooms');
            $table->integer('duration')->default(25); // menit
            $table->boolean('completed')->default(false);
            $table->integer('xp_earned')->default(0);
            $table->timestamps();
        });
    }
};
