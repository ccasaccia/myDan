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
        Schema::create('construction_sites', function (Blueprint $table) {
            $table->id();
            $table->string('site_name');
            $table->string('client_name');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['planned', 'active', 'completed', 'on_hold']);
            $table->string('location');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('budget', 12, 2)->nullable();
            $table->decimal('actual_cost', 12, 2)->nullable();
            $table->text('description',255)->nullable();
            $table->string('details',255);
            $table->string('permit_number')->nullable();
            $table->date('safety_inspection_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('construction_site_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('construction_site_id')
                  ->constrained('construction_sites')
                  ->onDelete('cascade');
            $table->string('path', 255);
            $table->string('alt_text')->nullable();
            $table->boolean('is_main')->default(false);
            $table->timestamps();
        });

        Schema::create('task_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('worker_teams', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('worker_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('construction_workers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->foreignId('team_id')->nullable()->constrained('worker_teams')->nullOnDelete();
            $table->foreignId('type_id')->nullable()->constrained('worker_types')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('construction_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('construction_site_id')->constrained('construction_sites')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('task_type_id')->nullable()->constrained('task_types')->nullOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('construction_task_team', function (Blueprint $table) {
            $table->id();
            $table->foreignId('construction_task_id')->constrained('construction_tasks')->onDelete('cascade');
            $table->foreignId('team_id')->constrained('worker_teams')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('construction_task_user');
        Schema::dropIfExists('construction_tasks');
        Schema::dropIfExists('task_types');
        Schema::dropIfExists('construction_sites');
        Schema::dropIfExists('construction_site_images');
    }
};
