<?php

namespace App\Http\Controllers;

use App\CategoriaReceta;
use App\Receta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpKernel\Event\ViewEvent;

class RecetaController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth', ['except' => ['show','search']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // Auth::user()->recetas->dd();
        // $recetas =  auth()->user()->recetas;

       $usuario = auth()->user();

       //Recetas con paginacion
       $recetas = Receta::where('user_id',$usuario->id)->paginate(5);

        return view('recetas.index')
              ->with('recetas',$recetas)
              ->with('usuario',$usuario);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // DB::table('categoria_receta')->get()->pluck('nombre','id')->dd();
        
        //Obtener las categorias sin modal
        // $categorias = DB::table('categoria_recetas')->get()->pluck('nombre','id');
  
        //con modelo
        $categorias = CategoriaReceta::all(['id','nombre']);

        return view('recetas.create')->with('categorias',$categorias);
    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // dd( $request['imagen']->store('upload-recetas', 'public'));

        //validacion
        $data = $request->validate([
            'titulo' =>  'required|min:6',
            'categoria' => 'required',
            'preparacion' => 'required',
            'ingredientes' => 'required',
            'imagen' => 'required|image',
        ]);

        //Obtener la ruta de la imagen
        $ruta_imagen = $request['imagen']->store('upload-recetas','public');

        //Resize de la imagen 
        $img = Image::make(public_path("storage/{$ruta_imagen}"))->fit(1220,550);
        $img->save();

        //Almacenar en la DB sin modelo
        // DB::table('recetas')->insert([
        //      'titulo' => $data['titulo'] ,
        //      'preparacion' => $data['preparacion'], 
        //      'ingredientes' => $data['ingredientes'], 
        //      'imagen' => $ruta_imagen,
        //      'user_id' => Auth::user()->id ,
        //      'categoria_id' => $data['categoria'] 
        // ]);


        //Almacenar en la DB con modelo
        auth()->user()->recetas()->create([
            'titulo' => $data['titulo'] ,
            'preparacion' => $data['preparacion'], 
            'ingredientes' => $data['ingredientes'], 
            'imagen' => $ruta_imagen,
            'categoria_id' => $data['categoria']
        ]);


        return redirect()->action('RecetaController@index');
  
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function show(Receta $receta)
    {
        
        // Obtener si el usuario le gusta la receta y esta autenticado
        $like = ( auth()->user()) ? auth()->user()->meGusta->contains($receta->id) : false;

        // Pasa la cantoidad de likes a la vista
        $likes = $receta->likes->count();

        return view('recetas.show',compact('receta','like','likes'));
    }

    /**
     * Show the form for editing the specified resource.
     * 
     * @param  \App\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function edit(Receta $receta)
    {

        $categorias = CategoriaReceta::all(['id','nombre']);

        return view('recetas.edit',compact('categorias','receta'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Receta $receta)
    {

        //Revisar el policy
        $this->authorize('update', $receta);

        //validacion
        $data = $request->validate([
            'titulo' =>  'required|min:6',
            'categoria' => 'required',
            'preparacion' => 'required',
            'ingredientes' => 'required',
        ]);

        //Asiganr los valores
        $receta->titulo = $data['titulo'];
        $receta->preparacion = $data['preparacion'];
        $receta->ingredientes = $data['ingredientes'];
        $receta->categoria_id = $data['categoria'];

        // Si el usuario sube un anueva imagen 
        if (request('imagen')) {
            
        //Obtener la ruta de la imagen
        $ruta_imagen = $request['imagen']->store('upload-recetas','public');

        //Resize de la imagen 
        $img = Image::make(public_path("storage/{$ruta_imagen}"))->fit(1220,550);

        $img->save();

        //Asiganr al objeto
        $receta->imagen = $ruta_imagen;
    }

        $receta->save();

        //redireccionar
        return redirect()->action('RecetaController@index');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Receta $receta)
    {
        //Ejecutar el Policy
        $this->authorize('delete',$receta);

        //Eliminar la receta
        $receta->delete();        

        return redirect()->action('RecetaController@index');
    }

    
    public function search(Request $request)
    {
        // $busqueda = $request['buscar'];
        $busqueda = $request->get('buscar');

        $recetas = Receta::where('titulo', 'like', '%' . $busqueda . '%')->paginate(1);
        $recetas->appends(['buscar' => $busqueda]);

        return view('busquedas.show',compact('recetas','busqueda'));
    }

}
