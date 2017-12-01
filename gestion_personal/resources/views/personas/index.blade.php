@extends('layouts.default')

 

@section('content')


						<div class="pull-left">
							<h3>Lista de Personas</h3>
						</div>
						<div class="pull-right">
							<div class="btn-group">
								
								<a href="{{ route('personas.create') }}" class="btn btn-info" >Crear Nuevo</a>
								
							</div>
						</div>
						<div style="margin:10px 0px;">
						
							{{ Form::model(Request::only(['nombre','oficina_id']),['route'=>'personas.index', 'method'=>'GET', 'role'=>'search', 'class'=>'form-inline']) }}							  
							  
							  {{ Form::text('nombre', null, ['class'=>'form-control input-sm', 'placeholder'=>'Nombre']) }}							  
							  {{ Form::select('oficina_id', array_pluck($oficinas, 'nombre', 'id'), null, ['class'=>'custom-select']) }}
							  
							  <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
							{{ Form::close() }}
							
						</div>
						
						<div class="table-container">
						
              <table id="mytable" class="table table-bordred table-striped">
                   
                   <thead>
                   

                       <th>Nombre</th>
                       <th>Apellido P.</th>                       
					   <th>Apellido M.</th>                       
                       <th>Estado</th>
                       <th>Ver</th>
                       <th>Editar</th>
                       <th>Borrar</th>
                   </thead>
    <tbody>
  @if($personas->count())  
  @foreach($personas as $persona)  
    <tr>   
    <td>{{$persona->nombre}}</td>
    <td>{{$persona->apellido_paterno}}</td>    
	<td>{{$persona->apellido_materno}}</td>    
    <td> <span class="btn btn-{{ ($persona->estado) ? 'success' : 'danger' }}"> {{ ($persona->estado) ? 'activo' : 'inactivo' }}</span></td>
    <td><a class="btn btn-primary" href="{{action('PersonaController@show', $persona->id)}}" >Ver</a></td>
    <td><a class="btn btn-primary" href="{{action('PersonaController@edit', $persona->id)}}" >Editar</a></td>
    <td>
<form action="{{action('PersonaController@destroy', $persona->id)}}" method="post">
     {{csrf_field()}}
     <input name="_method" type="hidden" value="DELETE">
   
     <button class="btn btn-danger btn-xs" type="submit">Borrar</button>
    </td>
    </tr>
   @endforeach 
   @else
 <tr>
    <td colspan="7">No existen registros !!</td>
    </tr>
   @endif
 
   
    
   
    
    </tbody>
        
</table>
{{ $personas->appends(Request::only(['nombre', 'oficina_id']))->render() }}
						</div>
					
					
					
					
					
					
@endsection
