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
        Schema::create('precenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employe_id')->references('id')->on('employees')->cascadeOnDelete();
            $table->integer('type');
            $table->integer('status');
            $table->unsignedBigInteger('image')->nullable();
            $table->time('time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('precenses');
    }
};
