<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('water_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->constrained()->cascadeOnDelete();
            $table->date('supply_date');
            $table->decimal('hours', 8, 2);
            $table->decimal('rate_per_hour', 10, 2);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->string('season')->nullable(); // রবি, খরিপ-১, খরিপ-২
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('water_entries');
    }
};
