<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Coto;
use App\User;

class CotoController extends Controller
{
    public function index(Request $request)
    {
        # code...
        if (isset($request['id'])) {
            $cotos = Coto::where('id',$request['id'])->get();
        }else if($request['user_id']) {
            $user = User::find($request['user_id']);
            if($user->role_id == 1) {
                $cotos = Coto::all();
            } else {
                $cotos = Coto::where('id',$user->coto_id)->get();
            }
        } else {
            $cotos = Coto::all();
        }

        return $cotos;
    }
    //
    public function store(Request $request)
    {
    $request =  json_decode(request()->getContent(), true);
        try{
            $newCoto = new Coto;
            $newCoto->name = $request['name'];
            $newCoto->houseCount = $request['houses'];
            $newCoto->address = $request['address'];
            $newCoto->save();
            //$credentials = $request->only('email', 'password');
            //$token = JWTAuth::attempt($credentials);
            return response()->json([
                'status' => 'OK',
                'id' => $newCoto->id
            ]);
        }
        catch(\Exception $e){
            return response()->json([
                'error' => 'No se pudo completar el registro'
            ]);

        }
    }

    public function update(Request $request, $id)
    {
        # code...
        try{
            $request =  json_decode(request()->getContent(), true);
            $coto = Coto::find($id);
            $coto->name = $request['name'];
            $coto->houseCount = $request['houses'];
            $coto->address = $request['address'];
            $coto->save();
            return response()->json([
                'status' => 'OK',
                'id' => $coto->id
            ]);
        }
        catch(\Exception $e){
            return response()->json([
                'error' => 'No se pudo completar el registro'
            ]);
        }


    }
}
