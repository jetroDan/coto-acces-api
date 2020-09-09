<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Carbon\Carbon;
use Log;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

class entriesAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'entry:alert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send alerts to guards if enough time has passed since their entry';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $entries = Entry::where('exit_guard',null)->get();
        foreach ($entries as $key => $value) {
            # code...
            $time = Carbon::createFromTimeString($value->entry_time);
            if(Carbon::now()->diffInMinutes($time) >= 15 ) {
                $users = User::where('coto_id', $value->entryguarddata->coto_id)->where('role_id',3)->get();
                foreach ($users as $key2 => $value2) {
                    # code...
                    self::notify($value2, 'Una visita lleva más tiempo de lo normal', 'Tiempo de visita excedido', 0);
                }
            }
        }
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
}
