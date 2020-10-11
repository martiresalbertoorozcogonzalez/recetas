<?php

namespace App\Http\Controllers;

use App\Receta;
use App\CategoriaReceta;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class InicioController extends Controller
{
    public function index()
    {

        //Mostrar recetas por cantidad de votos  
        //$votadas = Receta::has('likes', '>', 0)->get();
        $votadas = Receta::withCount('likes')->orderBy('likes_count','desc')->take(3)->get();

        //Obtener las recetas nuevas
        $nuevas = Receta::latest()->take(6)->get();

        // Obterner las recetas por categoria
        $categorias = CategoriaReceta::all();

        //Agragr las recetas a categoria
        $recetas = [];
        foreach ($categorias as $categoria) {
            $recetas[ Str::slug( $categoria->nombre )][] = Receta::where('categoria_id', $categoria->id)->take(3)->get();
        }

        // return $recetas; 

        return view('inicio.index',compact('nuevas','recetas','votadas'));
    }
}
