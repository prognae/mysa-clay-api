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
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_discounted')->default(0)->after('price');
            $table->float('discounted_price')->nullable()->after('is_discounted');
            $table->float('markup')->nullable()->after('discounted_price');
            $table->float('markdown')->nullable()->after('markup');
            $table->float('final_price')->nullable()->after('markdown');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
