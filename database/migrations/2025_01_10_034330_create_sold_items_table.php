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
        Schema::create('sold_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pay_method_id');
            $table->foreign('pay_method_id')
                ->references('id')
                ->on('pay_methods');
            $table->unsignedBigInteger('sell_method_id');
            $table->foreign('sell_method_id')
                ->references('id')
                ->on('sell_methods');
            $table->string('name', 128);
            $table->float('price');
            $table->string('condition', 32);
            $table->string('size', 32);
            $table->date('date_sold');
            $table->text('tags')
                ->nullable();
            $table->string('notes', 255)
                ->nullable();
            $table->string('image_location', 2048)
                ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sold_items');
    }
};
