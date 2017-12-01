<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Persona;
use App\Oficina;


class PersonaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {        
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
}
