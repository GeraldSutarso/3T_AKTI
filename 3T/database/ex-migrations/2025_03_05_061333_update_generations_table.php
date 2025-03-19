<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGenerationsTable extends Migration
{
    public function up()
    {
        Schema::create('generations', function (Blueprint $table) {
            $table->id();
            $table->string('gen'); // e.g., "Generation 2025"
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('generations');
    }
}
