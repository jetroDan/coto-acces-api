<?php

namespace App\Http\Controllers;

use App\Business;
use Validator;
use Exception;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    public function list()
    {

        $business = Business::all();

        return response()->json([
            'status' => 'OK',
            'business' => $business
        ]);
    }
    public function store(Request $request)
    {
        try{

            $body = request()->all();

            $validator = Validator::make($body,[
            'name' => 'required|max:100',
            'direction' => 'required|max:50',
            'schedule_one' => 'required|max:50',
            'schedule_two' => 'required|max:50',
            'rfc' => 'required|max:100',
            'phone_number_one' => 'required|max:15',
            'phone_number_two'=> 'required|max:15',
            'web_page' => 'required|max:50',
            'category_of_services' => 'required|max:50',
            'type_of_service' => 'required|max:50',
            'price_range_one' => 'required|max:50',
            'price_range_two' => 'required|max:50',
            'way_to_pay' => 'required|max:50',
            'clabe' => 'required|max:5',
            'card_number' => 'required|max:16',
            'distance_limit' => 'required|max:100',
            ]);

            if($validator->fails()){
                throw new Exception($validator->errors(),422);
            }

            $business = new Business;
            $business->name = $body['name'];
            $business->direction = $body['direction'];
            $business->schedule_one = $body['schedule_one'];
            $business->schedule_two = $body['schedule_two'];
            $business->rfc = $body['rfc'];
            $business->phone_number_one = $body['phone_number_one'];
            $business->phone_number_two = $body['phone_number_two'];
            $business->web_page = $body['web_page'];
            $business->category_of_services = $body['category_of_services'];
            $business->type_of_service = $body['type_of_service'];
            $business->price_range_one = $body['price_range_one'];
            $business->price_range_two = $body['price_range_two'];
            $business->way_to_pay = $body['way_to_pay'];
            $business->clabe = $body['clabe'];
            $business->card_number = $body['card_number'];
            $business->distance_limit = $body['distance_limit'];
            $business->save();

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
            'name' => 'required|max:100',
            'direction' => 'required|max:50',
            'schedule_one' => 'required|max:50',
            'schedule_two' => 'required|max:50',
            'rfc' => 'required|max:100',
            'phone_number_one' => 'required|max:15',
            'phone_number_two'=> 'required|max:15',
            'web_page' => 'required|max:50',
            'category_of_services' => 'required|max:50',
            'type_of_service' => 'required|max:50',
            'price_range_one' => 'required|max:50',
            'price_range_two' => 'required|max:50',
            'way_to_pay' => 'required|max:50',
            'clabe' => 'required|max:5',
            'card_number' => 'required|max:16',
            'distance_limit' => 'required|max:100',
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors(), 422);
            }

            $business = Business::find($id);
            $business->name = $body['name'];
            $business->direction = $body['direction'];
            $business->schedule_one = $body['schedule_one'];
            $business->schedule_two = $body['schedule_two'];
            $business->rfc = $body['rfc'];
            $business->phone_number_one = $body['phone_number_one'];
            $business->phone_number_two = $body['phone_number_two'];
            $business->web_page = $body['web_page'];
            $business->category_of_services = $body['category_of_services'];
            $business->type_of_service = $body['type_of_service'];
            $business->price_range_one = $body['price_range_one'];
            $business->price_range_two = $body['price_range_two'];
            $business->way_to_pay = $body['way_to_pay'];
            $business->clabe = $body['clabe'];
            $business->card_number = $body['card_number'];
            $business->distance_limit = $body['distance_limit'];
            $business->save();

            return response()->json([
                'status' => 'OK',
                'business' => $business
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
