<?php

namespace App\Http\Controllers;

use App\Perfil;
use App\Receta;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PerfilController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth',['except'=>'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function show(Perfil $perfil)
    {
        
        //Obtener las recetas con paginacion
        $recetas = Receta::where('user_id', $perfil->user_id)->paginate(9);
        
        return view('perfil.show', compact('perfil','recetas'));
    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function edit(Perfil $perfil)
    {
        //Ejecutar Policy
        $this->authorize('update',$perfil);

        return view('perfil.edit', compact('perfil'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Perfil $perfil)
    {

        // dd($request['imagen']);
        //Ejecutar el Policy 
         $this->authorize('update',$perfil);


        //validacion
        $data = $request->validate([
            'nombre' =>  'required',
            'biografia' => 'required',
            'url' => 'required',
        ]);

        //Si el usuario sube una imagen
        if ( $request['imagen'] ) {

            //Obtener la ruta de la imagen
            $ruta_imagen = $request['imagen']->store('upload-perfiles', 'public');

            //Resize de la imagen 
            $img = Image::make(public_path("storage/{$ruta_imagen}"))->fit(600, 600);

            $img->save();
            
            //Crear un array de la imagen
            $array_imagen = ['imagen' => $ruta_imagen];

        }

        //Asignar nombre y url
        auth()->user()->url = $data['url'];
        auth()->user()->name = $data['nombre'];
        auth()->user()->save();

        //Eliminar url y name del $data
        unset($data['url']);
        unset($data['nombre']);

        //Guardar informacion

        //Asignar biografia e imagen
        auth()->user()->perfil()->update( array_merge(
            $data,
            $array_imagen ?? []
        ) );


        //redireccionar

        return redirect()->action('RecetaController@index');
    }

}
