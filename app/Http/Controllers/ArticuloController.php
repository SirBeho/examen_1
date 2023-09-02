<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ArticuloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articulo = articulo::where('status', 1)->get();
        $articulo->load('categoria');
        return $articulo;
    }

    
    public function show($id)
    {

        $validator = validator(['id' => $id], [
            'id' => 'required|numeric'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $articulo = articulo::findOrFail($id);
            $articulo->load('categoria');

            return $articulo;
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'El articulo ' . $id . ' no existe no fue encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en la acción realizada'], 500);
        }

    }

    
    public function create(Request $request)
    {


       
        try {
          
            $validator = validator($request->all(), [
                'codigo'=> 'required',
                'nombre'=> 'required',
                'descripcion'=> 'required',
                'precio_c'=> 'required|numeric',
                'precio_v'=> 'required|numeric',
                'strock'=> 'required|numeric',
                'categoria_id'=> 'required|exists:categorias,id',
               
            ]);
    
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            
            articulo::create($request->all());
           
            return response()->json(['msj' => 'Articulo creado correctamente'], 200);
        
        } catch (QueryException $e) {
            $errormsj = $e->getMessage();

            if (strpos($errormsj, 'Duplicate entry') !== false) {
                preg_match("/Duplicate entry '(.*?)' for key/", $errormsj, $matches);
                $duplicateValue = $matches[1] ?? '';

                return response()->json(['error' => "Error, No se puede realizar la accion, datos duplicado $duplicateValue"], 422);
            }
            return response()->json(['error' => 'Error en la accion realizada: ' . $errormsj], 500);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'No se pudo registrar el articulo'.$e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error en la accion realizada' . $e->getMessage()], 500);
        }
    }

    public function update($id,Request $request)
    {
        
        try {
            $validator = validator($request->all(), [
                'codigo'=> 'required',
                'nombre'=> 'required',
                'descripcion'=> 'required',
                'precio_c'=> 'required|numeric',
                'precio_v'=> 'required|numeric',
                'strock'=> 'required|numeric',
                'categoria_id'=> 'required|exists:categorias,id',
               
            ]);
    
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

    
         
            $articulo = articulo::findOrFail($id);
            $articulo->update($request->all());
            $articulo->status = $request->status;
            $articulo->save();

           

            return response()->json(['msj' => 'articulo actualizado correctamente'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'El articulo ' . $id . ' no existe no fue encontrado'], 404);
        } catch (QueryException  $e) {
            $errormsj = $e->getMessage();

            if (strpos($errormsj, 'Duplicate entry') !== false) {
                preg_match("/Duplicate entry '(.*?)' for key/", $errormsj, $matches);
                $duplicateValue = $matches[1] ?? 'Tienes un valor que';

                return response()->json(['error' => 'Error: ' . $duplicateValue . ' ya esta en uso'], 422);
            }

            return response()->json(['error' => 'Error en la acción realizada'.$errormsj], 500);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error en la acción realizada'], 500);
        }
    }

    public function destroy($id)
    {
        $validator = validator(['id' => $id], [
            'id' => 'required|numeric'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $articulo = articulo::findOrFail($id);
            if ($articulo->status) {
                $articulo->status = 0;
                $articulo->save();
                return response()->json(['msj' => 'Articulo eliminado correctamente'], 200);
            }
            return response()->json(['msj' => 'Este Articulo ya ha sido eliminado'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'El Articulo ' . $id . ' no existe no fue encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en la acción realizada'], 500);
        }
    }
}
