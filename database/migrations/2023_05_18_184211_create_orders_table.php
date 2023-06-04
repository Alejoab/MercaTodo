<?php

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->enum('status', array_column(OrderStatus::cases(), 'value'));
            $table->unsignedDecimal('total', 10)->default(0);
            $table->enum('payment_method', array_column(PaymentMethod::cases(), 'value'))->nullable();
            $table->unsignedBigInteger('requestId')->nullable();
            $table->string('processUrl')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
