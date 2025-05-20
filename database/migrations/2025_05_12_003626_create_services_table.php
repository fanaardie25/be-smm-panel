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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service'); 
            $table->string('name');
            $table->string('type')->nullable(); 
            $table->string('category')->nullable();
            $table->decimal('rate', 10, 2); 
            $table->unsignedInteger('min'); 
            $table->unsignedInteger('max'); 
            $table->boolean('refill')->default(false);
            $table->boolean('cancel')->default(false);
            $table->decimal('final_price', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
