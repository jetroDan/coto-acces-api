<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Registration;
use App\Invitation;
use App\User;
use Carbon\Carbon;
use Aws\Sns\SnsClient;
use Aws\Exception\AwsException;
use Aws\Credentials\Credentials;
use Log;
use Illuminate\Support\Facades\Crypt;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SendQr extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:qr';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send the user the QR code every day';

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
        return ;
        try {
        $invitations = Invitation::where('status',0)->where('visit_day','<=',Carbon::now()->toDateString())->get();
        foreach ($invitations as $key => $value) {
            # code...
            if ($value->visitor_type == 0) { //Visitante normal
                //generar codigo QR
                self::generateQr($value->registrations);
            } else if($value->visitor_type == 1 || $value->visitor_type == 2) { //Visitante recurrente
                if ($value->last_visit_day == null || $value->last_visit_day == NULL) {
                    //generar codido QR ??? Si es recurrente en fecha especifica, cuando se termina su generacion de QR's
                    self::generateQr($value->registrations);
                } else if ($value->specific_days != 0) { //tipo de fecha: dias especificos
                    //generar codigo qr
                    if(Carbon::now()->isMonday() && $value->monday != 0) {

                    self::generateQr($value->registrations);

                    } else if(Carbon::now()->isTuesday()  && $value->tuesday != 0) {

                    self::generateQr($value->registrations);

                    } else if(Carbon::now()->isWednesday()  && $value->wednesday != 0) {


                    self::generateQr($value->registrations);
                    } else if(Carbon::now()->isThursday()  && $value->thursday != 0) {

                    self::generateQr($value->registrations);

                    } else if(Carbon::now()->isFriday()  && $value->friday != 0) {


                    self::generateQr($value->registrations);
                    } else if(Carbon::now()->isSaturday()  && $value->saturday != 0) {

                    self::generateQr($value->registrations);

                    } else if(Carbon::now()->isSunday()  && $value->sunday != 0) {


                    self::generateQr($value->registrations);
                    }
                    self::generateQr($value->registrations);
                } else { //tipo de fecha: rango de fechas
                    //generar codigo qr

                self::generateQr($value->registrations);
                }


            } else if($value->visitor_type == 3) { //proveedor corporativo

            }
        }
        Log::debug('I finished generating QR successfully');
    }
        catch(\Exception $e){
            Log::debug('I crashed in handle Function with the following error: ' . $e->getMessage());

        }

        /*

        $invitations = Invitation::where('visit_day',Carbon::now()->toDateString())->get();
        foreach ($invitations as $key => $value) {
            $value->registrations;
            $qr = md5(Crypt::encryptString($value->registration->id+$value->registration->invitation_id)).md5("".$value->registration->invitation_id.$value->registration->id."");
            $value->registration->token = Crypt::encryptString($value->registration->id."-".$value->registration->invitation_id);
            QrCode::format('png')->size(500)->merge('/public/uploads/coto-logo.jpg', .3, false)->errorCorrection('H')->generate(Crypt::encryptString($value->registration->id."-".$value->registration->invitation_id), '../public/uploads/qrcodes/'.$qr.'.png');
            $value->registration->qr = '/uploads/qrcodes/'.$qr.'.png';
        }
        Log::debug($invitations);*/
        //
        /*
        Log::debug('Trying to send QR code from message using Amazon SNS ....');
        $user = User::find(60);
            $credentials = new Credentials(ENV('AWS_ACCESS_KEY_ID'), ENV('AWS_SECRET_ACCESS_KEY'));
            $SnSclient = new SnsClient([
                'region' => 'us-east-1',
                'version' => '2010-03-31',
                'credentials' => $credentials
            ]);

            $message = 'Mensaje task scheduling';
            $phone = $user->phone;

            try {
                $result = $SnSclient->publish([
                    'Message' => $message,
                    'PhoneNumber' => $phone,
                ]);
            } catch (AwsException $e) {
                // output error message if fails

                Log::debug('ERROR Trying to send QR code from message using Amazon SNS ....'. $e->getMessage());
                return $e->getMessage();
            }
            Log::debug('Send Qr has been send successfully');
            $this->info('Send Qr has been send successfully');
            */
    }

    public function generateQr($registrations)
    {
        try {
        # code...
        foreach ($registrations as $key => $registration) {
            # code...
            $qr = md5(Crypt::encryptString($registration->id+$registration->invitation_id)).md5("".$registration->invitation_id.$registration->id."");
            $registration->token = Crypt::encryptString($registration->id."-".$registration->invitation_id);
            QrCode::format('png')->size(500)->merge('/public/uploads/coto-logo.jpg', .3, false)->errorCorrection('H')->generate(Crypt::encryptString($registration->id."-".$registration->invitation_id), base_path().'/public/uploads/qrcodes/'.$qr.'.png');
            $registration->qr = '/uploads/qrcodes/'.$qr.'.png';
            $registration->save();
        }
    }
    catch(\Exception $e){

        Log::debug('I crashed in generateQT, with invitation id: '.$registrations[0].' Function with the following error: ' . $e->getMessage());

    }
    }
}
