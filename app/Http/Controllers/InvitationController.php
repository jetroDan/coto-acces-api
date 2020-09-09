<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invitation;
use App\Registration;
use App\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use App\User;
use App\UserData;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

class InvitationController extends Controller
{
    //
    public function store(Request $request)
    {
        # code...
        /*
            Visior type:
                        0-> visitante
                        1-> visitante recurrente
                        2-> provedor recurrente (servicios)
                        3-> proveedor
        */
        $newInvitation = new Invitation;
        if($request["visitor_type"] == 0){
            $newInvitation->visit_day = $request["visitorVisitDay"];
            $newInvitation->invitation_type = $request["invitation_type"];
            $newInvitation->visitor_type = $request["visitor_type"];
            $newInvitation->visitor_phone = $request["visitorPhone"];
            $vowels = array("/", ".");
            $newInvitation->token = str_replace($vowels, "", Hash::make(md5(rand(1000,999999))));
            $newInvitation->name = $request["visitorName"];
            $newInvitation->visit_day = $request["visitorVisitDay"];
            $newInvitation->arrival_time = $request["visitorArrivalTime"];
            $newInvitation->departure_time = $request["visitorDepartureTime"];
            $newInvitation->visit_duration = $request["visitorDurationTime"];
            $newInvitation->visit_duration_days = $request["visitorDurationDays"];
            $newInvitation->recurring_visitor = $request["recurrent_visitor"];
            $newInvitation->last_visit_day = $request["recurrentVisitorLastVisitDay"];
            $newInvitation->daily = $request["daily"];
            $newInvitation->specific_days = $request["specificDays"];
            $newInvitation->motive = $request["recurrentVisitorMotive"];
            $newInvitation->indefinite_stay = $request["indefiniteStay"];
        }
        else if( $request["visitor_type"] == 1){
            $request["visitorDays"] = json_decode($request["visitorDays"],true);
            $newInvitation->monday = ( $request["visitorDays"][0]["selected"] ? 1 : 0 );
            $newInvitation->tuesday = ( $request["visitorDays"][1]["selected"] ? 1 : 0 );
            $newInvitation->wednesday = ( $request["visitorDays"][2]["selected"] ? 1 : 0 );
            $newInvitation->thursday = ( $request["visitorDays"][3]["selected"] ? 1 : 0 );
            $newInvitation->friday = ( $request["visitorDays"][4]["selected"] ? 1 : 0 );
            $newInvitation->saturday = ( $request["visitorDays"][5]["selected"] ? 1 : 0 );
            $newInvitation->sunday = ( $request["visitorDays"][6]["selected"] ? 1 : 0 );
            $newInvitation->visit_day = $request["visitorVisitDay"];
            $newInvitation->invitation_type = $request["invitation_type"];
            $newInvitation->visitor_type = $request["visitor_type"];
            $newInvitation->visitor_phone = $request["visitorPhone"];
            $vowels = array("/", ".");
            $newInvitation->token = str_replace($vowels, "", Hash::make(md5(rand(1000,999999))));
            $newInvitation->name = $request["visitorName"];
            $newInvitation->visit_day = $request["recurrentVisitorVisitDay"];
            $newInvitation->arrival_time = $request["recurrentVisitorArrivalTime"];
            $newInvitation->departure_time = $request["recurrentVisitorDepartureTime"];
            $newInvitation->visit_duration = $request["recurrentVisitorVisitDuration"];
            $newInvitation->visit_duration_days = $request["recurrentVisitorVisitDurationDays"];
            $newInvitation->recurring_visitor = $request["recurrent_visitor"];
            $newInvitation->last_visit_day = $request["recurrentVisitorLastVisitDay"];
            $newInvitation->daily = $request["daily"];
            $newInvitation->specific_days = $request["specificDays"];
            $newInvitation->motive = $request["recurrentVisitorMotive"];
            $newInvitation->indefinite_stay = $request["indefiniteStay"];
        }
        else if($request["visitor_type"] == 2) {
            $request["visitorDays"] = json_decode($request["visitorDays"],true);
            $newInvitation->monday = ( $request["visitorDays"][0]["selected"] ? 1 : 0 );
            $newInvitation->tuesday = ( $request["visitorDays"][1]["selected"] ? 1 : 0 );
            $newInvitation->wednesday = ( $request["visitorDays"][2]["selected"] ? 1 : 0 );
            $newInvitation->thursday = ( $request["visitorDays"][3]["selected"] ? 1 : 0 );
            $newInvitation->friday = ( $request["visitorDays"][4]["selected"] ? 1 : 0 );
            $newInvitation->saturday = ( $request["visitorDays"][5]["selected"] ? 1 : 0 );
            $newInvitation->sunday = ( $request["visitorDays"][6]["selected"] ? 1 : 0 );
            $newInvitation->visit_day = $request["visitorVisitDay"];
            $newInvitation->invitation_type = $request["invitation_type"];
            $newInvitation->visitor_type = $request["visitor_type"];
            $newInvitation->visitor_phone = $request["visitorPhone"];
            $vowels = array("/", ".");
            $newInvitation->token = str_replace($vowels, "", Hash::make(md5(rand(1000,999999))));
            $newInvitation->name = $request["visitorName"];
            /*$newInvitation->arrival_time = $request["visitorArrivalTime"];
            $newInvitation->departure_time = $request["visitorDepartureTime"];
            $newInvitation->visit_duration = $request["visitorDurationTime"];*/
            $newInvitation->visit_day = $request["recurrentVisitorVisitDay"];
            $newInvitation->arrival_time = $request["recurrentVisitorArrivalTime"];
            $newInvitation->departure_time = $request["recurrentVisitorDepartureTime"];
            $newInvitation->visit_duration = $request["recurrentVisitorVisitDuration"];
            $newInvitation->visit_duration_days = $request["recurrentVisitorVisitDurationDays"];
            $newInvitation->recurring_visitor = $request["recurrent_visitor"];
            $newInvitation->last_visit_day = $request["recurrentVisitorLastVisitDay"];
            $newInvitation->daily = $request["daily"];
            $newInvitation->specific_days = $request["specificDays"];
            $newInvitation->motive = $request["recurrentVisitorMotive"];
            $newInvitation->indefinite_stay = $request["indefiniteStay"];
        }
        else {
            $newInvitation->visit_day = $request["visitorVisitDay"];
            $newInvitation->invitation_type = $request["invitation_type"];
            $newInvitation->visitor_type = $request["visitor_type"];
            $newInvitation->visitor_phone = $request["visitorPhone"];
            $vowels = array("/", ".");
            $newInvitation->token = str_replace($vowels, "", Hash::make(md5(rand(1000,999999))));
            $newInvitation->name = $request["visitorName"];
            $newInvitation->arrival_time = $request["visitorArrivalTime"];
            $newInvitation->departure_time = $request["visitorDepartureTime"];
            $newInvitation->visit_duration = $request["visitorDurationTime"];
            $newInvitation->visit_day = $request["recurrentVisitorVisitDay"];
            $newInvitation->arrival_time = $request["recurrentVisitorArrivalTime"];
            $newInvitation->departure_time = $request["recurrentVisitorDepartureTime"];
            $newInvitation->visit_duration = $request["recurrentVisitorVisitDuration"];
            $newInvitation->visit_duration_days = $request["recurrentVisitorVisitDurationDays"];
            $newInvitation->recurring_visitor = $request["recurrent_visitor"];
            //$newInvitation->visit_motive = $request["recurrentVisitorMotive"];
            $newInvitation->last_visit_day = $request["recurrentVisitorLastVisitDay"];
            $newInvitation->daily = $request["daily"];
            $newInvitation->specific_days = $request["specificDays"];
            $newInvitation->motive = $request["recurrentVisitorMotive"];
            $newInvitation->indefinite_stay = $request["indefiniteStay"];

        }
        //$request =  json_decode(request()->getContent(), true);
        $newInvitation->user_id = $request["user_id"];
        $newInvitation->save();
        if($request["visitor_type"] == 3) {
            self::notifyGuard($newInvitation->user->coto_id, $newInvitation->id);
        }


        return response()->json([
            'status' => 'OK',
            'token' => $newInvitation->token,
            'date' => $newInvitation->visit_day,
            'time' => $newInvitation->arrival_time,
        ]);
    }

