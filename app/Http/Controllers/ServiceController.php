<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Service;
use Exception;

class ServiceController extends Controller
{

    //Metodo GET Listar registro
    public function list() {
        $services = Service::all();
          

        return response()->json([
            'status' => 'OK',
            'services' => $services
        ]);
    }

    //Metodo POST Guardar
    public function store(Request $request)
    {
     
        try{

            $body = request()->all();

        $validator = Validator::make($body, [
            'name' => 'required|max:100',
            'type' => 'required|max:100',
        ]);

        // dd($result);

        if ($validator->fails()) {
            throw new Exception($validator->errors(), 422);
        }

        $service = new Service; 
        $service->type_service = $body['type'];
        $service->name_service = $body['name'];
        $service->save();


        return response()->json([
            'status' => 'OK',
            'request' => request()->all()
        ]);
    }
        catch(\Exception $e){
            return response()->json([
                'error' => 'No se pudo completar el registro',
                'message' => $e->getMessage()
            ], $e->getCode());
        }
        }
       

    public function update($id)
    //Metodo PUT Actualizar o hacer cambios
    {
        try {
            $body = request()->all();

            $validator = Validator::make($body, [
                'name' => 'required|max:100',
                'type' => 'required|max:100',
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors(), 422);
            }

            $service = Service::find($id);
            $service->name_service = $body['name'];
            $service->type_service = $body['type'];
            $service->save();

            return response()->json([
                'status' => 'OK',
                'service' => $service
            ]);
        }
        catch(\Exception $e){
            return response()->json([
                'error' => 'No se pudo completar el registro',
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

     
}
