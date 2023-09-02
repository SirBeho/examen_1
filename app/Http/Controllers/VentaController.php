<?php

namespace App\Http\Controllers;

use App\Models\DetalleVenta;
use App\Models\Venta;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $venta = venta::where('status', 1)->get();
        $venta->load('cliente');
        $venta->load('trabajador');
        $venta->load('comprobante'); 
        $venta->load('detalle'); 
        return $venta;
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
            $venta = venta::findOrFail($id);
            $venta->load('cliente');
            $venta->load('trabajador');
            $venta->load('comprobante');
            $venta->load('detalle'); 

            return $venta;
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'El venta ' . $id . ' no existe no fue encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en la acción realizada'], 500);
        }

    }

    
    public function create(Request $request)
    {

        try {
           
            $validator = validator($request->all(), [
                
                'serie'=> 'required',
                'cliente_id'=> 'required|exists:terceros,id',
                'trabajador_id'=> 'required|exists:trabajadores,id',
                'fecha'=> 'required|date',
                'comprobante_id'=> 'required|exists:comprobantes,id',
               
            ]);
    
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }


            

            venta::create($request->all());

           

            $jsonDatos = json_encode($request->detalle);


            return redirect()->route('detallecrear', compact('jsonDatos'));


            
 


       
             //$response = Http::get(redirect()->route('detallever',['id' => 5]));
        
           
            // return response()->json(['msj' => 'Venta creado correctamente y '.$response ], 200);
        
        } catch (QueryException $e) {
            $errormsj = $e->getMessage();

            if (strpos($errormsj, 'Duplicate entry') !== false) {
                preg_match("/Duplicate entry '(.*?)' for key/", $errormsj, $matches);
                $duplicateValue = $matches[1] ?? '';

                return response()->json(['error' => "Error, No se puede realizar la accion, datos duplicado $duplicateValue"], 422);
            }
            return response()->json(['error' => 'Error en la accion realizada: ' . $errormsj], 500);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'No se pudo registrar el venta'.$e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error en la accion realizada' . $e->getMessage()], 500);
        }
    }

    public function update($id,Request $request)
    {
        
        try {
           $validator = validator($request->all(), [
                
                'serie'=> 'required',
                'cliente_id'=> 'required|exists:terceros,id',
                'trabajador_id'=> 'required|exists:trabajadores,id',
                'fecha'=> 'required|date',
                'comprobante_id'=> 'required|exists:comprobantes,id',
               
            ]);
    
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

    
         
            $venta = venta::findOrFail($id);
            $venta->update($request->all());
            $venta->status = $request->status;
            $venta->save();

            
           

            return response()->json(['msj' => 'venta actualizado correctamente'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'El venta ' . $id . ' no existe no fue encontrado'], 404);
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
            $venta = venta::findOrFail($id);
            if ($venta->status) {
                $venta->status = 0;
                $venta->save();
                return response()->json(['msj' => 'Venta eliminado correctamente'], 200);
            }
            return response()->json(['msj' => 'Este Venta ya ha sido eliminado'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'El Venta ' . $id . ' no existe no fue encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en la acción realizada'], 500);
        }
    }
}
