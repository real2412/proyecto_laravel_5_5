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
				    		<h3 class="panel-title">Agregar Persona</h3>
				 	</div>

					<div class="panel-body">
			
					
						<div class="table-container">
    						<form method="POST" action="{{ route('personas.store') }}"  role="form">
    						{{ csrf_field() }}
			    			<div class="row">
			    				<div class="col-4">
			    					<div class="form-group">
			                <input type="text" name="nombre" id="nombre" class="form-control input-sm" placeholder="Nombre(s)">
			    					</div>
			    				</div>
			    				<div class="col-4">
			    					<div class="form-group">
			    						<input type="text" name="apellido_paterno" id="apellido_paterno" class="form-control input-sm" placeholder="Apellido Paterno">
			    					</div>
			    				</div>
								<div class="col-4">
			    					<div class="form-group">
			    						<input type="text" name="apellido_materno" id="apellido_materno" class="form-control input-sm" placeholder="Apellido Materno">
			    					</div>
			    				</div>
								<div class="col-4">
			    					<div class="form-group">
			    						<input type="text" name="correo" id="correo" class="form-control input-sm" placeholder="Correo">
			    					</div>
			    				</div>
								<div class="col-4">
								<select name="oficina_id" class="custom-select">
									<option value="0" selected>Todos</option>
									@foreach($oficinas as $oficina)																								
									<option value="{{$oficina->id}}">{{$oficina->nombre}}</option>
									@endforeach 
								</select>
								</div>
			    			</div>
			    			
			    		 <div class="row">
							
							<div class="col-12">
								<input type="submit"  value="Save" class="btn btn-success btn-block">
								<a href="{{ route('personas.index') }}" class="btn btn-info btn-block" >Atras</a>
							</div>	
							
					     </div>
			    		</form>
						</div>
					</div>

				</div>
			</div>

@endsection
