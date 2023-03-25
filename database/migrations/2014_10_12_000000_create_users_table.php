<?php

use App\Enums\DocumentType;
use App\Models\City;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->string('document')->unique();
            $table->enum('document_type', ['cedula de ciudadanía', 'tarjeta de identidad', 'cedula de extranjeria', 'pasaporte']);
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('address');
            $table->foreignIdFor(City::class);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->index('name');
            $table->index('surname');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
