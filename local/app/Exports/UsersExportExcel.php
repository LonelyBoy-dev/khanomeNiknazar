<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class UsersExportExcel implements FromView
{

    public function view(): View
    {

        $users = User::where('HairStylist','NO')->get();
        return view('admin.users.report', compact(['users']));

    }
}
