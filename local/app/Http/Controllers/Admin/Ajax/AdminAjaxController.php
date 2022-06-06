<?php

namespace App\Http\Controllers\Admin\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Admin;
use App\Models\Avatar_user;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\CategoryUser;
use App\Models\Comment;
use App\Models\DepositRequest;
use App\Models\Favorite;
use App\Models\Gallery;
use App\Models\Package;
use App\Models\Role;
use App\Models\Slider;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminAjaxController extends Controller
{
    public function numberToword(Request $request)
    {
        $Number2Word=new \Number2Word();
        @$Number2Word=@$Number2Word->numberToWords($request->number);
        return response([
            'word'=>$Number2Word.' تومان '
        ]);
    }

    public function delete_all_items_and_change_status(Request $request)
    {
        $table = base64_decode($request['table']);

        if ($request->Select_Action_Show=="delete"){

            if ($request->softDelete=="yes") {
                if ($table=="posts"){

                    DB::table('post_post_category')->where('post_id', $request['id'])->delete();
                    DB::table('post_post_exam')->where('post_id', $request['id'])->delete();
                    DB::table('post_post_module')->where('post_id', $request['id'])->delete();
                    DB::table('post_post_notexam')->where('post_id', $request['id'])->delete();
                    DB::table('post_post_score')->where('post_id', $request['id'])->delete();

                }
                if ($table == "users") {

                    foreach ($request['id'] as $id) {
                        $item = User::withTrashed()->where('id',$id)->first();
                        if ($item->avatar != "") {
                            if (file_exists(public_path() . '/' . $item->avatar)) {
                                unlink(public_path() . '/' . $item->avatar);
                            }
                        }

                        CategoryUser::where('user_id',$id)->delete();
                        Gallery::where('user_id',$id)->delete();
                        Comment::where('user_id',$id)->delete();
                        Comment::where('post_id',$id)->delete();
                        Ticket::where('user_id',$id)->delete();
                        Favorite::where('user_id',$id)->delete();
                        Address::where('user_id',$id)->delete();
                    }
                }
                if ($table=="comments"){
                    foreach ($request['id'] as $id) {
                        $deleted = DB::delete('delete from ' . $table . ' where parent=?', [$id]);
                    }
                }
                if ($table=="admins"){

                    foreach ($request['id'] as $id) {
                        $item = Admin::withTrashed()->where('id',$id)->first();
                        if ($item->avatar != "") {
                            if (file_exists(public_path() . '/' . $item->avatar)) {
                                unlink(public_path() . '/' . $item->avatar);
                            }
                        }
                        $permission = Role::where('admin_id', $id)->first();
                        if ($permission!=""){
                            $deleted = DB::table('role_has_permissions')->where('role_id', $permission->id)->delete();
                            $permission->delete();
                        }

                    }

                }
                if ($table=="tickets"){

                    foreach ($request['id'] as $id) {
                        $item = Ticket::where('id',$id)->first();

                        if ($item->file != "") {
                            if (file_exists(public_path() . '/' . $item->file)) {
                                unlink(public_path() . '/' . $item->file);
                            }
                        }
                        if ($item->file2 != "") {
                            if (file_exists(public_path() . '/' . $item->file2)) {
                                unlink(public_path() . '/' . $item->file2);
                            }
                        }
                        $item2 = Ticket::where('parent',$id)->first();
                        if ($item2){
                            if (@$item2->file != "") {
                                if (file_exists(public_path() . '/' . $item2->file)) {
                                    unlink(public_path() . '/' . $item2->file);
                                }
                            }
                            if (@$item2->file2 != "") {
                                if (file_exists(public_path() . '/' . $item2->file2)) {
                                    unlink(public_path() . '/' . $item2->file2);
                                }
                            }

                            $item2->delete();
                        }

                        $item->delete();
                    }

                    echo 'deleted';
                }

                $deleted = DB::table($table)->whereIn('id', $request['id'])->delete();
            }else{
                foreach ($request['id'] as $id) {
                    $deleted = DB::table($table)->where('id', $id)->update(['deleted_at' => Carbon::now()->format('Y-m-d H:m:s')]);
                }
            }
            if ($deleted) {
                echo 'deleted';
            }

        }
        elseif ($request->Select_Action_Show=="changeStatusFalse" or $request->Select_Action_Show=="changeStatusTrue"){
            foreach ($request['id'] as $id) {
                if ($request->Select_Action_Show=="changeStatusFalse"){
                    $status="INACTIVE";
                }else{
                    $status="ACTIVE";
                }
                DB::table($table)->where('id', $id)->update(['status' => $status]);
            }

            return response([
                'msg'=>'changeStatus',
                'status'=>$status
            ]);
        }
        elseif ($request->Select_Action_Show=="SEEN" or $request->Select_Action_Show=="UNSEEN"){
            foreach ($request['id'] as $id) {
                if ($request->Select_Action_Show=="UNSEEN"){
                    $status="UNSEEN";
                }else{
                    $status="SEEN";
                }
                DB::table($table)->where('id', $id)->update(['status' => $status]);
            }

            return response([
                'msg'=>'changeStatus',
                'status'=>$status
            ]);
        }
        elseif ($request->Select_Action_Show=="ACTIVE" or $request->Select_Action_Show=="INACTIVE"){
            foreach ($request['id'] as $id) {
                DB::table($table)->where('id', $id)->update(['status' => $request->Select_Action_Show]);
            }

            return response([
                'msg'=>'changeStatus',
                'status'=>$request->Select_Action_Show
            ]);
        }
        elseif ($request->Select_Action_Show=="PUBLISHED" or $request->Select_Action_Show=="DRAFT"){
            foreach ($request['id'] as $id) {
                DB::table($table)->where('id', $id)->update(['status' => $request->Select_Action_Show]);
            }

            return response([
                'msg'=>'changeStatus',
                'status'=>$request->Select_Action_Show
            ]);
        }
        elseif ($request->Select_Action_Show=="stop" or $request->Select_Action_Show=="waiting" or $request->Select_Action_Show=="answer"){
            foreach ($request['id'] as $id) {
                DB::table($table)->where('id', $id)->update(['status' => $request->Select_Action_Show]);
                DB::table($table)->where('parent', $id)->update(['status' => $request->Select_Action_Show]);
            }

            return response([
                'msg'=>'changeStatus',
                'status'=>$request->Select_Action_Show
            ]);
        }
        elseif ($request->Select_Action_Show=="Pay" or $request->Select_Action_Show=="Waiting" or $request->Select_Action_Show=="NotPay"){
            foreach ($request['id'] as $id) {
                $DepositRequest=DepositRequest::find($id);
                if ($request->Select_Action_Show=="NotPay"){
                    $user=User::find($DepositRequest->hairstylist_id);
                    $user->wallet= $user->wallet+$DepositRequest->price;
                    $user->save();
                }
                $DepositRequest->status=$request->Select_Action_Show;
                $DepositRequest->save();
                //DB::table($table)->where('id', $id)->update(['status' => $request->Select_Action_Show]);
            }

            return response([
                'msg'=>'changeStatus',
                'status'=>$request->Select_Action_Show
            ]);
        }
        elseif ($request->Select_Action_Show=="restore_trash"){
            foreach ($request['id'] as $id) {
                DB::table($table)->where('id', $id)->update(['deleted_at' => null]);
            }

            return response([
                'msg'=>'restore_trash',
                'status'=>'restore'
            ]);
        }
    }

    public function delete_solo_item(Request $request)
    {

        $table = base64_decode($request['table']);

        if ($request->softDelete=="yes"){

            if ($table=="posts"){

                DB::table('post_post_category')->where('post_id', $request['id'])->delete();
                DB::table('post_post_exam')->where('post_id', $request['id'])->delete();
                DB::table('post_post_module')->where('post_id', $request['id'])->delete();
                DB::table('post_post_notexam')->where('post_id', $request['id'])->delete();
                DB::table('post_post_score')->where('post_id', $request['id'])->delete();

            }
            if ($table=="users"){

                $item = User::withTrashed()->where('id',$request['id'])->first();
                if ($item->avatar!=""){
                    if(file_exists(public_path() . '/' . $item->avatar)){
                        unlink(public_path() . '/' . $item->avatar);
                    }
                }
                CategoryUser::where('user_id',$request['id'])->delete();
                Gallery::where('user_id',$request['id'])->delete();
                Comment::where('user_id',$request['id'])->delete();
                Comment::where('post_id',$request['id'])->delete();
                Ticket::where('user_id',$request['id'])->delete();
                Favorite::where('user_id',$request['id'])->delete();
                Address::where('user_id',$request['id'])->delete();



            }

            if ($table=="comments"){
                DB::table($table)->where('parent', $request['id'])->delete();
                //DB::delete('delete from ' . $table . ' where parent=?', [$request['id']]);

            }

            if ($table=="admins"){

                $item = Admin::withTrashed()->where('id',$request['id'])->first();
                if ($item->avatar!=""){
                    if(file_exists(public_path() . '/' . $item->avatar)){
                        unlink(public_path() . '/' . $item->avatar);
                    }
                }
                $permission=Role::where('admin_id', $request['id'])->first();
                if ($permission!="") {
                    $deleted = DB::table('role_has_permissions')->where('role_id', $permission->id)->delete();
                    $permission->delete();
                }
            }

            if ($table=="tickets"){

                $item = Ticket::where('id',$request['id'])->first();
                if ($item->file!=""){
                    if(file_exists(public_path() . '/' . $item->file)){
                        unlink(public_path() . '/' . $item->file);
                    }
                }
                if ($item->file2!=""){
                    if(file_exists(public_path() . '/' . $item->file2)){
                        unlink(public_path() . '/' . $item->file2);
                    }
                }

                $item2 = Ticket::where('parent',$request['id'])->first();
                if ($item2){
                    if (@$item2->file!=""){
                        if(file_exists(public_path() . '/' . $item2->file)){
                            unlink(public_path() . '/' . $item2->file);
                        }
                    }
                    if (@$item2->file2!=""){
                        if(file_exists(public_path() . '/' . $item2->file2)){
                            unlink(public_path() . '/' . $item2->file2);
                        }
                    }
                    $item2->delete();
                }

                $item->delete();
                echo 'deleted';
            }
            $deleted=DB::table($table)->where('id', $request['id'])->delete();
            //$deleted = DB::delete('delete from ' . $table . ' where id=?', [$request['id']]);
        }else{
            $deleted = DB::table($table)->where('id', $request['id'])->update(['deleted_at' => Carbon::now()->format('Y-m-d H:m:s')]);
        }
        if ($deleted) {
            echo 'deleted';
        }
    }

    public function uploadimageuser_new(Request $request)
    {
        $user = Avatar_user::where('user_id',Admin()->id)->first();
        if (!$user)
        {
            $user=new Avatar_user();
        }

        if (!empty($user->src)) {
            if (file_exists(public_path($user->src))) {
                unlink(public_path($user->src));
            }
        }

        $file = $request->file('file');
        $image = Image::make($file);

        //save image
        $name = time() . rand() . $file->getClientOriginalName();
        $image->resize(150, 150, function ($constraint) {
            $constraint->aspectRatio();
        });

        if (!is_dir('images/')) {
            mkdir("images");
        }
        if (!is_dir('images/users')) {
            mkdir("images/users");
        }
        $image->save('images/users/'. $name);
        $user->src = 'images/users/' . $name;
        $user->name =  $name;
        $user->user_id=Admin()->id;
        $user->save();

        return response()->json([
            'status' => asset($user->src)
        ]);

    }

    public function uploadImageUser(Request $request)
    {
        if ($request->id!=""){
            if ($request->user=="admin"){
                $user = Admin::find($request->id);
            }else{
                $user = User::find($request->id);
            }
        }else{
            $user = Admin::find(Admin()->id);
        }


        if (!empty($user->avatar)) {
            if (file_exists(public_path($user->avatar))) {
                unlink(public_path($user->avatar));
            }
        }

        $file = $request->file('file');
        $image = Image::make($file);

        //save image
        $name = time() . rand() . $file->getClientOriginalName();
        $image->resize(150, 150, function ($constraint) {
            $constraint->aspectRatio();
        });
        if ($request->user=="admin"){
            if (!is_dir('images')) {
                mkdir("images");
            }
            if (!is_dir('images/admins')) {
                mkdir("images/admins");
            }
            if (!is_dir('images/admins/' . $user->id)) {
                mkdir("images/admins/" . $user->id);
            }
            if (!is_dir('images/admins/' . $user->id . '/profile')) {
                mkdir("images/admins/" . $user->id . '/profile');
            }
            $image->save('images/admins/' . $user->id . '/profile/'.$name);
            $user->avatar='images/admins/' . $user->id . '/profile/'.$name;
        }else{
            if (!is_dir('images')) {
                mkdir("images");
            }
            if (!is_dir('images/users')) {
                mkdir("images/users");
            }
            if (!is_dir('images/users/' . $user->id)) {
                mkdir("images/users/" . $user->id);
            }
            if (!is_dir('images/users/' . $user->id . '/profile')) {
                mkdir("images/users/" . $user->id . '/profile');
            }
            $image->save('images/users/' . $user->id . '/profile/'.$name);
            $user->avatar='images/users/' . $user->id . '/profile/'.$name;
        }


        $user->save();

        return response()->json([
            'status' => asset($user->avatar)
        ]);

    }

    public function Change_status_user(Request $request)
    {
        if ($request->admin=="NO"){
            User::where('id',$request->user_id)->update([$request->name=>$request->status]);
        }else{
            Admin::where('id',$request->user_id)->update([$request->name=>$request->status]);
        }

    }

    public function Change_status_Wallet(Request $request)
    {
        $DepositRequest=DepositRequest::find($request->id);
        if ($request->status=="NotPay"){
            $user=User::find($DepositRequest->hairstylist_id);
            $user->wallet= $user->wallet+$DepositRequest->price;
            $user->save();
        }
        $DepositRequest->status=$request->status;
        $DepositRequest->save();

    }

    public function admin_select_address(Request $request)
    {
        Address::where('user_id',$request->user_id)->update(['selected'=>"NO"]);
        Address::where(['id'=>$request->id,'user_id'=>$request->user_id])->update(['selected'=>"YES"]);

    }

    public function Change_status_comments(Request $request)
    {
        Comment::where(['id'=>$request->id])->update(['status'=>$request->status]);

    }

    public function get_comment(Request $request)
    {
        $comment=Comment::find($request->id);
        @$answer=Comment::where('parent',@$request->id)->first();
        return response([
            'item'=>$comment,
            'answer'=>@$answer->content
        ]);
    }

    public function store_answer_comment(Request $request)
    {

        if ($request->comment!=""){
            $answer=Comment::where('parent',@$request->id)->first();
            if ($answer==""){
                $comment=new Comment();
                $comment->comment=$request->comment;
                $comment->parent=$request->id;
                $comment->type="post";
                $comment->user_id=Admin()->id;
                $comment->status="SEEN";
                $comment->save();
            }else{
                $answer->comment=$request->input('comment');
                $answer->save();
            }
        }

    }

    public function restore_table(Request $request)
    {
        $table = base64_decode($request['table']);
        DB::table($table)->where('id', $request['id'])->update(['deleted_at' => null]);

    }

    public function update_slider_banner_brand(Request $request)
    {
        $table = base64_decode($request['table']);
        if ($table=="sliders"){
            $item=\Modules\Slider\Entities\Slider::find($request->id);
        }
        if ($table=="banners"){
            $item=\Modules\Banner\Entities\Banner::find($request->id);
            if ($request->position=="بالا"){
                $item->position="top";
            }elseif ($request->position=="وسط"){
                $item->position="center";
            }elseif ($request->position=="پایین"){
                $item->position="bottom";
            }
        }
        if ($table=="brands"){
            $item=\Modules\Brand\Entities\Brand::find($request->id);
        }

        $item->title=$request->title;
        $item->text=$request->text;
        $item->link=$request->link;
        $item->alt=$request->alt;
        $item->color=$request->color;
        if ($request->status=="نمایش"){
            $item->status="ACTIVE";
        }else{
            $item->status="INACTIVE";
        }

        $item->save();
    }

    public function update_membership_package(Request $request)
    {

        $item=Package::find($request->id);
        $item->title=$request->title;
        $item->day=$request->day;
        $item->price=$request->price;
        if ($request->text!="" and $request->text!="Empty"){
            $item->text=$request->text;
        }else{
            $item->text="";
        }


        $item->save();
    }

    public function get_data_table(Request $request)
    {
        $table = base64_decode($request['table']);
        $data=DB::select('select * from '.$table.'  where id=?', [$request->id]);
        return response([
            'data'=>$data
        ]);
    }

    public function uploadImageGalleryUser(Request $request)
    {
        $gallery = new Gallery();
        $file = $request->file('file');

        $image = Image::make($file);

        //save image
        $name = time() . rand() . $file->getClientOriginalName();
        /*$image->resize(150, 150, function ($constraint) {
            $constraint->aspectRatio();
        });*/
        if (!is_dir('FileUploader')) {
            mkdir("FileUploader");
        }
        if (!is_dir('FileUploader/users')) {
            mkdir("FileUploader/users");
        }
        if (!is_dir('FileUploader/users/' . Auth::id())) {
            mkdir("FileUploader/users/" . Auth::id());
        }
        if (!is_dir('FileUploader/users/' . Auth::id() . '/galleries')) {
            mkdir("FileUploader/users/" . Auth::id() . '/galleries');
        }

        $image->save('FileUploader/users/' . Auth::id() . '/galleries/' . $name);
        $gallery->imagePath = 'FileUploader/users/' . Auth::id() . '/galleries/' . $name;
        $gallery->imageName=$file->getClientOriginalName();
        $gallery->user_id=$request->user_id;
        $gallery->save();

        $galleries=Gallery::where('user_id',$request->user_id)->get();
        $csrf_token=csrf_token();
        return response()->json([
            'id' => $gallery->id,
            'user_id' => $request->user_id,
            'csrf_token'=>"'$csrf_token'",
            'src' => asset($gallery->imagePath),
            'count'=>count($galleries)
        ]);

    }

    public function remove_gallery(Request $request)
    {
        $gallery=Gallery::where(['id'=>$request->id])->first();
        if(file_exists(public_path() . '/' . $gallery->imagePath)){
            unlink(public_path() . '/' . $gallery->imagePath);
        }
        $gallery->delete();
        $galleries=Gallery::where('user_id',$request->user_id)->get();
        $csrf_token=csrf_token();
        return response()->json([
            'count'=>count($galleries),
            'csrf_token'=>"'$csrf_token'",
        ]);
    }

}

