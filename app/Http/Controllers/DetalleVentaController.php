<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\DetalleDetalleVenta;
use App\Models\DetalleVenta;
use App\Models\venta;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class DetalleVentaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $detalleVenta = DetalleVenta::where('status', 1)->get();

        return $detalleVenta;
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
            $detalleVenta = detalleVenta::findOrFail($id);
            $detalleVenta->load('cliente');
            $detalleVenta->load('trabajador');
            $detalleVenta->load('comprobante');
            $detalleVenta->load('detalle');

            return $detalleVenta;
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'El detalleVenta ' . $id . ' no existe no fue encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en la acción realizada'], 500);
        }
    }


    public function create(Request $requesta)
    {

        $datos = json_decode($requesta->input('jsonDatos'), true);
        $successMessages = [];
        foreach ($datos as $request) {


            try {

                $request['venta_id'] = venta::latest()->first()->id;

                $validator = validator($request, [
                    'venta_id' => 'required|exists:ventas,id',
                    'articulo_id' => 'required|exists:articulos,id',
                    'cantidad' => 'required|numeric',
                    'precio' => 'required|numeric',
                    'descuento' => 'required|numeric',
                    'status' => 'required|bool',
                ]);

                if ($validator->fails()) {
                    $successMessages[] = ['errors' => $validator->errors()];
                } else {

                    $Articulo = Articulo::findOrFail($request['articulo_id']);
                    if ($Articulo->strock >= $request['cantidad']) {

                        detalleVenta::create($request);

                        $Articulo->strock = $Articulo->strock - $request['cantidad'];
                        $Articulo->save();

                        response()->json(['msj' => 'DetalleVenta creado correctamente'], 200);
                        $successMessages[] = ['msj' => 'Articulo ' . $request['articulo_id'] . ' vendido correctamente'];
                    } else {
                        $successMessages[] = ['msj' => 'No hay stock suficiente para vender el articulo ' . $request['articulo_id']];
                    }
                }
            } catch (QueryException $e) {
                $errormsj = $e->getMessage();

                if (strpos($errormsj, 'Duplicate entry') !== false) {
                    preg_match("/Duplicate entry '(.*?)' for key/", $errormsj, $matches);
                    $duplicateValue = $matches[1] ?? '';

                    $successMessages[] = response()->json(['error' => "Error, No se puede realizar la accion, datos duplicado $duplicateValue"], 422);
                }
                $successMessages[] = response()->json(['error' => 'Error en la accion realizada: ' . $errormsj], 500);
            } catch (ModelNotFoundException $e) {
                $successMessages[] =  response()->json(['error' => 'No se pudo registrar el detalleVenta' . $e->getMessage()], 404);
            } catch (Exception $e) {
                $successMessages[] = response()->json(['error' => 'Error en la accion realizada' . $e->getMessage()], 500);
            }
        }
        return response()->json(['success' => $successMessages], 200);
    }

    public function update($id, Request $request)
    {

        try {
            $validator = validator($request->all(), [

                'venta_id' => 'required|exists:ventas,id',
                'articulo_id' => 'required|exists:articulos,id',
                'cantidad' => 'required|numeric',
                'precio' => 'required|numeric',
                'descuento' => 'required|numeric',
                'status' => 'required|bool',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }



            $detalleVenta = detalleVenta::findOrFail($id);
            $detalleVenta->update($request->all());

            $detalleVenta->save();




            return response()->json(['msj' => 'detalleVenta actualizado correctamente'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'El detalleVenta ' . $id . ' no existe no fue encontrado'], 404);
        } catch (QueryException  $e) {
            $errormsj = $e->getMessage();

            if (strpos($errormsj, 'Duplicate entry') !== false) {
                preg_match("/Duplicate entry '(.*?)' for key/", $errormsj, $matches);
                $duplicateValue = $matches[1] ?? 'Tienes un valor que';

                return response()->json(['error' => 'Error: ' . $duplicateValue . ' ya esta en uso'], 422);
            }

            return response()->json(['error' => 'Error en la acción realizada' . $errormsj], 500);
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
            $detalleVenta = detalleVenta::findOrFail($id);
            if ($detalleVenta->status) {
                $detalleVenta->status = 0;
                $detalleVenta->save();
                return response()->json(['msj' => 'DetalleVenta eliminado correctamente'], 200);
            }
            return response()->json(['msj' => 'Este DetalleVenta ya ha sido eliminado'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'El DetalleVenta ' . $id . ' no existe no fue encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en la acción realizada'], 500);
        }
    }
}
