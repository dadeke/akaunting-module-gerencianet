<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GerencianetV101 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gerencianet_logs', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('document_id')->nullable();
            $table->enum('action', [
                'create',
                'update',
                'cancel',
                'webhook',
                'show',
                'enable', // Enable logs
                'disable' // Disable logs
            ]);
            $table->boolean('error');
            $table->text('message')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gerencianet_logs');
    }
}
