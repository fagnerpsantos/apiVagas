<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVagasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vagas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titulo');
            $table->string('descricao');
            $table->float('salario');
            $table->integer('qtdHoras');
            $table->integer('qtdVagas');
            $table->integer('empresa_id')->unsigned();
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vagas');
    }
}
