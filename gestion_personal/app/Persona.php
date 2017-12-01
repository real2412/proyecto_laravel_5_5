<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    
protected $fillable = ['nombre', 'apellido_paterno', 'apellido_materno', 'correo', 'oficina_id',];
	
	function scopeNombre($query, $nombre){
		if(trim($nombre)!=""){
			$query->where('nombre','LIKE', '%'.$nombre.'%');
		}
	}

	function scopeOficina($query, $oficina){
		if(trim($oficina)){
			$query->where('oficina_id','=',$oficina);
		}
	}
}
