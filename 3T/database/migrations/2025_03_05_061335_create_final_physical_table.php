<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinalPhysicalTable extends Migration
{
    public function up()
    {
        Schema::create('final_physical', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // user finalizing the record
            $table->unsignedBigInteger('period_id'); // must reference a period
            $table->decimal('bleep', 5, 2)->nullable();
            $table->integer('pull_up')->nullable();
            $table->integer('push_up')->nullable();
            $table->integer('sit_up')->nullable();
            $table->decimal('shuttle', 5, 2)->nullable();
            $table->decimal('avg', 5, 2)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('period_id')->references('id')->on('periods')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('final_physical');
    }
}
