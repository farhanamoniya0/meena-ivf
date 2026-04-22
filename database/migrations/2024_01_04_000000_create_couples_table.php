<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('couples', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->string('husband_name');
            $table->date('husband_dob')->nullable();
            $table->integer('husband_age')->nullable();
            $table->string('husband_phone')->nullable();
            $table->string('husband_nid')->nullable();
            $table->string('husband_photo')->nullable();
            $table->string('husband_occupation')->nullable();
            $table->string('husband_blood_group')->nullable();
            $table->date('marriage_date')->nullable();
            $table->text('medical_history')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('couples');
    }
};
