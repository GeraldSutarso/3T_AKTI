<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKpiTable extends Migration
{
    public function up()
    {
        Schema::create('kpi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // user creating the draft
            $table->unsignedBigInteger('period_id')->nullable(); // set upon finalization
            $table->integer('no_room')->nullable();
            $table->decimal('kedisiplinan_point', 5, 2)->nullable();
            $table->decimal('kedisiplinan_nilai', 5, 2)->nullable();
            $table->decimal('kesehatan_point', 5, 2)->nullable();
            $table->decimal('kesehatan_nilai', 5, 2)->nullable();
            $table->decimal('safety_point', 5, 2)->nullable();
            $table->decimal('safety_nilai', 5, 2)->nullable();
            $table->decimal('r5_point', 5, 2)->nullable();   // renamed from 5R_point
            $table->decimal('r5_nilai', 5, 2)->nullable();
            $table->decimal('vt7_point', 5, 2)->nullable();  // renamed from 7VT_point
            $table->decimal('vt7_nilai', 5, 2)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('period_id')->references('id')->on('periods')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('kpi');
    }
}
