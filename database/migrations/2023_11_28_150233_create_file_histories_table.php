<?php

use App\Enum\FileActionsEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('file_histories', function (Blueprint $table) {
            $table->id();
            $table->enum('action', [FileActionsEnum::CREATE->value, FileActionsEnum::LOCK->value,
                FileActionsEnum::UNLOCK->value, FileActionsEnum::DELETE->value]);
            $table->foreignId('file_id')->constrained('files')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('editor_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_histories');
    }
};
