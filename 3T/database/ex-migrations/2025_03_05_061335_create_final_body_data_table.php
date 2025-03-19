<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinalBodyDataTable extends Migration
{
    public function up()
    {
        Schema::create('final_body_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // user finalizing the record
            $table->unsignedBigInteger('period_id'); // must reference a period
            $table->decimal('height', 5, 2)->nullable();
            $table->decimal('ideal_weight', 5, 2)->nullable();
            $table->decimal('actual_weight', 5, 2)->nullable();
            $table->text('conclusion')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('period_id')->references('id')->on('periods')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('final_body_data');
    }
}
