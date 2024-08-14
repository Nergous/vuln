<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('compensating_solutions', function (Blueprint $table) {
            $table->id();
            $table->text('measure');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('compensating_solutions');
    }
};
