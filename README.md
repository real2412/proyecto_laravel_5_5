# Proyecto Basico en Laravel 5.5
<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>
Laravel es un framework de PHP, la documentacion esta en este <a href="https://laravel.com/docs/5.5">link</a>.

El proyecto que se desarrollara en esta ocasion es un CRUD con algunas funciones extra como enlazar por llave foranea en la busqueda por filtros, desde 0. Se ignorara pasos previos como la instalacion del composer, xampp(o homestead), etc.

# Instalacion de Framework

Abre la consola de tu S.O.(cmd en caso Windows), ubicarse en el directorio donde quieres que se cree tu proyecto y tipear:

```bash 
composer create-project --prefer-dist laravel/laravel gestion_personal
```

Se creara la carpeta:gestion_personal instalaran todos lo paquetes de la ultima version de Laravel (5.5).

# Routes

Ahora dirigete a routes/web.php. En este archivo crearemos la ruta para el manteniento del personal(Ver en codigo fuente).
```php
Route::resource('personas','PersonaController');
```

# Model

En la consola, tipear el codigo:

```bash
php artisan make:model Oficina
```
 y
 
 ```bash
php artisan make:model Persona
```

Recuerda que el nombre de los modelos es en singular. Despues de ejecutar esos comandos se generaran los archivos en : app/Oficina.php y app/Persona.php. En este último escribir el siguiente codigo:

```php
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
```

La funcion <strong>scopeNombre</strong> nos servira para filtrar/buscar por ese parametro alguna persona en especial, lo mismo con la funcion <strong>scopeOficina</strong>.

# Controller

Tipear en la consola:

```bash 
php artisan make:controller PersonaController --resource
``` 
Se creara el archivo en app\Http\Controllers\PersonaController.php con las funciones basicas de CRUD, rellenaremos estas funciones tal como se muestra a continuacion:

```php

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {        
    	//las funciones nombre y oficina son usados para el get que a su vez lo usamos en los filtros de busqueda
        $personas=Persona::nombre($request->get('nombre'))->oficina($request->get('oficina_id'))->orderBy('id','DESC')->paginate(2);
		
		$ofi=new Oficina;
		$ofi->id=0;
		$ofi->nombre="Todos";
		$oficinas=Oficina::all('id','nombre');
		$oficinas->prepend($ofi);
		
		
		//$categorias=$categorias2->union($cate);
		//array_unshift( $categorias, $cate);
		
		return view('personas.index', compact('personas','oficinas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $oficinas=Oficina::all();
        return view('personas.create', compact('oficinas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
			'nombre'=>'required',
			'apellido_paterno'=>'required',
			'apellido_materno'=>'required',			
			'correo'=>'required',
		]);
		
		Persona::create($request->all());
		
		return redirect()->route('personas.index')
						->with('success','Persona creada satisfactoriamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $persona=Persona::find($id);
		return view('personas.show', compact('persona'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $persona=Persona::find($id);
		$oficinas=Oficina::all();
		
		return view('personas.edit',compact('persona','oficinas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
			'nombre'=>'required',
			'apellido_paterno'=>'required',
			'apellido_materno'=>'required',			
			'correo'=>'required',
		]);
		
		Persona::find($id)->update($request->all());
		
		return redirect()->route('personas.index')
						->with('success','Persona actualizada satisfactoriamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Persona::find($id)->delete();
		
		return redirect()->route('personas.index')
						->with('success','Persona borrada satisfactoriamente');
    }

```

No olvisarse de usar los modelos Persona y Oficina

# Base de datos.

Para conectarte a la base de datos se tiene que ir a la ruta: config/database.php. Edita:

```php
'default' => 'mysql',
```
...

```php
'mysql' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            'port' => '',
            'database' => 'personal',
            'username' => 'admin',
            'password' => '1234',
            //'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => ''//,
            //'strict' => true,
            //'engine' => null,
        ],
```
Me voy a saltar la parte de creacion de base de datos, para este ejemplo se llama "personal". Para la creacion de tablas Lo puedes realizar con migration o manualmente, sin olvidar que las tablas que crees requieren del campo "id", del tipo int y los campos "created_at" y "updated_at" del tipo timestamps. 

