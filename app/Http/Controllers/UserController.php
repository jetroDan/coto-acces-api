<?php

namespace App\Http\Controllers;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\User;
use App\RestrictedUser;
use App\Code;
use App\UserData;
use App\UserCoto;
use Aws\Sns\SnsClient;
use Aws\Exception\AwsException;
use Aws\Credentials\Credentials;
use Log;

class UserController extends Controller
{
    public function index(Request $request)
    {
        # code...
        $user = User::find($request["user_id"]);
        if(isset($request["my-account"])) {
            $user->data;
            return $user;
        } else {
            if($user->coto_id !== null) {
                if($request["role_id"] == '1' || $request["role_id"] == 1)
                {
                    $tenants = User::select('*')->where('role_id',$request["role_id"])->get();
                } else {
                    $tenants = User::select('*')->where('coto_id',$request["coto_id"])->where('role_id',$request["role_id"])->with('coto')->get();
                }
            } else {
                if(($user->role_id == '1' || $user->role_id == 1) && $request['coto_id'] == '')
                {
                    $tenants = User::select('*')->where('role_id',$request["role_id"])->get();
                } else {
                    $tenants = User::select('*')->where('role_id',$request["role_id"])->where('coto_id',$request['coto_id'])->with('coto')->get();
                }
            }
        }

        return $tenants;
    }
    //
    public function store(Request $request)
    {
        //
        $request =  json_decode(request()->getContent(), true);
        try{
            $coto = (isset($request['coto']) ? $request['coto'] : null);
            /* try {
                foreach (json_decode($request["coto"], true) as $key => $value) {
                    # code...
                    $coto = $value["id"];
                }
            }
            catch(\Exception $e){
                //return $e;
            } */
                $user = User::where('phone',$request['phone'])->orWhere('phone', '52'.$request['phone'])->first();
                if($user) {
                    return response()->json([
                        'error' => 'already exists',
                        'code' => 'already exists'
                    ]);
                }

            $newUser = new User;
            $newUser->name = (isset($request['name']) ? $request['name'] : null);
            $newUser->phone = '52'.$request['phone'];
            $newUser->address = (isset($request['address']) ? $request['address'] : null);
            $newUser->nss = (isset($request['nss']) ? $request['nss'] : null);;
            $newUser->username =(isset($request['username']) ? strtolower($request['username']) : null);
            $newUser->password =(isset($request['password']) ?  Hash::make($request['password']) : null);
            $newUser->role_id = $request['type'];
            $newUser->coto_id = $coto;
            $newUser->save();
            //$credentials = $request->only('email', 'password');
            //$token = JWTAuth::attempt($credentials);
            return response()->json([
                'status' => 'OK',
                'id' => $newUser->id,
                'user_role' => $newUser->role_id
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if($errorCode == 1062){
                return response()->json([
                    'error' => 'error de registro',
                    'code' => 'duplicated'
                ]);
            }
        } catch(\Exception $e) {
            return response()->json([
                'error' => 'error de registro',
                'code' => 'register',
                'message' => $e->getMessage()
            ]);

        }
    }

    public function update(Request $request, $id)
    {
        # code...

        $request =  json_decode(request()->getContent(), true);
        try{
            $coto = (isset($request['coto']) ? $request['coto'] : null);
            /* try {
                foreach (json_decode($request["coto"], true) as $key => $value) {
                    # code...
                    $coto = $value["id"];
                }
            }
            catch(\Exception $e){
                //return $e;
            } */

            $newUser = User::find($request['user_id']);

                if(strlen($request['phone']) > 10) {
                    if($newUser->phone != $request['phone']) {
                        $user = User::where('phone',$request['phone'])->orWhere('phone', '52'.$request['phone'])->first();
                        if($user) {
                            return response()->json([
                                'error' => 'already exists',
                                'code' => 'already exists'
                            ]);
                        }
                    }
                    $newUser->phone != $request['phone'];
                } else {
                    if($newUser->phone != '52'.$request['phone']) {
                        $user = User::where('phone',$request['phone'])->orWhere('phone', '52'.$request['phone'])->first();
                        if($user) {
                            return response()->json([
                                'error' => 'already exists',
                                'code' => 'already exists'
                            ]);
                        }
                    }
                    $newUser->phone != '52'.$request['phone'];
                }


            $newUser->name = (isset($request['name']) ? $request['name'] : null);
            $newUser->phone = '52'.$request['phone'];
            $newUser->address = (isset($request['address']) ? $request['address'] : null);
            $newUser->nss = (isset($request['nss']) ? $request['nss'] : null);;
            $newUser->username =(isset($request['username']) ? strtolower($request['username']) : null);
            $newUser->password =(isset($request['password']) ?  Hash::make($request['password']) : null);
            $newUser->role_id = $request['type'];
            $newUser->coto_id = $coto;
            $newUser->save();
            //$credentials = $request->only('email', 'password');
            //$token = JWTAuth::attempt($credentials);
            return response()->json([
                'status' => 'OK',
                'id' => $newUser->id,
                'user_role' => $newUser->role_id
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if($errorCode == 1062){
                return response()->json([
                    'error' => 'error de registro',
                    'code' => 'duplicated'
                ]);
            }
        } catch(\Exception $e) {
            return response()->json([
                'error' => 'error de registro',
                'code' => 'register',
                'message' => $e->getMessage()
            ]);

        }

    }

    public function authenticate(Request $request)
    {
        if(isset($request["phone"])) {
            $user = User::where('phone','52'.$request["phone"])->get();
            if($user->isEmpty()) {
                return response()->json([
                    'error' => 'No encontramos un usuario con esas credenciales'
                ]);
            }
            $user = $user[0];
        }
        else {
        $credentials = $request->only('username', 'password');

            try {
                // attempt to verify the credentials and create a token for the user
                if (! $token = JWTAuth::attempt($credentials)) {
                    return response()->json([
                        'error' => 'No hay un usuario con esas credenciales'
                    ]);
                }
            } catch (JWTException $e) {
                // something went wrong whilst attempting to encode the token
                return response()->json(['error' => 'could_not_create_token'], 500);
            }
            $user = JWTAuth::toUser($token);
        }

        $userInfo["id"] = $user->id;
        $userInfo["name"] = $user->name;
        $userInfo["coto_id"] = (isset($user->coto) ? $user->coto->id : '');
        $userInfo["coto"] =  (isset($user->coto) ? $user->coto->name : '');
        $userInfo["phone"] = $user->phone;
        $userInfo['user_role'] = $user->role_id;

        return $userInfo;

        // all good so return the token
        //return response()->json(compact('token'));
    }

    public function test(Request $request)
    {
        # code...
        $data = [];
        $user = User::find(11);
        foreach($user->invitations as $value){
            $value->registrations;
        }
        return $user;
    }

    public function userToken(Request $request)
    {
        $request =  json_decode(request()->getContent(), true);
        if (isset($request["token"]) && $request["token"] !== '') {
            $user = User::find($request["id"]);
            $user->fcm = $request["token"];
            $user->save();
        }
        return response()->json([
            'status' => 'OK'
        ]);

    }
    public function uploadOne(UploadedFile $uploadedFile, $folder = null, $disk = 'public', $filename = null, $extension)
    {
        $name = !is_null($filename) ? $filename : str_random(25);

        $file = $uploadedFile->storeAs($folder, $name . $extension, $disk);

        return $file;
    }

    public function updateUserInfo(Request $request) {
        $user = User::find($request["user_id"]);
        $userData = UserData::where('user_id',$request["user_id"])->first();
        if ($userData== null) {
            $userData = new UserData;
            $userData->user_id = $request["user_id"];
        }
        # code...
        $user->name = $request["name"];
        if(strlen($request["phone"]) <= 10) {
            $user->phone = '52'.$request["phone"];
        } else {
            $user->phone = $request["phone"];
        }
        if ($request->has('file')) {
            // Get image file
            //$request["file"] = base64_decode($request["file"]);
            //$image = base64_decode($request["file"]);
            $image = $request["file"];
            $date = Carbon::now();
            // Make a image name based on user name and current timestamp
                $extension = '.jpg';
                $name = $name = $date->year . $date->month . $date->day . $date->micro . $date->timestamp .'INE';


            // Define folder path
            $folder = '/uploads/images/';
            // Make a file path where image will be stored [ folder path + file name + file extension]
            $filePath = $folder . $name. $extension;
            // Upload image
            //Storage::disk('public')->put($name.$extension, base64_decode($image));
            $this->uploadOne($image, $folder, 'public', $name, $extension);
            $userData->INE_url =  $filePath;
            // Set user profile image path in database to filePath
        }
        $user->save();
        if (isset($request["vehicleType"])) {
            $userData->vehicle_type_id = $request["vehicleType"];
            $userData->car_color = $request["carColor"];
            if($request["vehicleType"] != 5 && $request["vehicleType"] != 4) {
                $userData->car_model = $request["carModel"];
                $userData->license_plate = $request["carPlates"];
                $userData->car_brand = $request["carBrand"];
            }
        }
        $userData->save();

        return response()->json([
            'status' => 'OK'
        ]);
    }
    public function phonetest(Request $request)
    {
        # code...
        $available = false;
        do {
            $code = mt_rand(100000, 999999);
            if (!Code::where('code',$code)->first()) {
                $available = true;
            }
        } while(!$available);

        $newCode = new Code;
        $newCode->code = $code;
        $newCode->save();

        $credentials = new Credentials(ENV('AWS_ACCESS_KEY_ID'), ENV('AWS_SECRET_ACCESS_KEY'));
        $SnSclient = new SnsClient([
            'region' => 'us-east-1',
            'version' => '2010-03-31',
            'credentials' => $credentials
        ]);

        $message = 'Este es tu código de verificación de Acceso WAppido T-Sec: '.$code;
        $phone = '+52'.$request["phone"];

        try {
            $result = $SnSclient->publish([
                'Message' => $message,
                'PhoneNumber' => $phone,
            ]);
            return response()->json([
                'id' => $newCode->id
            ]);
        } catch (AwsException $e) {
            // output error message if fails
            return $e->getMessage();
        }
    }

    public function phonetest2(Request $request)
    {
        # code...

        $code = Code::find($request["id"]);
        if ($code->code == $request["code"]) {
            $code->delete();
            return response()->json([
                'status' => "ok"
            ]);
        } else {
            return response()->json([
                'error' => "incorrect code"
            ]);
        }
    }

    public function getCotos(Request $request)
    {
        # code...
        $guard = User::find($request["user_id"]);
        $allCotos = User::where('coto_id',$guard->coto_id)->get();
        return $allCotos;
    }
    public function restrictUser(Request $request) {
        $newRestricted = new RestrictedUser;
        $newRestricted->name = $request["name"];
        $newRestricted->phone = $request["phone"];
        $newRestricted->coto_id = $request["coto_id"];
        $newRestricted->car_plates = (isset($request["car_plates"]) ? $request["car_plates"] : null);
        $newRestricted->motive = $request["motive"];
        if ($request->has('file')) {
            // Get image file
            //$request["file"] = base64_decode($request["file"]);
            //$image = base64_decode($request["file"]);
            $image = $request["file"];
            $date = Carbon::now();
            // Make a image name based on user name and current timestamp
                $extension = '.jpg';
                $name = $name = $date->year . $date->month . $date->day . $date->micro . $date->timestamp .'INE';


            // Define folder path
            $folder = '/uploads/images/';
            // Make a file path where image will be stored [ folder path + file name + file extension]
            $filePath = $folder . $name. $extension;
            // Upload image
            //Storage::disk('public')->put($name.$extension, base64_decode($image));
            $this->uploadOne($image, $folder, 'public', $name, $extension);
            $newRestricted->INE_url =  $filePath;
            // Set user profile image path in database to filePath
        }
        $newRestricted->save();
        return response()->json([
            'status' => "ok"
        ]);
    }

    public function getRestricted(Request $request)
    {
        # code...
        $restricted = RestrictedUser::where('coto_id',$request['coto_id']);

        if(isset($request['name'])) {

        $restricted = $restricted->where('name','like','%'.$request['name'].'%');
        }
        $restricted = $restricted->get();
        return $restricted;
    }

}
