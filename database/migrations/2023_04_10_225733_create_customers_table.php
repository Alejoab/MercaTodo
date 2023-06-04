<?php

use App\Enums\DocumentType;
use App\Models\City;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('surname', 255);
            $table->enum('document_type', array_column(DocumentType::cases(), 'value'));
            $table->string('document', 10)->unique();
            $table->string('phone', 10)->nullable();
            $table->string('address', 255);
            $table->foreignIdFor(City::class);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->index('name');
            $table->index('surname');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
