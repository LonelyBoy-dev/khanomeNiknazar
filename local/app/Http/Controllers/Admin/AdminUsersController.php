<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UsersExportExcel;
use App\Http\Middleware\Admin;
use App\Http\Requests\User\UserEditRequest;
use App\Http\Requests\User\UserStoreRequest;
use App\Models\Address;
use App\Models\Avatar_user;
use App\Models\Comment;
use App\Models\Favorite;
use App\Models\User;
use Carbon\CarbonImmutable;
use Hekmatinasser\Verta\Verta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;

class AdminUsersController extends Controller
{

    public function index()
    {
        if (admin()->can('users')) {

            $avatar = Avatar_user::where('user_id', Admin()->id)->first();
            if (!empty($avatar->src)) {
                if (file_exists(public_path($avatar->src))) {
                    unlink(public_path($avatar->src));
                }
                Avatar_user::where('user_id', admin()->id)->delete();
            }

            @$search=$_GET['search'];
            @$cView=$_GET['cView'];
            if ($cView){
                $pageNum=$cView;
            }else{
                $pageNum=10;
            }
            if ($search){

                if ($search=="فعال" or $search=="غیر فعال" or $search=="فعال" or $search=="غیرفعال" ){
                    if ($search=="فعال"){
                        $status="ACTIVE";
                    }elseif ($search=="غیر فعال"){
                        $status="INACTIVE";
                    }elseif ($search=="غیرفعال"){
                        $status="INACTIVE";
                    }
                    $items=User::where('HairStylist','NO')->where('status',$status)->orderBy('id', 'desc')->paginate($pageNum);
                    $count_items=User::where('HairStylist','NO')->where('status',$status)->orderBy('id', 'desc')->get();
                }else{
                    $items=User::where('HairStylist','NO')->where('name', 'like', '%'.$search.'%')->orwhere('mobile', 'like', '%'.$search.'%')->orderBy('id', 'desc')->paginate($pageNum);
                    $count_items=User::where('HairStylist','NO')->where('name', 'like', '%'.$search.'%')->orwhere('mobile', 'like', '%'.$search.'%')->orderBy('id', 'desc')->get();

                }
            }else{
                $items = User::where('HairStylist','NO')->orderBy('id', 'desc')->paginate($pageNum);
                $count_items = User::where('HairStylist','NO')->orderBy('id', 'desc')->get();
            }

            $title = "کاربران";
            $Active = "users";
            $table = base64_encode('users');
            $pageNum = count($count_items);
            return view('admin.users.index', compact(['items', 'Active', 'title', 'table','pageNum']));
        } else {
            abort(403);
        }
    }

    public function create()
    {
        if (admin()->can('users')) {
            $img = Avatar_user::where('user_id', Admin()->id)->first();
            $title = "افزودن کاربر جدید";
            $Active = "users";
            $back_link = "users";
            $table = base64_encode('users');
            return view('admin.users.create', compact(['Active', 'title', 'table', 'img','back_link']));
        } else {
            abort(403);
        }
    }

    public function store(UserStoreRequest $request)
    {

        $item = new User();
        $item->name = $request->name;
        $item->mobile = $request->mobile;
        $item->sex = $request->sex;
        $item->email = $request->email;
        $item->avatar = $request->input('feature_image');
        $item->password = bcrypt($request->input('password'));

        if ($request->status == "") {
            $item->status = "INACTIVE";
        }
        $item->save();


        session()->put('store-success', 'کاربر جدید با موفقیت اضافه شد');
        return redirect()->back();

    }

    public function show($id)
    {
    }

    public function edit($id)
    {
        if (admin()->can('users')) {
            $user = User::withTrashed()->where('id', $id)->first();
            $addresses = Address::where('user_id', $id)->get();
            $comments = Comment::with('post','hairstylist','user','product')->where(['user_id'=> $id,'parent'=>'0'])->get();
            $favorites = Favorite::where('user_id', $id)->get();
            $title = "ویرایش کاربر";
            $Active = "users";
            $table = base64_encode('users');
            $back_link = "users";
            $users = User::all();
            return view('admin.users.edit', compact(['user', 'users', 'title', 'Active', 'table', 'addresses', 'comments', 'favorites', 'back_link']));
        } else {
            abort(403);
        }
    }

    public function update(UserEditRequest $request, $id)
    {
        $user = User::withTrashed()->where('id', $id)->first();
        $user->username = $request->username;
        $user->name = $request->name;
        $user->mobile = $request->mobile;
        //$user->sex = $request->sex;
        $user->email = $request->email;
        $user->wallet = $request->wallet;
        $user->avatar = $request->input('feature_image');
        if ($request->password != "") {
            $user->password = bcrypt($request->input('password'));
        }
        $user->save();
        session()->put('store-success', 'کاربر  با موفقیت ویرایش شد');
        return redirect()->back();


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */


    public function destroy($id)
    {
        //
    }


    public function report()
    {
        return Excel::download(new UsersExportExcel(), 'UsersReport.xlsx');
    }

}
