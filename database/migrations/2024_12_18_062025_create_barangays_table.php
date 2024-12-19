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
        Schema::create('barangays', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->string('old_name')->nullable();
            $table->string('sub_municipality_code')->nullable();
            $table->string('municipality_code')->nullable();
            $table->string('district_code')->nullable();
            $table->string('province_code')->nullable();
            $table->string('region_code')->nullable();
            $table->string('island_group_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangays');
    }
};
