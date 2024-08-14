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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('date');
            $table->string('number');
            $table->date('stamp_high_date')->nullable();
            $table->string('stamp_high_number')->nullable();
            $table->date('stamp_low_date')->nullable();
            $table->string('stamp_low_number')->nullable();
            $table->enum('status', ['Completed', 'Delayed', 'In work']);
            $table->string('path_to_file');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
