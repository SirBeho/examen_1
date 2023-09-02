<?php

namespace App\Http\Controllers;

use App\Models\Tercero;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class TerceroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tercero = tercero::where('status', 1)->get();
        $tercero->load('documento');
        return $tercero;
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
            $tercero = tercero::findOrFail($id);
            $tercero->load('documento');

            return $tercero;
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'El tercero ' . $id . ' no existe no fue encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en la acción realizada'], 500);
        }

    }

    
    public function create(Request $request)
    {


       
        try {
            $request->merge([
                'sexo' => strtoupper($request->sexo)
            ]);
            
            $validator = validator($request->all(), [
                'nombre'=> 'required',
                'apellido'=> 'required',
                'sexo'=> 'required|in:M,F',
                'nacimiento'=> 'dete',
                'documento'=> 'required',
                'tipo_documento_id'=> 'required|exists:documentos,id',
                'direccion'=> 'required',
                'telefono'=> 'required',
                'email'=> 'required|email',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            
            tercero::create($request->all());
           
            return response()->json(['msj' => 'Tercero creado correctamente'], 200);
        
        } catch (QueryException $e) {
            $errormsj = $e->getMessage();

            if (strpos($errormsj, 'Duplicate entry') !== false) {
                preg_match("/Duplicate entry '(.*?)' for key/", $errormsj, $matches);
                $duplicateValue = $matches[1] ?? '';

                return response()->json(['error' => "Error, No se puede realizar la accion, datos duplicado $duplicateValue"], 422);
            }
            return response()->json(['error' => 'Error en la accion realizada: ' . $errormsj], 500);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'No se pudo registrar el tercero'.$e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error en la accion realizada' . $e->getMessage()], 500);
        }
    }

    public function update($id,Request $request)
    {
        
        try {
            $request->merge([
                'sexo' => strtoupper($request->sexo)
            ]);
            
            $validator = validator($request->all(), [
                'nombre'=> 'required',
                'apellido'=> 'required',
                'sexo'=> 'required|in:M,F',
                'nacimiento'=> 'dete',
                'documento'=> 'required',
                'tipo_documento_id'=> 'required|exists:documentos,id',
                'direccion'=> 'required',
                'telefono'=> 'required',
                'email'=> 'required|email',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $tercero = tercero::findOrFail($id);
            $tercero->update($request->all());
            $tercero->status = $request->status;
            $tercero->save();

           

            return response()->json(['msj' => 'tercero actualizado correctamente'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'El tercero ' . $id . ' no existe no fue encontrado'], 404);
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
            $tercero = tercero::findOrFail($id);
            if ($tercero->status) {
                $tercero->status = 0;
                $tercero->save();
                return response()->json(['msj' => 'Tercero eliminado correctamente'], 200);
            }
            return response()->json(['msj' => 'Este Tercero ya ha sido eliminado'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'El Tercero ' . $id . ' no existe no fue encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en la acción realizada'], 500);
        }
    }
}
