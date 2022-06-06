<?php

namespace App\Exports;

use App\Models\DepositRequest;
use Hekmatinasser\Verta\Verta;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class WalletpayExport implements FromView
{
    public function view(): View
    {
        $items = DepositRequest::with('hairstylist')->where('status','Waiting')->get();

        $v = new Verta();
        $factor = $v->year . $v->month . $v->day . $v->second . rand(100, 999) . Auth::id();
        DepositRequest::where('status','Waiting')->update(['excel_id'=>$factor]);
        return view('admin.wallets.excel', compact(['items']));
    }
}
