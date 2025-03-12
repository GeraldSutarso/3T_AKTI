<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhysicalTable extends Migration
{
    public function up()
    {
        Schema::create('physical', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // user drafting the record
            $table->unsignedBigInteger('period_id')->nullable(); // set upon finalization
            $table->decimal('bleep', 5, 2)->nullable();
            $table->integer('pull_up')->nullable();
            $table->integer('push_up')->nullable();
            $table->integer('sit_up')->nullable();
            $table->decimal('shuttle', 5, 2)->nullable();
            $table->decimal('avg', 5, 2)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('period_id')->references('id')->on('periods')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('physical');
    }
}
