<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReceiptImageToIncomesAndExpenses extends Migration
{
    public function up(): void
    {
        Schema::table('incomes', function (Blueprint $table) {
            $table->unsignedBigInteger('receipt_image')->nullable()->after('receipt_no');
            $table->foreign('receipt_image')->references('id')->on('uploads')->nullOnDelete();
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->unsignedBigInteger('receipt_image')->nullable()->after('receipt_no');
            $table->foreign('receipt_image')->references('id')->on('uploads')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('incomes', function (Blueprint $table) {
            $table->dropForeign(['receipt_image']);
            $table->dropColumn('receipt_image');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->dropForeign(['receipt_image']);
            $table->dropColumn('receipt_image');
        });
    }
}

