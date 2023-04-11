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
            $table->enum(
                'document_type',
                array_column(DocumentType::cases(), 'value')
            );
            $table->string('document', 11)->unique();
            $table->string('phone', 10)->nullable();
            $table->string('address');
            $table->foreignIdFor(City::class);
            $table->timestamps();
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
