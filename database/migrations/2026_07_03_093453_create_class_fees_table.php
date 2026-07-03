<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_class_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('term_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fees_type_id')->constrained('fees_types')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->timestamps();

            // A class can only have one amount for a specific fee type per term/session
            $table->unique(['school_class_id', 'academic_session_id', 'term_id', 'fees_type_id'], 'class_fee_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_fees');
    }
};
