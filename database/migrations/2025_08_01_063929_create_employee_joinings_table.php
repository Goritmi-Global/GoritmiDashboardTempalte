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
        Schema::create('employee_joinings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('joining_date');
            $table->decimal('starting_salary', 12, 2);
            $table->date('probation_from')->nullable();
            $table->date('probation_to')->nullable();
            $table->enum('contract_type', ['full_time', 'part_time', 'contract', 'internship'])->default('full_time');
            $table->string('contract_document')->nullable(); // path to uploaded file
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete(); // if using roles
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_joinings');
    }
};
