<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Registration;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Notification;
use App\Invitation;
use App\User;
use App\Entry;
use Carbon\Carbon;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use Illuminate\Support\Facades\File;
use App\RestrictedUser;

class RegistrationController extends Controller
{
    //
    public function index(Request $request)
    {
        # code...
        $registrations = Registration::select('*');
        if (isset($request["id"]) && $request["id"] != null) {
            $registrations = $registrations->where('id',$request["id"]);
        }
        if (isset($request["invitation_id"]) && $request["invitation_id"] != null) {
            $registrations = $registrations->where('invitation_id',$request["invitation_id"]);
        }
        $registrations = $registrations->get();
        foreach ($registrations as $key => $value) {
            $value->invitation->user;
            if(isset($value["visit_duration"])) {
                $value->invitation["visit_duration"] = Carbon::createFromFormat('H:i:s',$value->invitation["visit_duration"])->toArray();
                $value->invitation["visit_duration"] = $value->invitation["visit_duration"]["hour"] . ' Horas y '.$value->invitation["visit_duration"]["minute"] . " minutos";
            }
            if($value->vehicle_entry == 1){
                $value->user->data;
            }
            # code...
        }

        return $registrations;
    }
    public function status(Request $request)
    {
        # code...
        $request =  json_decode(request()->getContent(), true);
        $registration = Registration::find($request["id"]);
        $registration->status = $request["status"];
        $registration->save();
        //if($request["status"] == 1 && Carbon::now()->between(Carbon::parse($registration->invitation->visit_day . '00:00:00'), Carbon::parse($registration->invitation->visit_day . '23:59:00')))
        if($request["status"] == 1)
        {
            $qr = md5(Crypt::encryptString($registration->id+$registration->invitation_id)).md5("".$registration->invitation_id.$registration->id."");
            $registration->token = Crypt::encryptString($registration->id."-".$registration->invitation_id);
            QrCode::format('png')->size(500)->merge('/public/uploads/coto-logo.jpg', .3, false)->errorCorrection('H')->generate(Crypt::encryptString($registration->id."-".$registration->invitation_id), '../public/uploads/qrcodes/'.$qr.'.png');
            $registration->qr = '/uploads/qrcodes/'.$qr.'.png';
            $registration->save();
            self::notify($registration->user,"Han aceptado tu registro para una invitación",'Registro Aceptado', $registration->id);
            /* $newUser = new User;
            $newUser->name = $registration->name;
            $newUser->phone = $registration->phone;
            $newUser->role_id = 5;
            $newUser->save();*/

        } else {
            self::notify($registration->user,"Han rechazado tu registro para una invitación",'Registro Rechazado', $registration->id);
        }

        return response()->json([
            'status' => 'OK'
        ]);

    }
    public function notify($user, $message, $title ,$id)
    {
        # code...

        $notification = new Notification;
        $notification->user_id = $user->id;
        $notification->title = $title;
        $notification->message = $message;
        $notification->data = $id;
        $notification->status = 0;
        $notification->save();

        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);
        $optionBuilder->setPriority('high');
        $optionBuilder->setContentAvailable(true);

        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($message)
                            ->setSound('default')
                            /*->setforceStart('1')*/;

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['a_data' => $id]);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $token = $user->fcm;
        try {
            $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
            $response = [];
            $response[0] = $downstreamResponse->numberSuccess();
            $response[1] = $downstreamResponse->numberFailure();
            $response[2] = $downstreamResponse->numberModification();
        }catch(\LaravelFCM\Response\Exceptions\InvalidRequestException $e) {
                return "error de token";
            }
    }

    public function verifyqr(Request $request)
    {
        # code...
        $request =  json_decode(request()->getContent(), true);
        try {
        $qr = Crypt::decryptString($request["token"]);
        } catch(\Exception $e) {
            return response()->json([
                'error' => 'error de token',
                'code' => 'invalid token'
            ]);

        }
         $pos = strpos($qr,"-");
        if($pos === FALSE)
            $type = "new";
        else{
            $id = substr($qr,0,$pos);
            $user_id = substr($qr,$pos+1);
        }
        try {
            $registration = Registration::with('invitation')->where('id',$id)->get();
            if($registration[0]->token === null) {
                return response()->json([
                    'error' => 'error de token',
                    'code' => 'invalid token'
                ]);
            }
            $guard = User::find($request["user_id"]);
            if($registration[0]->invitation->user->coto_id == $guard->coto_id)
            {
                $registration[0]->invitation->user;
                $registration[0]->user->data;
                return $registration;
            }
            else {
                return response()->json([
                    'error' => 'error de token',
                    'code' => 'invalid token'
                ]);
            }

        }
        catch(\Exception $e) {
            return response()->json([
                'error' => 'error de registro',
                'code' => 'invalid registry',
                'message' => $e->getMessage()
            ]);

        }
    }

    public function useQr(Request $request)
    {
        # code...
        $request =  json_decode(request()->getContent(), true);
        if($request["registration_type"] == '-1' || $request["registration_type"] == -1) {
            $registration = Invitation::find($request["registration_id"]);
            $registration->times_used = $registration->times_used + 1;
            $date = Carbon::now();
            if($registration->times_used == 1) {
                $entry = new Entry;
                $entry->registration_id = $request["registration_id"];
                $entry->entry_guard = $request["user_id"];
                $entry->entry_type = $request["entry_type"];
                $entry->cone = (isset($request["cone"]) ? $request["cone"] : null );
                $entry->entry_door = "Entrada";
                $entry->visitor_name = $registration->name;
                $entry->visitor_type = 3;
                $entry->entry_date = $date->toDateString();
                $entry->entry_time = $date->toTimeString();
                $entry->visited_address = $registration->user->address;
                $entry->visited_name = $registration->user->name;
                $entry->visit_motive = $registration->name;
                $entry->pre_registered = 0;
                $entry->save();
            }
            if($registration->times_used == 2) {
                $entry = Entry::where('registration_id',$request["registration_id"])->first();
                $entry->exit_guard = $request["user_id"];
                $entry->exit_door = "Salida";
                $entry->exit_date = $date->toDateString();
                $entry->exit_time = $date->toTimeString();
                $before = Carbon::parse($entry->entry_time);
                $entry->visit_time = $before->diff($date)->format('%H:%I:%S');
                $entry->save();
            }

        } else{
            $registration = Registration::find($request["registration_id"]);
            $registration->times_used = $registration->times_used + 1;
            $date = Carbon::now();
            if($registration->times_used == 1) {
                $entry = new Entry;
                $entry->registration_id = $request["registration_id"];
                $entry->entry_guard = $request["user_id"];
                $entry->entry_type = $request["entry_type"];
                $entry->cone = (isset($request["cone"]) ? $request["cone"] : null );
                $entry->entry_door = "Entrada";
                $entry->visitor_name = $registration->name;
                $entry->visitor_type = $registration->invitation->visitor_type;
                $entry->INE_url = 'http://phpstack-380196-1191838.cloudwaysapps.com' . $registration->INE_url;
                $entry->entry_date = $date->toDateString();
                $entry->entry_time = $date->toTimeString();
                $entry->pre_registered = 1;

                if ($registration->vehicle_entry == 1) {
                    $entry->vehicle_type = $registration->user->data->vehicle_type_id;
                    $entry->car_plates = $registration->user->data->license_plate;
                    $entry->car_color = $registration->user->data->car_color;
                    $entry->vehicle_type = $registration->user->data->vehicle_type_id;
                }
                $entry->visited_address = $registration->invitation->user->address;
                $entry->visited_name = $registration->invitation->user->name;
                $entry->visit_motive = $registration->invitation->name;
                $entry->save();
            }
            if($registration->times_used == 2) {
                $registration->qr = null;
                $registration->token = null;
                $entry = Entry::where('registration_id',$request["registration_id"])->first();
                $entry->exit_guard = $request["user_id"];
                $entry->exit_door = "Salida";
                $entry->exit_date = $date->toDateString();
                $entry->exit_time = $date->toTimeString();
                $before = Carbon::parse($entry->entry_time);
                $entry->visit_time = $before->diff($date)->format('%H:%I:%S');
                $entry->save();
            }
        }

        $registration->save();
        return response()->json([
            'status' => 'ok'
        ]);
    }

    public function registerVisitor(Request $request) {
        $date = Carbon::now();
        if(isset($request['id'])) {
            $entry = Entry::find($request['id']);
        } else {
            $entry = new Entry;
        }
        if($request["register_type"] == 1) {
            /* $entry = RestrictedUser::where('coto_id',User::find($request["user_id"])->coto_id)->where('name','like','%' . $request["name"]. '%')->first();
            if($entry) {
                return response()->json([
                    'restricted' => 'ok',
                    'user' => $entry
                ]);
            } */
            ini_set('memory_limit','256M');
            ini_set('upload_max_filesize','50M');
            ini_set('post_max_size','50M');
            if ($request->has('file')) {
                // Get image file
                $image = $request["file"];
                $date = Carbon::now();
                // Make a image name based on user name and current timestamp
                if ($request["file"]->getClientOriginalExtension()) {
                    $extension = '';
                    $name = $date->year . $date->month . $date->day . $date->micro . $date->timestamp . $request["file"]->getClientOriginalName();
                } else{
                    $extension = '.jpg';
                    $name = $name = $date->year . $date->month . $date->day . $date->micro . $date->timestamp .'INE';
                }

                // Define folder path
                $folder = '/uploads/images/';
                // Make a file path where image will be stored [ folder path + file name + file extension]
                $filePath = $folder . $name. $extension;
                // Upload image
                $this->uploadOne($image, $folder, 'public', $name, $extension);
                // Set user profile image path in database to filePath
                $entry->INE_url =  'http://phpstack-380196-1191838.cloudwaysapps.com' . $filePath;
            }

            $entry->entry_guard = $request["user_id"];
            $entry->entry_type = 1;
            $entry->entry_door = "Entrada";
            $entry->visitor_name = $request["name"];
            $entry->visitor_type = 0;
            $entry->entry_date = $date->toDateString();
            $entry->entry_time = $date->toTimeString();
            $entry->visited_address = $request["address"];
            $entry->visited_name = $request["visitor_name"];
            $entry->visit_motive = $request["motive"];
            $entry->cone = (isset($request["cono"]) ? $request["cono"] : null);
            $entry->pre_registered = 0;
            if ($request["vehicle_entry"] == 1) {
                $entry->vehicle_type = $request["vehicleType"];
                $entry->car_plates = $request["carPlates"];
                $entry->car_color = $request["carColor"];
            }
            $entry->save();
        }
        if($request["register_type"] == 2) {
            $entry->exit_guard = $request["user_id"];
            $entry->entry_type = 1;
            $entry->exit_door = "Salida";
            $entry->visitor_type = 0;
            $entry->exit_date = $date->toDateString();
            $entry->exit_time = $date->toTimeString();
            //$entry->visitor_name = $request["name"];
            //$entry->visited_address = $request["address"];
            //$entry->visited_name = $request["visitor_name"];
            //$entry->visit_motive = $request["motive"];
            $entry->pre_registered = 0;
            /* if ($request["vehicle_entry"] == 1) {
                $entry->vehicle_type = $request["vehicleType"];
                $entry->car_plates = $request["carPlates"];
                $entry->car_color = $request["carColor"];
            }*/
            $entry->save();
        }
        return response()->json([
            'status' => 'ok'
        ]);
    }

    public function searchHistory(Request $request)
    {
        # code...
        $entries  = Entry::where('exit_door',null);
        if(isset($request['visitor_name'])) {
            $entries = $entries->where('visitor_name', 'like', '%' . $request['visitor_name'] . '%');
        }
        if(isset($request['car_plates'])) {
            $entries = $entries->where('car_plates', 'like', '%' . $request['car_plates'] . '%');
        }
        $entries = $entries->get();

        return $entries;
    }
    public function searchPreRegistered(Request $request)
    {
        # code...
        $user = User::find($request["user_id"]);
        if(isset($request['exit'])) {
            $entries = Registration::where('status',1)->whereHas("invitation",function($q) use ($user){
                $q->whereHas("user",function($q) use ($user){
                    $q->where('coto_id', '=', $user->coto_id);
                });
            })->whereNotNull('qr');
        if(isset($request['visitor_name'])) {
            $entries = $entries->where('name', 'like', '%' . $request['visitor_name'] . '%')->where('times_used', 1);
        } else if (isset($request['car_plates'])) {
            $license = $request['car_plates'];
            $entries = Registration::where('status',1)->where('vehicle_entry', 1)->where('times_used', 1)->whereHas("invitation",function($q) use ($user, $license){
                $q->whereHas("user",function($q) use ($user, $license){
                    $q->where('coto_id', '=', $user->coto_id)->whereHas("data",function($q) use ($user, $license){
                        $q->where('license_plate', 'like', '%' . $license. '%');
                    });
                });
        });
    }
        } else {
            $entries = Registration::where('status',1)->whereHas("invitation",function($q) use ($user){
                $q->whereHas("user",function($q) use ($user){
                    $q->where('coto_id', '=', $user->coto_id);
                });
            })->whereNotNull('qr');
        if(isset($request['visitor_name'])) {
            $entries = $entries->where('name', 'like', '%' . $request['visitor_name'] . '%')->where('times_used', 0);
        } else if (isset($request['car_plates'])) {
            $license = $request['car_plates'];
            $entries = Registration::where('status',1)->where('vehicle_entry', 1)->where('times_used', 0)->whereHas("invitation",function($q) use ($user, $license){
                $q->whereHas("user",function($q) use ($user, $license){
                    $q->where('coto_id', '=', $user->coto_id)->whereHas("data",function($q) use ($user, $license){
                        $q->where('license_plate', 'like', '%' . $license. '%');
                    });
                });
        });
    }
        }

        $entries = $entries->get();
        foreach ($entries as $key => $value) {
            # code...
            $value->user->data;
            $value->invitation->user;
        }
        return $entries;
    }

    public function uploadOne(UploadedFile $uploadedFile, $folder = null, $disk = 'public', $filename = null, $extension)
    {
        $name = !is_null($filename) ? $filename : str_random(25);

        $file = $uploadedFile->storeAs($folder, $name . $extension, $disk);

        return $file;
    }

    public function reactivateQr(Request $request) {
        $request =  json_decode(request()->getContent(), true);
        $registration = Registration::find($request["id"]);
        $qr = md5(Crypt::encryptString($registration->id+$registration->invitation_id)).md5("".$registration->invitation_id.$registration->id."");
        $registration->token = Crypt::encryptString($registration->id."-".$registration->invitation_id);
        QrCode::format('png')->size(500)->merge('/public/uploads/coto-logo.jpg', .3, false)->errorCorrection('H')->generate(Crypt::encryptString($registration->id."-".$registration->invitation_id), '../public/uploads/qrcodes/'.$qr.'.png');
        $registration->qr = '/uploads/qrcodes/'.$qr.'.png';
        $registration->times_used = 0;
        $registration->save();
        self::notify($registration->user,"Han reactivado tu QR para acceder",'Registro Aceptado', $registration->id);
        /* $newUser = new User;
        $newUser->name = $registration->name;
        $newUser->phone = $registration->phone;
        $newUser->role_id = 5;
        $newUser->save();*/

        return response()->json([
            'status' => 'OK'
        ]);

    }
}
