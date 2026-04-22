<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_no')->unique();
            $table->foreignId('patient_package_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->enum('payment_method', ['cash','bank','card','bkash','nagad','rocket'])->default('cash');
            $table->string('transaction_id')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('reference')->nullable();
            $table->foreignId('received_by')->constrained('users');
            $table->text('remarks')->nullable();
            $table->enum('status', ['pending','approved','rejected'])->default('approved');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
