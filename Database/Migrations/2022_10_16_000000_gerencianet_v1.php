<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GerencianetV1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gerencianet_transactions', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('document_id')->unique();
            $table->unsignedInteger('location_id');
            $table->string('txid', 32);
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->foreign('document_id')->references('id')->on('documents');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gerencianet_transactions');
    }
}
