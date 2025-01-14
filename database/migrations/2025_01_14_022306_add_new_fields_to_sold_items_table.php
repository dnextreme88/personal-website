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
        Schema::table('sold_items', function (Blueprint $table) {
            $table->string('brand', 64)
                ->after('sell_method_id');
            $table->string('type', 64)
                ->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sold_items', function (Blueprint $table) {
            $table->dropColumn('brand');
        });
    }
};
