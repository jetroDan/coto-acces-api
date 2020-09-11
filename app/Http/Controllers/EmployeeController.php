<?php

namespace App\Http\Controllers;

use App\Employee;
use Exception;
use Validator;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list() {

        $employee = Employee::all();

        return response()->json([
            'status' => 'OK',
            'employee' => $employee
        ]);
    }

   

    public function store(Request $request)
    {
        try{

            $body = request()->all();

            $validator = Validator::make($body,[
                'name' => 'required|max:100',
                'direction' => 'required|max:100',
                'phone' => 'required|max:100',
            ]);

            if($validator->fails()){
                throw new Exception($validator->errors(),422);
            }

            $employee = new Employee;
            $employee->name = $body['name'];
            $employee->direction = $body['direction'];
            $employee->phone_number = $body['phone'];
            $employee->save();

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
        {
            try{
                $body = request()->all();

                $validator = Validator::make($body,[
                    'name' => 'required|max:100',
                    'direction' => 'required|max:100',
                    'phone' => 'required|max:100',
                ]);
                if ($validator->fails()) {
                    throw new Exception($validator->errors(), 422);
                }

            $employee = Employee::find($id);
            $employee->name = $body['name'];
            $employee->direction = $body['direction'];
            $employee->phone_number = $body['phone'];
            $employee->save();

            
            return response()->json([
                'status' => 'OK',
                'employee' => $employee
                ]);

            }catch(\Exception $e){
                return response()->json([
                    'error' => 'No se pudo completar el registro',
                    'message' => $e->getMessage()
                ], $e->getCode());
            }
        }
  
}
