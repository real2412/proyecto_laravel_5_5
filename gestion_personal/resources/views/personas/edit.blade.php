@extends('layouts.default')

@section('content')

	<div class="row">
			<div class="col-12">
				  @if (count($errors) > 0)

			        <div class="alert alert-danger">

			            <strong>Whoops!</strong> There were some problems with your input.<br><br>

			            <ul>

			                @foreach ($errors->all() as $error)

			                    <li>{{ $error }}</li>

			                @endforeach

			            </ul>

			        </div>

			    @endif
			    @if(Session::has('success'))
				    <div class="alert alert-info">
				      {{Session::get('success')}}
				    </div>
				@endif

				<div class="panel panel-default">
					<div class="panel-heading">
				    		<h3 class="panel-title">Actualizar a : {{$persona->nombre." ".$persona->apellido_paterno}}</h3>
				 	</div>

					<div class="panel-body">
			
					
						<div class="table-container">
    						<form method="POST" action="{{ route('personas.update', $persona->id) }}"  role="form">
    						{{ csrf_field() }}
    						<input name="_method" type="hidden" value="PATCH">
			    			<div class="row">
			    				<div class="col-4">
			    					<div class="form-group">
			                <input type="text" name="nombre" value="{{$persona->nombre}}" id="nombre" class="form-control input-sm" placeholder="Nombre">
			    					</div>
			    				</div>
			    				<div class="col-4">
			    					<div class="form-group">
			    						<input type="text" name="apellido_paterno" value="{{$persona->apellido_paterno}}" id="apellido_paterno" class="form-control input-sm" placeholder="Apellido Paterno">
			    					</div>
			    				</div>
								<div class="col-4">
			    					<div class="form-group">
			    						<input type="text" name="apellido_materno" value="{{$persona->apellido_materno}}" id="apellido_materno" class="form-control input-sm" placeholder="Apellido Materno">
			    					</div>
			    				</div>
								<div class="col-4">
			    					<div class="form-group">
			    						<input type="text" name="correo" value="{{$persona->correo}}" id="correo" class="form-control input-sm" placeholder="Correo">
			    					</div>
			    				</div>
								<div class="col-4">
								<select name="categoria_id" class="custom-select">
									<option value="0">Todos</option>
									@foreach($oficinas as $oficina)
										<option value="{{$oficina->id}}" {{($persona->oficina_id===$oficina->id)? 'selected':''}} > {{$oficina->nombre}} </option>
									@endforeach 
								</select>
								</div>
			    			</div>
			    			
			    		 <div class="row">
							
							<div class="col-12">
								<input type="submit"  value="Update" class="btn btn-success btn-block">
								<a href="{{ route('personas.index') }}" class="btn btn-info btn-block" >Back</a>
							</div>	
							
					     </div>
			    		</form>
						</div>
					</div>

				</div>
			</div>
@endsection