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
        Schema::create('dropping_areas', function (Blueprint $table) {
            $table->id();
            $table->string('address', 255)
                ->nullable();
            $table->string('unit', 128);
            $table->string('note', 255)
                ->nullable();
            $table->string('opens_at', 255)
                ->nullable();
            $table->string('closes_at', 255)
                ->nullable();
            $table->date('started_on')
                ->nullable();
            $table->boolean('is_current')
                ->default(false);
            $table->string('image_path', 255)
                ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dropping_areas');
    }
};
