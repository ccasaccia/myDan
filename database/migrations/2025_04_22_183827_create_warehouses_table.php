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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['mezzo', 'materiale']);
            $table->text('description')->nullable();
            $table->integer('quantity')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('construction_site_warehouse', function (Blueprint $table) {
            $table->id();
            $table->foreignId('construction_site_id')->constrained()->onDelete('cascade');
            $table->foreignId('warehouse_id')->constrained()->onDelete('cascade');
            $table->integer('quantity_used');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
