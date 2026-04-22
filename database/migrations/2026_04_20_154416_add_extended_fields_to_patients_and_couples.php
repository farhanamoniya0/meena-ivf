<?php

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
        Schema::table('patients', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('patient_code');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('marital_status')->nullable()->after('gender');
            $table->string('source_type')->nullable()->after('referred_by');
            $table->string('post_code')->nullable()->after('address');
            $table->string('thana')->nullable()->after('post_code');
            $table->string('district')->nullable()->after('thana');
            $table->string('division')->nullable()->after('district');
            $table->decimal('height_cm', 5, 1)->nullable()->after('blood_group');
            $table->decimal('weight_kg', 5, 1)->nullable()->after('height_cm');
        });

        Schema::table('couples', function (Blueprint $table) {
            $table->string('partner_first_name')->nullable()->after('patient_id');
            $table->string('partner_last_name')->nullable()->after('partner_first_name');
            $table->enum('partner_gender', ['male','female','other'])->nullable()->after('partner_last_name');
            $table->string('partner_marital_status')->nullable()->after('partner_gender');
            $table->string('partner_phone')->nullable()->after('husband_phone');
            $table->string('partner_occupation')->nullable()->after('husband_occupation');
            $table->string('partner_blood_group')->nullable()->after('husband_blood_group');
            $table->decimal('partner_height_cm', 5, 1)->nullable()->after('partner_blood_group');
            $table->decimal('partner_weight_kg', 5, 1)->nullable()->after('partner_height_cm');
            $table->text('partner_address')->nullable()->after('partner_weight_kg');
            $table->string('partner_post_code')->nullable()->after('partner_address');
            $table->string('partner_thana')->nullable()->after('partner_post_code');
            $table->string('partner_district')->nullable()->after('partner_thana');
            $table->string('partner_division')->nullable()->after('partner_district');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['first_name','last_name','marital_status','source_type',
                'post_code','thana','district','division','height_cm','weight_kg']);
        });

        Schema::table('couples', function (Blueprint $table) {
            $table->dropColumn(['partner_first_name','partner_last_name','partner_gender',
                'partner_marital_status','partner_phone','partner_occupation','partner_blood_group',
                'partner_height_cm','partner_weight_kg','partner_address','partner_post_code',
                'partner_thana','partner_district','partner_division']);
        });
    }
};
