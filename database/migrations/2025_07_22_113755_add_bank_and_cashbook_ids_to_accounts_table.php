<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('accounts', function (Blueprint $table) {
        $table->unsignedBigInteger('bank_id')->nullable()->after('sourceable_id');
        $table->unsignedBigInteger('cashbook_id')->nullable()->after('bank_id');
    });
}

public function down()
{
    Schema::table('accounts', function (Blueprint $table) {
        $table->dropColumn(['bank_id', 'cashbook_id']);
    });
}

};
