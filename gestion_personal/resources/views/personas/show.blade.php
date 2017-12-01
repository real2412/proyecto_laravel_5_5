@extends('layouts.default')

 

@section('content')

<div class="row">

		<section class="content">

			<div class="col-12">


				<div class="panel panel-default">
					<div class="panel-heading">
				    		<h3 class="panel-title">Datos de {{$persona->nombre}}</h3>
							<label>Nombre completo: {{$persona->nombre." ".$persona->apellido_paterno." ".$persona->apellido_materno}}</label>
							<br>
							<label>Correo: {{$persona->correo}}</label>														
							
				 	</div>

					<div class="panel-body">			
					
		
						
						
						
						
						
    					    			
			    		 <div class="row"
							
							<div class="col-12">
								
								<a href="{{ route('personas.index') }}" class="btn btn-info btn-block" >Atras</a>
							</div>	
							
					     </div>
			    		
		
					</div>

				</div>
			</div>
		</section>



@endsection