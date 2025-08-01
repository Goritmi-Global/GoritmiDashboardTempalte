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
        Schema::create('employee_salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->date('effective_from'); // when this salary becomes active
            $table->date('effective_to')->nullable(); // if replaced by newer record
            $table->text('reason')->nullable(); // e.g., "Annual increment", "Promotion"
            $table->foreignId('account_id')->nullable()->constrained()->nullOnDelete(); // accounting integration
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_salaries');
    }
};
