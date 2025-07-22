<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadsTable extends Migration
{
    public function up(): void
    {
        Schema::create('uploads', function (Blueprint $table) {
            $table->id();
            $table->string('file_original_name');
            $table->string('file_name'); // saved name in disk
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('extension', 10)->nullable();
            $table->string('type')->nullable(); // e.g., receipt, profile, etc.
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uploads');
    }
}
