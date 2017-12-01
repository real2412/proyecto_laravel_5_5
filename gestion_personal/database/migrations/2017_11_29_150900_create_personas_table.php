<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personas', function (Blueprint $table) {
			$table->increments('id');
			$table->string('nombre',100)->nullable();            
			$table->string('apellido_paterno',100)->nullable();            
			$table->string('apellido_materno',100)->nullable();            
			$table->string('correo',100)->unique();
			$table->integer('oficina_id')->unsigned();    
			$table->foreign('oficina_id')->references('id')->on('oficinas');            			
			$table->timestamps();
			$table->boolean('estado');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personas');
    }
}
