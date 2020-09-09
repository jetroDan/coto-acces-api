<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notification;
use App\User;
use App\UserToken;
use Carbon\Carbon;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        //$request =  json_decode(request()->getContent(), true);
        $notificationData = [];
        if(isset($request["notification_id"])) {
            $notification = Notification::find($request["notification_id"]);
            $notification["sender"] = User::find($notification->data);
            return $notification;
        }
        if(isset($request["count"]))
        {
            $notifications = Notification::where('user_id',$request["user_id"])->where('status',0)->get();
            if($notifications !== null) {
                return $notifications->count();
            } else {
                return 0;
            }
        } elseif (isset($request["id"])) {
            $notification = Notification::find($request["id"]);
            $notification->status = 1;
            $notification->save();
            return 0;
        } elseif (isset($request["all"])) {
            $notification = Notification::where('user_id', $request["user_id"])->get();
            foreach ($notification as $key => $value) {
                # code...
                $value->status = 1;
                $value->save();
            }
            return 0;
        }
        else {

            if(isset($request["web"])) {
                $notifications = Notification::where('user_id',$request["user_id"])->where('created_at','>=',Carbon::now()->subDay(3))->orderBy('id', 'desc');
            } else
            if(isset($request["sent"])) {
                $notifications = Notification::where('data',$request["user_id"])->where('data_type',1)->where('created_at','>=',Carbon::now()->subDay(3))->orderBy('id', 'desc');
            } else
            if(isset($request["oldSent"])) {
                $notifications = Notification::where('data',$request["user_id"])->where('data_type',1)->where('created_at','<=',Carbon::now()->subDay(3))->orderBy('id', 'desc');
            } else if(isset($request["oldReceived"])){
                $notifications = Notification::where('user_id',$request["user_id"])->where('created_at','<=',Carbon::now()->subDay(3))->orderBy('id', 'desc');
            } else {
                $notifications = Notification::where('user_id',$request["user_id"])->orderBy('id', 'desc');
            }
            $notifications = $notifications->get();
            foreach ($notifications as $key => $value) {
                $notificationData[$key]["id"] = $value->id;
                $notificationData[$key]["user_id"] = $value->user_id;
                $notificationData[$key]["title"] = $value->title;
                $notificationData[$key]["message"] = $value->message;
                $notificationData[$key]["data"] = $value->data;
                $notificationData[$key]["data_type"] = $value->data_type;
                $notificationData[$key]["status"] = $value->status;
                $notificationData[$key]["time"] = $value->created_at->diffForHumans();
            }
        }

        return $notificationData;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request =  json_decode(request()->getContent(), true);
        $user = User::find($request["user_id"]);
        $title = "Tienes un nuevo aviso de " . $user->name;
        $message = $request["message"];
        if ($request["users"] == -1 || $request["users"] == '-1') {
            $allUsers = User::where('coto_id',$user->coto_id)->where('role_id', $request["user_role"])->get();
            foreach ($allUsers as $key => $value) {
                $notification = new Notification;
                $notification->user_id = $value["id"];
                $notification->title = $title;
                $notification->message = $message;
                $notification->status = 0;
                $notification->data_type = 1;
                $notification->data = $user->id;
                $notification->save();
                self::notify(User::Find($value["id"])->fcm, $title, $message, $notification->id);

                $Webtokens = UserToken::where('user_id',$value["id"])->get();
                foreach ($Webtokens as $key2 => $value2) {
                    # code...
                    self::notify($value2["token"], $title, $message, $notification->id);
                }
            }
        } else {
            foreach ($request["users"] as $key => $value) {
                $notification = new Notification;
                $notification->user_id = $value["id"];
                $notification->title = $title;
                $notification->message = $message;
                $notification->status = 0;
                $notification->data_type = 1;
                $notification->data = $user->id;
                $notification->save();
                self::notify(User::Find($value["id"])->fcm, $title, $message, $notification->id);
                $Webtokens = UserToken::where('user_id',$value["id"])->get();
                foreach ($Webtokens as $key2 => $value2) {
                    # code...
                    self::notify($value2["token"], $title, $message, $notification->id);
                }
            }
        }
        return response()->json([
            'status' => 'ok'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function notify($fcm, $title, $message, $notification_id)
    {
        # code...

        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);
        $optionBuilder->setPriority('high');
        $optionBuilder->setContentAvailable(true);

        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($message)
                            ->setSound('default')
                            /*->setforceStart('1')*/;

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['a_data' =>$notification_id, 'type'=> '1']);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();
        try{
            $token = $fcm;
            $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
            $response = [];
            $response[0] = $downstreamResponse->numberSuccess();
            $response[1] = $downstreamResponse->numberFailure();
            $response[2] = $downstreamResponse->numberModification();
        } catch(\LaravelFCM\Response\Exceptions\InvalidRequestException $e) {
            return "error de token";
        }
    }
    public function newTestWeb()
    {
        # code...
        $title = "Tienes un nuevo aviso de";
        $message = "blah blah blah";
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);
        $optionBuilder->setPriority('high');
        $optionBuilder->setContentAvailable(true);

        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($message)
                            ->setSound('default')
                            /*->setforceStart('1')*/;

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['message' =>
        array (
          'token' => 'bk3RNwTe3H0:CI2k_HHwgIpoDKCIZvvDMExUdFQ3P1...',
          'notification' =>
          array (
            'title' => 'Portugal vs. Denmark',
            'body' => 'great match!',
          ),
          'data' =>
          array (
            'Nick' => 'Mario',
            'Room' => 'PortugalVSDenmark',
          ),
        )]);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();
        try{
            $token = "eNyOZXQL7xcsxZZ_9Pl5sV:APA91bGhkFHC7pGPDu5k6dnwuVxjRjyjxhz1_TbHyVpdyCKUv9SRT5VsTqnrBeDSPglovQgILnpqDyNOd7EX07eH0U_0up6YR3Ym0RQf8AHrg_6FErnsuXw11fgTVBI2D2eaACzecBL1";
            $downstreamResponse = FCM::sendTo($token, null, null, $data);
            $response = [];
            $response[0] = $downstreamResponse->numberSuccess();
            $response[1] = $downstreamResponse->numberFailure();
            $response[2] = $downstreamResponse->numberModification();
        } catch(\LaravelFCM\Response\Exceptions\InvalidRequestException $e) {
            return "error de token";
        }
    }

    public function registerToken(Request $request) {
        if (isset($request["token_id"]) && $request["token_id"] != 'undefined' && $request["token_id"] != '') {
            $token = UserToken::find($request["token_id"]);
            $token->user_id = $request["user_id"];
            $token->token = $request["token"];
            $token->save();
            return response()->json([
                'status' => 'ok',
                'token' =>  $token->token,
                'token_id' => $token->id
            ]);
        } else {
            $token = UserToken::firstOrNew(['token' =>  $request["token"]]);
            $token->user_id = $request["user_id"];
            $token->token = $request["token"];
            $token->save();
            return response()->json([
                'status' => 'ok',
                'token' =>  $token->token,
                'token_id' => $token->id
            ]);
        }
    }
}
