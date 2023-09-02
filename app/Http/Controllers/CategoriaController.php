<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {

        $categorias = Categoria::where('status', 1)->get();
        return $categorias;

    }


    public function create(Request $post)
    {

        $validator = validator($post->all(), [
            'descripcion' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $nuevaCategoria = new Categoria();
            $nuevaCategoria->descripcion = $post->descripcion;

            $nuevaCategoria->save();

            return "El registro se creo correctamente";
        } catch (Exception $e) {
            return "Bad Request";
        }
    }


    public function show($id)
    {
        try {
            $categoria = Categoria::findOrFail($id);

            return $categoria;
        } catch (QueryException $e) {
            return "Bad Request";
        } catch (ModelNotFoundException $e){
            return response()->json(['error'=> "la categoria $id no existe"]);
        }
    }


    public function update(Request $body, $id)
    {
        $validator = validator($body->all(), [
            'descripcion' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $categoria = Categoria::findOrFail($id);

            if (isset($categoria)) {
                $categoria->descripcion = $body->descripcion;

                $categoria->save();
                return "El registro $id se actualizo correctamente";
            } else {
                return "Bad Request";
            }
        } catch (QueryException $e) {
            return "Bad Request";
        } catch (ModelNotFoundException $e){
            return response()->json(['error'=> "la categoria $id no existe"]);
        }
    }

    public function destroy($id)
    {
        try {
            $categoria = Categoria::find($id);

            if (isset($categoria)) {
                $categoria->status = false;
                $categoria->save();
                return "El registro se elimino correctamente";
            } else {
                return "El registro no existe";
            }
        } catch (QueryException $e) {
            return "Bad request";
        }
    }
}