    public function index(Request $request)
    {
        # code...
        if(isset($request['token'])){
            $invitation = Invitation::where('token',$request['token'])->with('user')->get();
            if($invitation->isEmpty())
            {
                return response()->json([
                    'error' => 'No se ha encontrado invitación válida'
                ]);
            }
            /*
            if(!Carbon::parse($invitation[0]->visit_day . $invitation[0]->arrival_time)->greaterThanOrEqualTo(Carbon::now())) {
                return response()->json([
                    'expired' => 'No se ha encontrado invitación válida'
                ]);
            } */
            if (isset($request["user_id"])) {
                $invitation[0]["userData"] = User::where('id',$request['user_id'])->with('data')->first();
            }
            if($request["registration_id"] != '0' && $request["registration_id"] != 0 ) {
                $invitation[0]["registration_id"] = $request["registration_id"];

            } elseif(isset($request["user_id"])) {
                $registration = new Registration;
                $registration->invitation_id = $invitation[0]->id;
                $registration->user_id = (isset($request["user_id"]) ? $request['user_id'] : null);
                $registration->name = (isset($request["user_id"]) ? $invitation[0]["userData"]->name : null);
                $registration->phone = (isset($request["user_id"]) ? $invitation[0]["userData"]->phone : null);
                $registration->INE_url =  (isset($request["user_id"]) ? (isset($invitation[0]["userData"]["data"]) ? $invitation[0]["userData"]["data"]->INE_url : null) : null);
                $registration->save();
                $invitation[0]["registration_id"] = $registration->id;
            }

            return $invitation;

        }
        else if(isset($request["id"]) || isset($request["user_id"])){
            $invitations = Invitation::select('*')->withCount('registrations');
            if (isset($request["id"]) && $request["id"] != null) {
                $invitations = $invitations->where('id',$request["id"])->with('user');
            }
            if (isset($request["user_id"]) && $request["user_id"] != null && $request["user_id"] != '') {
                $invitations = $invitations->where('user_id',$request["user_id"]);
            }
            $invitations = $invitations->orderby('created_at','desc')->get();

            foreach ($invitations as $key => $value) {
                # code...
                $hour = $value->arrival_time[0].$value->arrival_time[1];
                $minutes = $value->arrival_time[3].$value->arrival_time[4];
                $expirationDate = Carbon::parse($value->visit_day . $value->arrival_time)->addHours($hour)->addMinutes($minutes);
                if($expirationDate->greaterThanOrEqualTo(Carbon::now())) {
                    $value["status"] = 0;
                    //$value["visit_duration"] = Carbon::parse($value["visit_duration"])->diffForHumans(null,CarbonInterface::DIFF_ABSOLUTE);
                    if(isset($value["visit_duration"])) {
                    $value["visit_duration"] = Carbon::createFromFormat('H:i:s',$value["visit_duration"])->toArray();
                    $value["visit_duration"] = $value["visit_duration"]["hour"] . ' Horas y '.$value["visit_duration"]["minute"] . " minutos";
                    }
                } else {
                    $value["status"] = 1;
                }
            }
            return $invitations;
        }
    }
    public function received(Request $request)
    {
        # code...
        if(isset($request['token'])){
            $invitation = Invitation::where('token',$request['token'])->get();
            if($invitation->isEmpty())
            {
                return response()->json([
                    'error' => 'No se ha encontrado invitación válida'
                ]);
            }
            else return $invitation;

        }
        else if(isset($request["id"]) || isset($request["user_id"])){
            $invitations = Registration::select('*');
            if (isset($request["user_id"]) && $request["user_id"] != null) {
                //$user = User::find($request["user_id"]);
                $invitations = $invitations->where('user_id',$request["user_id"]);
            }
            $invitations = $invitations->orderby('created_at','desc')->get();
            foreach ($invitations as $key => $value) {
                $hour = $value->invitation->arrival_time[0].$value->invitation->arrival_time[1];
                $minutes = $value->invitation->arrival_time[3].$value->invitation->arrival_time[4];
                $expirationDate = Carbon::parse($value->invitation->visit_day . $value->invitation->arrival_time)->addHours($hour)->addMinutes($minutes);
                if($expirationDate->greaterThanOrEqualTo(Carbon::now())) {
                    $value["invitation_status"] = 0;
                } else {
                    $value["invitation_status"] = 1;
                }
                $value->invitation->user;
                $value->invitation->arrival_time = Carbon::parse($value->invitation->arrival_time)->format('h:i:s A');
                Carbon::setLocale(config('app.locale'));
                $value->invitation->visit_day = Carbon::parse($value->invitation->visit_day)->format('d-m-Y');
            }
            return $invitations;
        }
    }

