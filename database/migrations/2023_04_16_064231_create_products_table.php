<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('brand_id')->constrained('brands')->onDelete('cascade')->onUpdate('cascade');
            $table->string('code', 6)->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image');
            $table->decimal('price')->unsigned();
            $table->integer('stock')->unsigned();
            $table->softDeletes();
            $table->timestamps();

            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
