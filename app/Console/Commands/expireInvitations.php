<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Invitation;
use Carbon\Carbon;

class expireInvitations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expire:invitations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expires all invitations to avoid listing them at users';

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
        $invitations = Invitation::where('status',0)->get();
        foreach ($invitations as $key => $value) {
            # code...
            $hour = $value->arrival_time[0].$value->arrival_time[1];
            $minutes = $value->arrival_time[3].$value->arrival_time[4];
            $expirationDate = Carbon::parse($value->visit_day . $value->arrival_time)->addHours($hour)->addMinutes($minutes);
            if(!$expirationDate->greaterThanOrEqualTo(Carbon::now())) {
                $value["status"] = 1;
                $value->save();
            }
        }
        //
        /*
        $invitations = Invitation::where('status',0)->get();
        foreach ($invitations as $key => $value) {
            # code...
            $hour = $value->arrival_time[0].$value->arrival_time[1];
            $minutes = $value->arrival_time[3].$value->arrival_time[4];
            $expirationDate = Carbon::parse($value->visit_day . $value->arrival_time)->addHours($hour)->addMinutes($minutes);
            if(!$expirationDate->greaterThanOrEqualTo(Carbon::now())) {
                $value["status"] = 1;
                $value->save();
            }
        } */
    }
}
