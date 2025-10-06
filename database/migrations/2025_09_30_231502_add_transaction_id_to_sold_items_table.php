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
            $table->string('transaction_id', 16)
                ->after('sell_method_id')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sold_items', function (Blueprint $table) {
            $table->dropColumn('transaction_id');
        });
    }
};
