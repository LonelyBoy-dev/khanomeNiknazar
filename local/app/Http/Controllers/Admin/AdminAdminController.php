<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UsersExportExcel;

use App\Http\Requests\Admin\AdminEditRequest;
use App\Http\Requests\Admin\AdminProfileUpdateRequest;
use App\Http\Requests\Admin\AdminStoreRequest;
use App\Models\Admin;
use App\Models\Address;
use App\Models\Avatar_user;
use App\Models\Comment;
use App\Models\Favorite;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RoleHasPermission;
use App\Models\User;
use Carbon\CarbonImmutable;
use Hekmatinasser\Verta\Verta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;

class AdminAdminController extends Controller
{

    public function index()
    {
        if (admin()->can('admins')) {

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
                    $items=Admin::where('id','!=','1')->where('status', $status)->orderBy('id', 'desc')->paginate($pageNum);
                    $count_items=Admin::where('id','!=','1')->where('status',$status)->orderBy('id', 'desc')->get();
                }else{
                    $items=User::where('name', 'like', '%'.$search.'%')->orwhere('mobile', 'like', '%'.$search.'%')->orderBy('id', 'desc')->paginate($pageNum);
                    $count_items=User::where('id','!=','1')->where('name', 'like', '%'.$search.'%')->orwhere('mobile', 'like', '%'.$search.'%')->orderBy('id', 'desc')->get();

                }
            }else{
                $items = Admin::where('id','!=','1')->orderBy('id', 'desc')->paginate($pageNum);
                $count_items = Admin::where('id','!=','1')->orderBy('id', 'desc')->get();
            }

            $title = "لیست مدیران";
            $Active_list="tools";
            $Active = "admins";
            $table = base64_encode('admins');
            $pageNum = count($count_items);
            return view('admin.admins.index', compact(['Active_list','items', 'Active', 'title', 'table','pageNum']));
        } else {
            abort(403);
        }
    }

    public function create()
    {
        if (admin()->can('admins')) {
            $img = Avatar_user::where('user_id', Admin()->id)->first();
            $title = "افزودن مدیر جدید";
            $Active_list="tools";
            $Active = "admins";
            $back_link = "admins";
            $table = base64_encode('admins');
            return view('admin.admins.create', compact(['Active', 'title', 'table', 'img','back_link']));
        } else {
            abort(403);
        }
    }

    public function store(AdminStoreRequest $request)
    {
        $item = new Admin();
        $item->name = $request->name;
        //$item->lastname = $request->lastname;
        $item->mobile = $request->mobile;
        $item->sex = $request->sex;
        $item->email = $request->email;
        $item->Biography = $request->Biography;
        $item->password = bcrypt($request->input('password'));
        $item->avatar = $request->input('feature_image');
        if ($request->status == "") {
            $item->status = "INACTIVE";
        }
        $item->save();

        session()->put('store-success', 'مدیر جدید با موفقیت اضافه شد');
        return redirect(route('admins.index'));


    }

    public function edit($id)
    {
        if (admin()->can('admins')) {
            $user = Admin::withTrashed()->where('id', $id)->first();
            $title = "ویرایش مدیر";
            $Active_list="tools";
            $Active = "admins";
            $table = base64_encode('admins');
            $back_link = "admins";
            $items = Admin::all();
            return view('admin.admins.edit', compact(['user', 'items', 'title', 'Active', 'table','back_link','Active_list']));
        } else {
            abort(403);
        }
    }

    public function update(AdminEditRequest $request, $id)
    {
        $item = Admin::withTrashed()->where('id', $id)->first();
        $item->name = $request->name;
        //$item->lastname = $request->lastname;
        $item->mobile = $request->mobile;
        $item->sex = $request->sex;
        $item->email = $request->email;
        $item->Biography = $request->Biography;
        $item->avatar = $request->input('feature_image');
        if ($request->password != "") {
            $item->password = bcrypt($request->input('password'));
        }
        $item->save();
        session()->put('store-success', 'مدیر با موفقیت ویرایش شد');
        return redirect(route('admins.edit', $id));

    }

    public function permission($id)
    {
        if ($id!=1){
            if (admin()->can('admins')) {
                $role=Role::where('admin_id',$id)->first();
                if (!$role){
                    $new_role=new Role();
                    $new_role->name="admin-".$id;
                    $new_role->guard_name="admin";
                    $new_role->admin_id=$id;
                    $new_role->save();
                }

                $user = Admin::withTrashed()->where('id', $id)->first();
                $title = "ویرایش دسترسی ها";
                $Active_list="tools";
                $Active = "admins";
                $back_link = "admins";
                $table = base64_encode('admins');
                $items = Permission::where('parent','0')->get();
                return view('admin.admins.permissions', compact(['back_link','user', 'items', 'title', 'Active', 'table','Active_list','id']));
            } else {
                abort(403);
            }
        }else {
            abort(403);
        }

    }

    public function permission_store(Request $request,$id)
    {
        $role_id=Role::where('admin_id',$id)->first();
        RoleHasPermission::where(['role_id'=>$role_id->id])->delete();
        if ($request->permission!=null){
            foreach ($request->permission as $item){
                $new_permission=new RoleHasPermission();
                $new_permission->permission_id =$item;
                $new_permission->role_id=$role_id->id;
                $new_permission->save();
            }

        }
        $user=Admin::find($id);
        $user->assignRole($role_id->name);
        session()->put('store-success', 'دسترسی ها با موفقیت ویرایش شدند');
        return redirect('/admin/admins/permissions/'.$id);
    }

    public function profile_index()
    {

        $user = Admin::withTrashed()->where('id', Admin()->id)->first();
        $title = "پروفایل";
        $Active_list="";
        $Active = "";
        $table = base64_encode('admins');
        $back_link = "dashboard";
        $items = Admin::all();
        return view('admin.admins.profile', compact(['user', 'items', 'title', 'Active', 'table','back_link','Active_list']));

    }

    public function profile_update(AdminProfileUpdateRequest $request)
    {
        $item = Admin::withTrashed()->where('id', Admin()->id)->first();
        $item->name = $request->name;
        //$item->lastname = $request->lastname;
        $item->mobile = $request->mobile;
        $item->sex = $request->sex;
        $item->email = $request->email;
        $item->Biography = $request->Biography;
        if ($request->password != "") {
            $item->password = bcrypt($request->input('password'));
        }
        $item->save();
        session()->put('store-success', 'پروفایل شما با موفقیت ویرایش شد');
        return redirect()->back();

    }
}
