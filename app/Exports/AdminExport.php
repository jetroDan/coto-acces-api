<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Log;

class AdminExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($users)
    {
        $this->data = $users;
    }
    public function view(): View
    {
        //
        Log::debug($this->data);
        return view('excel.adminExport', [
            'data' => $this->data
        ]);
    }
}
