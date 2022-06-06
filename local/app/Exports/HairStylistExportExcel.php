<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class HairStylistExportExcel implements FromView
{
    public function view(): View
    {

        $users = User::where('HairStylist','YES')->get();
        return view('admin.HairStylist.report-excel', compact(['users']));

    }
}
