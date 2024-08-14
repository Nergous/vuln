<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('real_solutions', function (Blueprint $table) {
            $table->id();
            $table->text('solution');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('real_solutions');
    }
};