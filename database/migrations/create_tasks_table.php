<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->ulid('id')->primary();

            // Verifica se o multitenancy está ativado
            if (config('tasks-management.features.multitenancy', false)) {
                $table->foreignIdFor(config('tasks-management.models.team'))->constrained()->cascadeOnDelete();
            }

            $table->foreignIdFor(config('tasks-management.models.user'))->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUlid('parent_id')->nullable()->constrained('tasks');
            $table->nullableUlidMorphs('taskable');
            $table->string('name');
            $table->string('priority')->nullable();
            $table->string('status')->nullable();
            $table->string('type')->nullable();
            $table->longText('description')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
