<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Validator;
use App\Provider;
use Exception;

class ProviderController extends Controller
{
     //Metodo GET Listar registro
     public function list() {
         
        $providers = Provider::all();
          

        return response()->json([
            'status' => 'OK',
            'providers' => $providers
        ]);
    }

    public function store(Request $request)
    {
        try{

          $body = request()->all();

          $validator = Validator::make($body, [
            'company' => 'required|max:100',
            'phone' => 'required|max:100',
            'direction' => 'required|max:100',
            'user' => 'required|max:100',
          ]);

          if($validator->fails()){
              throw new Exception($validator->errors(),422);
          }

          $provider = new Provider;
          $provider->company_name = $body['company'];
          $provider->phone_number = $body['phone'];
          $provider->direction = $body['direction'];
          $provider->user_name = $body['user'];
          $provider->save();

          
        return response()->json([
            'status' => 'OK',
            'request' => request()->all()
            ]);

        }catch(\Exception $e){
            return response()->json([
                'error' => 'No se pudo completar el registro',
                'message' => $e->getMessage()
            ],400);
        }
    }

    public function update($id)
    //Metodo PUT Actualizar o hacer cambios
    {
        try {
            $body = request()->all();

            $validator = Validator::make($body, [
            'company' => 'required|max:100',
            'phone' => 'required|max:11',
            'direction' => 'required|max:100',
            'user' => 'required|max:100',
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors(), 422);
            }

            $provider = Provider::find($id);
            $provider->company_name = $body['company'];
            $provider->phone_number = $body['phone'];
            $provider->direction = $body['direction'];
            $provider->user_name = $body['user'];
            $provider->save();

            return response()->json([
                'status' => 'OK',
                'provider' => $provider
            ]);
        }
        catch(\Exception $e){
            return response()->json([
                'error' => 'No se pudo completar el registro',
                'message' => $e->getMessage()
            ],400);
        }
    }
    
}
