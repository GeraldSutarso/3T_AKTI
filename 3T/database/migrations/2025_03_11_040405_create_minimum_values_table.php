<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMinimumValuesTable extends Migration
{
    public function up()
    {
        Schema::create('minimum_values', function (Blueprint $table) {
            $table->id();
            // Optionally, link this record to a period if thresholds vary by period.
            $table->unsignedBigInteger('period_id')->nullable();
            
            // Physical thresholds for, say, the "bleep" metric.
            $table->decimal('bleep_very_good', 5, 2)->nullable();
            $table->decimal('bleep_good', 5, 2)->nullable();
            $table->decimal('bleep_standard', 5, 2)->nullable();
            $table->decimal('bleep_bad', 5, 2)->nullable();
            $table->decimal('bleep_kkm', 5, 2)->nullable();

            // KPI thresholds.
            $table->decimal('kedisiplinan_min', 5, 2)->nullable();
            $table->decimal('kesehatan_min', 5, 2)->nullable();
            $table->decimal('safety_min', 5, 2)->nullable();
            $table->decimal('r5_min', 5, 2)->nullable(); // For 5R.
            $table->decimal('vt7_min', 5, 2)->nullable(); // For 7VT.
            
            $table->timestamps();
            
            // If you use a period system, you might add a foreign key:
            // $table->foreign('period_id')->references('id')->on('periods')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('minimum_values');
    }
}
