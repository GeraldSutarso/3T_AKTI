<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentSignaturesTable extends Migration
{
    public function up()
    {
        Schema::create('document_signatures', function (Blueprint $table) {
            $table->id();
            $table->enum('document_type', ['kpi', 'body_data', 'physical']); 
            $table->unsignedBigInteger('document_id'); // the ID of the document record (from final tables)
            $table->enum('role', ['prepared_by', 'checked_by', 'approved_by']);
            $table->unsignedBigInteger('signer_user_id'); // the user who signed off
            $table->timestamp('signed_at')->useCurrent();
            $table->timestamps();

            // Optional: enforce foreign key constraint on the signer
            $table->foreign('signer_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('document_signatures');
    }
}
