<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('vulnerabilities', function (Blueprint $table) {
            $table->unsignedBigInteger('status_id')->nullable(); // Внешний ключ для статуса
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('vulnerabilities', function (Blueprint $table) {
            $table->dropForeign(['status_id']);
            $table->dropColumn('status_id');
        });
    }
};
