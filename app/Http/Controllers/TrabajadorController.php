<?php

namespace App\Http\Controllers;

use App\Models\Trabajador;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class TrabajadorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $trabajador = trabajador::where('status', 1)->get();
        $trabajador->load('tercero.documento');
        return $trabajador;
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
            $trabajador = trabajador::findOrFail($id);
            $trabajador->load('tercero');

            return $trabajador;
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'El trabajador ' . $id . ' no existe no fue encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en la acción realizada'], 500);
        }

    }

    
    public function create(Request $request)
    {


       
        try {
           
            
            $validator = validator($request->all(), [
                
                'tercero_id'=> 'required|exists:terceros,id',
                'usuario'=> 'required',
                'contra'=> 'required',
               
            ]);
    
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            
            trabajador::create($request->all());
           
            return response()->json(['msj' => 'Trabajador creado correctamente'], 200);
        
        } catch (QueryException $e) {
            $errormsj = $e->getMessage();

            if (strpos($errormsj, 'Duplicate entry') !== false) {
                preg_match("/Duplicate entry '(.*?)' for key/", $errormsj, $matches);
                $duplicateValue = $matches[1] ?? '';

                return response()->json(['error' => "Error, No se puede realizar la accion, datos duplicado $duplicateValue"], 422);
            }
            return response()->json(['error' => 'Error en la accion realizada: ' . $errormsj], 500);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'No se pudo registrar el trabajador'.$e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error en la accion realizada' . $e->getMessage()], 500);
        }
    }

    public function update($id,Request $request)
    {
        
        try {
            $validator = validator($request->all(), [
                'tercero_id'=> 'required|exists:terceros,id',
                'usuario'=> 'required',
                'contra'=> 'required',
            ]);
    
    
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            
            $trabajador = trabajador::findOrFail($id);
            $trabajador->update($request->all());
            $trabajador->status = $request->status;
            $trabajador->save();

           

            return response()->json(['msj' => 'trabajador actualizado correctamente'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'El trabajador ' . $id . ' no existe no fue encontrado'], 404);
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
            $trabajador = trabajador::findOrFail($id);
            if ($trabajador->status) {
                $trabajador->status = 0;
                $trabajador->save();
                return response()->json(['msj' => 'Trabajador eliminado correctamente'], 200);
            }
            return response()->json(['msj' => 'Este Trabajador ya ha sido eliminado'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'El Trabajador ' . $id . ' no existe no fue encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en la acción realizada'], 500);
        }
    }
}
