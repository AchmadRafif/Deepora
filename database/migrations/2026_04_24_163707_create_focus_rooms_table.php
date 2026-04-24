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
        Schema::create('focus_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('music_type')->default('lofi'); // lofi, jazz, nature
            $table->string('youtube_url')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->integer('max_members')->default(10);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
};
