<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->ulid('id')->primary();
            if (config('tasks-management.use_teams', false)) {
                $table->foreignUlid('team_id')->nullable()->constrained()->cascadeOnDelete();
            }
            $table->foreignUlid('user_id')->constrained()->cascadeOnDelete();
            $table->ulidMorphs('commentable');
            $table->longText('content');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
