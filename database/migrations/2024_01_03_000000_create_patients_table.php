<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('patient_code')->unique();
            $table->string('name');
            $table->date('dob')->nullable();
            $table->integer('age')->nullable();
            $table->enum('gender', ['female', 'male', 'other'])->default('female');
            $table->string('phone');
            $table->string('phone_alt')->nullable();
            $table->text('address')->nullable();
            $table->string('nid_number')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('religion')->nullable();
            $table->string('occupation')->nullable();
            $table->string('referred_by')->nullable();
            $table->string('photo')->nullable();
            $table->string('nid_photo')->nullable();
            $table->foreignId('consultant_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('registration_type', ['quick', 'full'])->default('full');
            $table->enum('status', ['active', 'inactive', 'completed'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