En caso lo hagas con migration, el comando para crear una migracion es el siguiente:
```bash
php artisan make:migration create_oficinas_table
```
Y para ejecutar la migration es:

```bash
php artisan migrate
```

Con migration el codigo para crear tablas seria:

<strong>Tabla Oficina</strong>

```php

public function up()
{
  Schema::create('oficinas', function (Blueprint $table) {
    $table->increments('id');
    $table->string('nombre',100)->nullable();            
    $table->string('descripcion',250)->nullable();            
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
Schema::dropIfExists('oficinas');
}
```

<strong>Tabla Persona</strong>
```php

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
```
# Vista

Ahora debemos meter mano a la parte html. Para esto hay muchas formar de hacerlo. Crea la carpeta /layouts en resources/views,  dentro de esa carpeta crea un archivo resources/views/layouts/default.blade.php. Asimismo crear en esa misma ubicacion el archivo menu.blade.php. El codigo para cada uno es el siguiente:

<strong>default.blade.php</strong>

```php
<!DOCTYPE html>
	<html lang="en">
	<head>
	  <meta charset="utf-8">
	  <meta name="viewport"
	     content="width=device-width, initial-scale=1, user-scalable=yes">
	  <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Roboto+Condensed" rel="stylesheet">
	
	</head>
	<body>
	
	<div class="container-fluid">
	<div style="padding: 10px 0px;">
		<div class="row">
                                    <div class="col-12">
				<h5 class="font-family: 'Roboto Condensed';">
				Gestion Personal</h5>
		            </div>			
		</div>
	</div>
	    <div class="row">
			
			<div class="col-2">
				@include('layouts.menu')
			</div>
			<div class="col-10">
				<div class="panel panel-default">
					<div class="panel-body">
						@yield('content')
					</div>
				</div>
			</div>	
			
		</div>
	</div>

	 
	 <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
	 
	</body>
</html>
```

<strong>menu.blade.php</strong>

```php
<div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical" style="margin-top:10px;">
	<a class="nav-link active text-left" href="#">Opcion 1</a>
	<a class="nav-link text-left" href="#">Opcion 2</a>
	<a class="nav-link text-left" href="#">Opcion 3</a>
	<a class="nav-link text-left" href="#">Opcion 4</a>
	<a class="nav-link text-left" href="#">Opcion 5</a>
	<a class="nav-link text-left" href="#">Opcion 6</a>
	<a class="nav-link text-left" href="#">Opcion 7</a>
 </div>
```

Hay varias cosas que comentar en el codigo, primero se esta usando los cdn´s de bootstrap y jquery. ademas se usan algunas directivas de Blade, que pueden averiguar mas en la documentacion de Laravel, en este caso se usa @include y @yield. Para la primera directiva es simplemente incluir un fragmento html en otro, mientras que la directiva @yield incluye codigo html en una seccion determinada, en este caso en la seccion "content".

Tambien crea la carpeta "personas" y el archivo index.blade.php de tal forma que quede la ruta: resources/views/personas/index.blade.php. En esta misma ubicacion crea los archivos: create.blade.php, edit.blade.php y show.blade.php. Para cada uno el codigo es el siguiente:

<strong>index.blade.php</strong>
```php
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

```


<strong>create.blade.php</strong>
```php
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

```


<strong>edit.blade.php</strong>
```php
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
```


<strong>show.blade.php</strong>
```php
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
```

En xampp puedes ejecutar ingresando a localhost/gestion_personal/public/personas. Deberias poder acceder a las opciones; sin embargo hay muchas opciones que dejo de lado como poder cambiar el estado o mostrar el nombre de la oficina en el cuadro, esto no deberia complicarse mucho, con buen animo y paciencia todo se puede :D.
