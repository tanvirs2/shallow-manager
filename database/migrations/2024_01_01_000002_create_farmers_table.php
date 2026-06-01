<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('farmers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('mobile', 20);
            $table->string('village')->nullable();
            $table->string('union')->nullable();
            $table->string('upazila')->nullable();
            $table->decimal('land_area', 10, 3)->default(0);
            $table->enum('land_unit', ['acre', 'shotok', 'bigha'])->default('shotok');
            $table->text('land_description')->nullable();
            $table->string('nid', 30)->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('farmers');
    }
};
