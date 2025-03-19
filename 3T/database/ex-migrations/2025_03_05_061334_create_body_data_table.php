<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBodyDataTable extends Migration
{
    public function up()
    {
        Schema::create('body_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // user drafting the record
            $table->unsignedBigInteger('period_id')->nullable(); // set upon finalization
            $table->decimal('height', 5, 2)->nullable();
            $table->decimal('ideal_weight', 5, 2)->nullable();
            $table->decimal('actual_weight', 5, 2)->nullable();
            $table->text('conclusion')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('period_id')->references('id')->on('periods')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('body_data');
    }
}
