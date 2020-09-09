<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\AdminExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Entry;
use App\User;
use Log;
use Carbon\Carbon;
class ExcelController extends Controller
{
    //
    public function entries(Request $request)
    {
        # code...
        $checkData = null;
        $user = User::find($request["id"]);
        if(isset($request["webHistory"]))
        {
            Log::debug('Web History');
            $checkData = Entry::where('id','>',1);
            if(isset($request["startDate"])) {
                Log::debug('start date '.$request["startDate"]);
                $checkData = $checkData->whereDate('entry_date','>=',$request["startDate"]);
            }
            if(isset($request["endDate"])) {
                Log::debug('end date '.$request["endDate"]);
                $checkData = $checkData->where('exit_date','<=',$request["endDate"]);
            }
            $checkData = $checkData->where(function($q) use($user) {
                $q->whereHas("entryguarddata",function($q) use ($user){
                    $q->where('coto_id', '=', $user->coto_id);
            })->orWhereHas("exitguarddata",function($q) use ($user){
                    $q->where('coto_id', '=', $user->coto_id);
            });
             });
        } else {
            $checkData = Entry::where('id','>',1);
            if(isset($request["startDate"])) {
                Log::debug('start date '.$request["startDate"]);
                $checkData = $checkData->whereDate('entry_date','>=',$request["startDate"]);
            }
            if(isset($request["endDate"])) {
                Log::debug('end date '.$request["endDate"]);
                $checkData = $checkData->where('exit_date','<=',$request["endDate"]);
            }
            $checkData = $checkData->where(function($q) use($user) {
                $q->whereHas("registration",function($q) use ($user){
                $q->whereHas("invitation",function($q) use ($user){
                $q->whereHas("user",function($q) use ($user){
                    $q->where('coto_id', '=', $user->coto_id);
                });
            });
            })->whereHas("entryguarddata",function($q) use ($user){
                $q->where('coto_id', '=', $user->coto_id);
        })->orWhereHas("exitguarddata",function($q) use ($user){
                $q->where('coto_id', '=', $user->coto_id);
        });
        });
    }
        $checkData = $checkData->orderBy('id','desc')->get();
        foreach ($checkData as $key => $value) {
            $value->exitguarddata;
            $value->entryguarddata;
            # code...
        }
        if(isset($request["webHistory"])){
            return $checkData;
        }
        $fileName = 'export.xls';
        ob_end_clean(); // this
        ob_start(); // and this
        Excel::store(new AdminExport($checkData), $fileName,'public');
    }
}
