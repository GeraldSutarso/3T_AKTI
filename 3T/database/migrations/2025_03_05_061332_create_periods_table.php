<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeriodsTable extends Migration
{
    public function up()
    {
        Schema::create('periods', function (Blueprint $table) {
            $table->id();
            $table->string('period'); // e.g., "2024/1" or "2024-03"
            $table->enum('type', ['semester', 'month']); // distinguishes the period type
            $table->boolean('is_current')->default(false); // marks the current active period
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('periods');
    }
}
