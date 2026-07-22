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
        Schema::create('post_relations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id')
                ->unique();
            $table->foreign('post_id')
                ->references('id')
                ->on('posts')
                ->cascadeOnDelete();
            $table->json('related_post_ids')
                ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_relations');
    }
};
