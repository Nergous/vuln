<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->id(); // ID статуса
            $table->string('name')->unique(); // Название статуса
            $table->timestamps(); // Метки времени
        });

        // Добавление предустановленных статусов
        DB::table('statuses')->insert([
            ['name' => 'Critical'],
            ['name' => 'High'],
            ['name' => 'Middle'],
            ['name' => 'Low'],
            ['name' => 'Unknown'],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('statuses');
    }
};
