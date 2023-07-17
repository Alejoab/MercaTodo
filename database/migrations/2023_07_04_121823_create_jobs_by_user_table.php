<?php

use App\Support\Enums\JobsByUserStatus;
use App\Support\Enums\JobsByUserType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jobs_by_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->enum('type', array_column(JobsByUserType::cases(), 'value'));
            $table->enum('status', array_column(JobsByUserStatus::cases(), 'value'))->nullable();
            $table->json('errors')->nullable();
            $table->string('file_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs_by_users');
    }
};