    public function uploadImage(Request $request) {
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

        }

        return response()->json([
            'status' => 'OK'
            //,'token' => $newInvitation->token
        ]);
    }

    public function uploadOne(UploadedFile $uploadedFile, $folder = null, $disk = 'public', $filename = null, $extension)
    {
        $name = !is_null($filename) ? $filename : str_random(25);

        $file = $uploadedFile->storeAs($folder, $name . $extension, $disk);

        return $file;
    }
    public function register(Request $request)
    {
        # code...
        if($request["registration_id"] != '0' && $request["registration_id"] != 0 &&  $request["registration_id"] != 'undefined') {
            $registration = Registration::find($request["registration_id"]);
        }
        else {
            $registration = new Registration;
        }
        if (isset($request["user_id"])) {
            $user = User::find($request["user_id"]);
        } else {
            $user = User::where('phone', '52'.$request["phone"])->first();
            if( $user === null) {
                $user = new User;
                $user->role_id = 5;
            }
        }
        $user->name = $request["name"];
        $user->phone = (strlen($request["phone"]) <= 10 ? '52'.$request["phone"] : $request["phone"] );
        $user->save();
        $userData = UserData::where('user_id',$user->id)->first();
        if( $userData === null) {
            $userData = new UserData;
            $userData->user_id = $user->id;
        }
        if ($request["hasVehicle"] == 'true') {
            $userData->vehicle_type_id = $request["vehicleType"];
            if($request["vehicleType"] != 5 && $request["vehicleType"] != 4) {
                $userData->license_plate = $request["carPlates"];
                $userData->car_brand = $request["carBrand"];
                $userData->car_model = $request["carModel"];
            }
            $userData->car_color = $request["carColor"];
        }
        $registration->user_id = $user->id;
        $registration->name = $request["name"];
        $registration->phone = (strlen($request["phone"]) <= 10 ? '52'.$request["phone"] : $request["phone"] );
        $registration->invitation_id = $request["id"];
        $registration->vehicle_entry = ($request["hasVehicle"] == 'true' ? 1 : 0);
        $registration->status = 0;

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
            $registration->INE_url =  $filePath;
            $userData->INE_url = $filePath;
            // Set user profile image path in database to filePath
        }

        $registration->save();
        $userData->save();

        self::test($registration->invitation_id,$registration->id);

        return response()->json([
            'status' => 'OK'
        ]);

    }
    public function notifyTest()
    {

        $notification = new Notification;
        $notification->user_id = '1';
        $notification->title = 'Nuevo registro';
        $notification->message = 'Hay nuevo registro desde tu invitación,  por favor revísalo para aprobarlo o rechazarlo';
        $notification->data = '1';
        $notification->status = 0;
        $notification->save();

        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);
        $optionBuilder->setPriority('high');
        $optionBuilder->setContentAvailable(true);

        $notificationBuilder = new PayloadNotificationBuilder('Nuevo registro');
        $notificationBuilder->setBody('Nuevo registro desde tu invitación')
                            ->setSound('default')
                            /*->setforceStart('1')*/;

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['a_data' => '1']);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $token = "dW2FVUjDku8:APA91bEdcRi59k2lthU6Md5fip8zM-NDKeay06AynpzE_p8P063kTXjbYGf2xbbwLgKSBWs8I4zB6ITr6kehP04RJhbqls3fhDXPqqygD7bkx8JRaL-idI2r4N2nM1ypaoS9Jf-5K6vC";
        try {
            $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
            $response = [];
            $response[0] = $downstreamResponse->numberSuccess();
            $response[1] = $downstreamResponse->numberFailure();
            $response[2] = $downstreamResponse->numberModification();
        } catch(\LaravelFCM\Response\Exceptions\InvalidRequestException $e) {
            return "error de token";
        }
    }

    public function test($invitation_id, $registration_id)
    {
        # code...
        $invitation = Invitation::find($invitation_id);

        $notification = new Notification;
        $notification->user_id = $invitation->user_id;
        $notification->title = 'Nuevo registro';
        $notification->message = 'Hay nuevo registro desde tu invitación,  por favor revísalo para aprobarlo o rechazarlo';
        $notification->data = $registration_id;
        $notification->status = 0;
        $notification->save();

        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);
        $optionBuilder->setPriority('high');
        $optionBuilder->setContentAvailable(true);

        $notificationBuilder = new PayloadNotificationBuilder('Nuevo registro');
        $notificationBuilder->setBody('Nuevo registro desde tu invitación')
                            ->setSound('default')
                            /*->setforceStart('1')*/;

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['a_data' => $registration_id]);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $token = $invitation->user->fcm;
        try {
            $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
            $response = [];
            $response[0] = $downstreamResponse->numberSuccess();
            $response[1] = $downstreamResponse->numberFailure();
            $response[2] = $downstreamResponse->numberModification();
        } catch(\LaravelFCM\Response\Exceptions\InvalidRequestException $e) {
            return "error de token";
        }
    }

    public function notifyGuard($coto_id, $id)
    {
        # code...
        $user = User::where('role_id',3)->where('coto_id',$coto_id)->first();

        $notification = new Notification;
        $notification->user_id = $user->id;
        $notification->title = 'Nueva visita';
        $notification->message = 'Hay una nueva visita agendada, revísala para que estés enterado';
        $notification->data = $id;
        $notification->data_type = 2;
        $notification->status = 0;
        $notification->save();

        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);
        $optionBuilder->setPriority('high');
        $optionBuilder->setContentAvailable(true);

        $notificationBuilder = new PayloadNotificationBuilder('Nueva visita');
        $notificationBuilder->setBody('Hay una nueva visita agendada, revísala para que estés entrerado')
                            ->setSound('default')
                            /*->setforceStart('1')*/;

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['a_data' =>'1']);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();
        try{
            $token = $user->fcm;
            $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
            $response = [];
            $response[0] = $downstreamResponse->numberSuccess();
            $response[1] = $downstreamResponse->numberFailure();
            $response[2] = $downstreamResponse->numberModification();
        } catch(\LaravelFCM\Response\Exceptions\InvalidRequestException $e) {
            return "error de token";
        }
    }

    public function entries(Request $request)
    {
        # code...
        $user = User::find($request["user_id"]);
        if ($request["type"] == 0) {
            $entries = Registration::where('status',1)->whereHas("invitation",function($q) use ($user){
                $q->whereHas("user",function($q) use ($user){
                    $q->where('coto_id', '=', $user->coto_id);
                });
            })->whereNotNull('qr')->get();
        } else {
            $entries = Invitation::where('invitation_type',1)->whereHas("user", function($q) use ($user){
                $q->where('coto_id', '=', $user->coto_id);
            })->get();
        }
        return $entries;
    }
}
