<?php

namespace Modules\Contact\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Contact\Entities\Contact;

class ContactController extends Controller
{
    public function contact()
    {
        if (admin()->can('contact_us')) {

            @$search=$_GET['search'];
            @$cView=$_GET['cView'];
            if ($cView){
                $pageNum=$cView;
            }else{
                $pageNum=10;
            }
            if ($search){

                if ($search=="انتشار" or $search=="پیش نویس" or $search=="انتشار" or $search=="پیشنویس" ){
                    if ($search=="انتشار"){
                        $status="PUBLISHED";
                    }elseif ($search=="پیش نویس"){
                        $status="DRAFT";
                    }elseif ($search=="پیشنویس"){
                        $status="DRAFT";
                    }
                    $items=Contact::where('status', 'like', '%'.$status.'%')->orderBy('id', 'desc')->paginate($pageNum);
                    $count_items=Contact::where('status', 'like', '%'.$status.'%')->orderBy('id', 'desc')->get();
                }else{
                    $items=Contact::where('email', 'like', '%'.$search.'%')->orderBy('id', 'desc')->paginate($pageNum);
                    $count_items=Contact::where('email', 'like', '%'.$search.'%')->orderBy('id', 'desc')->get();

                }
            }else{
                $items = Contact::orderBy('id', 'desc')->paginate($pageNum);
                $count_items = Contact::orderBy('id', 'desc')->get();
            }


            $title = "لیست تماس ها";
            $Active = "contact";
            $table = base64_encode('contacts');
            $pageNum = count($count_items);
            return view('contact::contact', compact(['items', 'Active', 'title', 'table', 'pageNum']));
        } else {
            abort(403);
        }
    }

    public function contact_store(Request $request)
    {
        $item=new Contact();
        //$item->title=$request->title;
        $item->message=$request->message;
        $item->status=$request->status;
        $item->save();
        session()->put('store-success', 'پیغام جدید با موفقیت اضافه شد');
        return redirect('/admin/messages');
    }
}
