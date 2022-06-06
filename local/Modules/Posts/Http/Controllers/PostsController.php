<?php

namespace Modules\Posts\Http\Controllers;

use App\Http\Requests\Posts\PostCategoryStoreRequest;
use App\Http\Requests\Posts\PostCategoryUpdateRequest;
use App\Http\Requests\Posts\PostStoreRequest;
use App\Http\Requests\Posts\PostUpdateRequest;
use App\Models\Comment;
use App\Models\PostExam;
use App\Models\PostModule;
use App\Models\PostNotexam;
use App\Models\PostScore;
use App\Models\PostTarget;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Posts\Entities\Post;
use Modules\Posts\Entities\PostCategory;

class PostsController extends Controller
{
    public function index()
    {
        if (admin()->can('posts_index')) {


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
                    $items=Post::with('postcategories')->where('status',$status)->orderBy('id', 'desc')->paginate($pageNum);
                    $count_items=Post::with('postcategories')->where('status',$status)->orderBy('id', 'desc')->get();
                }else{
                    $items=Post::with('postcategories')->where('title', 'like', '%'.$search.'%')->orderBy('id', 'desc')->paginate($pageNum);
                    $count_items=Post::with('postcategories')->where('title', 'like', '%'.$search.'%')->orderBy('id', 'desc')->get();

                }
            }else{
                $items = Post::with('postcategories')->orderBy('id', 'desc')->paginate($pageNum);
                $count_items = Post::with('postcategories')->orderBy('id', 'desc')->get();
            }

            $title = "مطالب";
            $Active_list = "posts";
            $Active = "posts";
            $table = base64_encode('posts');
            $pageNum = count($count_items);
            return view('posts::posts.post_index', compact(['items', 'Active','Active_list', 'title', 'table','pageNum']));
        } else {
            abort(403);
        }
    }

    public function create()
    {
        if (admin()->can('posts_index')) {
            $items=PostCategory::all();
            $modules=PostModule::all();
            $scores=PostScore::all();
            $exams=PostExam::all();
            $notexams=PostNotexam::all();
            $title = "افزودن مطلب جدید";
            $Active_list = "posts";
            $Active = "posts";
            $back_link = "posts";
            $table = base64_encode('posts');
            return view('posts::posts.post_create', compact(['items', 'Active','Active_list', 'title', 'table','back_link','modules','scores','exams','notexams']));
        } else {
            abort(403);
        }
    }

    public function store(PostStoreRequest $request)
    {
        $post = new Post();
        if ($request->slug == "") {
            $temp = str_replace(" ", "-", $request->title);
            $post->slug = $temp;
        } else {
            $post->slug = $request->input('slug');
        }
        $post->title = $request->input('title');
        $post->shortContent = $request->input('shortContent');

        if ($request->sounds){
            $sounds=implode('||',$request->sounds);
            $post->sounds = $sounds;
        }
        if ($request->links){
            $links=implode('||',$request->links);
            $post->links =$links;
        }

        //$post->Content = $request->input('content');
       // $post->seoTitle = $request->input('seoTitle');
        //$post->seoContent = $request->input('seoContent');
        $post->link = $request->input('link');
        $post->level = $request->input('level');
        $post->target = $request->input('target');
        $post->status = $request->input('status');
        $post->image = $request->input('feature_image');
        $post->admin_id = Auth::guard('admin')->id();
        $post->save();

        $post->postcategories()->attach($request->category);
        $post->postexams()->attach($request->exam);
        $post->postmodules()->attach($request->module);
        $post->postnotexams()->attach($request->notexam);
        $post->postscores()->attach($request->score);
        if ($request->input('target')==1){
            DB::table('post_post_notexam')->where('post_id', $post->id)->delete();
        }elseif ($request->input('target')==2){
            DB::table('post_post_exam')->where('post_id', $post->id)->delete();
            DB::table('post_post_module')->where('post_id', $post->id)->delete();
            DB::table('post_post_score')->where('post_id', $post->id)->delete();
        }
        Session()->put('store-success','کتاب های پیشنهادی شما با موفقیت ایجاد شد');
        return redirect('/admin/posts');
    }

    public function edit($id)
    {
        if (admin()->can('posts_index')) {
            $items = PostCategory::all();
            $item = Post::withTrashed()->with('postcategories')->where('id', $id)->first();
            $modules=PostModule::all();
            $scores=PostScore::all();
            $exams=PostExam::all();
            $notexams=PostNotexam::all();
            $title = "ویرایش مطلب ";
            $Active_list = "posts";
            $Active = "posts";
            $back_link = "posts";
            $table = base64_encode('posts');
            return view('posts::posts.post_edit', compact(['item', 'items', 'Active', 'Active_list', 'title', 'table', 'back_link','modules','scores','exams','notexams']));
        } else {
            abort(403);
        }
    }
    public function update(PostUpdateRequest $request, $id)
    {
        $post = Post::withTrashed()->where('id', $id)->first();
        if ($request->slug == "") {
            $temp = str_replace(" ", "-", $request->title);
            $post->slug = $temp;
        } else {
            $post->slug = $request->input('slug');
        }
        $post->title = $request->input('title');
        $post->level = $request->input('level');
        $post->shortContent = $request->input('shortContent');
       // $post->Content = $request->input('content');
        //$post->seoTitle = $request->input('seoTitle');
        //$post->seoContent = $request->input('seoContent');
        if ($request->sounds){
            $sounds=implode('||',$request->sounds);
            $post->sounds = $sounds;
        }
        if ($request->links){
            $links=implode('||',$request->links);
            $post->links =$links;
        }
        $post->link = $request->input('link');
        $post->status = $request->input('status');
        $post->target = $request->input('target');
        $post->image = $request->input('feature_image');

        $post->save();
        $post->postcategories()->sync($request->category);
        $post->postexams()->sync($request->exam);
        $post->postmodules()->sync($request->module);
        $post->postnotexams()->sync($request->notexam);
        $post->postscores()->sync($request->score);
        if ($request->input('target')==1){
            DB::table('post_post_notexam')->where('post_id', $id)->delete();
        }elseif ($request->input('target')==2){
            DB::table('post_post_exam')->where('post_id', $id)->delete();
            DB::table('post_post_module')->where('post_id', $id)->delete();
            DB::table('post_post_score')->where('post_id', $id)->delete();
        }
        Session()->put('store-success','کتاب های پیشنهادی شما با موفقیت ویرایش شد');
        return redirect('/admin/posts');
    }


    //================================== Category=========================
    public function posts_categories_index()
    {
        if (admin()->can('posts_index')) {


            @$search=$_GET['search'];
            @$cView=$_GET['cView'];
            if ($cView){
                $pageNum=$cView;
            }else{
                $pageNum=10;
            }
            if ($search){

                $items=PostCategory::where('title', 'like', '%'.$search.'%')->orderBy('id', 'desc')->paginate($pageNum);
                $count_items=PostCategory::where('title', 'like', '%'.$search.'%')->orderBy('id', 'desc')->get();

            }else{
                $items = PostCategory::orderBy('id', 'desc')->paginate($pageNum);
                $count_items = PostCategory::orderBy('id', 'desc')->get();
            }
            $title = "دسته بندی مطالب";
            $Active_list = "posts";
            $Active = "postsCategories";
            $table = base64_encode('post_categories');
            $pageNum = count($count_items);
            return view('posts::posts.category_index', compact(['items', 'Active','Active_list', 'title', 'table','pageNum']));
        } else {
            abort(403);
        }
    }

    public function posts_categories_create()
    {
        if (admin()->can('posts_category')) {
            $items=PostCategory::all();
            $title = "افزودن دسته بندی مطالب";
            $Active_list = "posts";
            $Active = "postsCategories";
            $back_link = "posts/categories";
            $table = base64_encode('post_categories');
            return view('posts::posts.category_create', compact(['items', 'Active','Active_list', 'title', 'table','back_link']));
        } else {
            abort(403);
        }

    }

    public function posts_categories_store(PostCategoryStoreRequest $request)
    {
        $item=new PostCategory();
        $item->title=$request->title;
        $item->slug=$request->slug;
        $item->parent=$request->parent;
        $item->save();
        session()->put('store-success', 'دسته بندی جدید با موفقیت اضافه شد');
        return redirect('/admin/posts/categories');
    }

    public function posts_categories_edit($id)
    {
        if (admin()->can('posts_category')) {
            $item=PostCategory::withTrashed()->find($id);
            $items=PostCategory::withTrashed()->get();
            $title = "ویرایش دسته بندی مطالب";
            $Active_list = "posts";
            $Active = "postsCategories";
            $back_link = "posts/categories";
            $table = base64_encode('post_categories');
            return view('posts::posts.category_edit', compact(['item','items', 'Active','Active_list', 'title', 'table','back_link']));
        } else {
            abort(403);
        }

    }

    public function posts_categories_Update(PostCategoryUpdateRequest $request ,$id)
    {
        $item=PostCategory::find($id);
        $item->title=$request->title;
        $item->slug=$request->slug;
        $item->parent=$request->parent;
        $item->save();
        session()->put('store-success', 'دسته بندی  با موفقیت ویرایش شد');
        return redirect('/admin/posts/categories');
    }


    //=================================== post comments ==================================

    public function posts_comments_index()
    {
        if (admin()->can('posts_comments')) {


            @$search=$_GET['search'];
            @$cView=$_GET['cView'];
            if ($cView){
                $pageNum=$cView;
            }else{
                $pageNum=10;
            }
            if ($search){

                $items=Comment::with('post')->where('parent','0')->where('name', 'like', '%'.$search.'%')->where('type','post')->orwhere('email', 'like', '%'.$search.'%')->where('type','post')->orderBy('id', 'desc')->paginate($pageNum);
                $count_items=Comment::with('post')->where('parent','0')->where('name', 'like', '%'.$search.'%')->where('type','post')->orwhere('email', 'like', '%'.$search.'%')->where('type','post')->orderBy('id', 'desc')->get();

            }else{
                $items = Comment::with('post')->where('parent','0')->orderBy('id', 'desc')->where('type','post')->paginate($pageNum);
                $count_items = Comment::with('post')->where('parent','0')->orderBy('id', 'desc')->where('type','post')->get();
            }
            $title = "لیست نظرات مطالب";
            $Active_list = "posts";
            $Active = "postsComments";
            $table = base64_encode('comments');
            $pageNum = count($count_items);
            return view('posts::posts.comment_index', compact(['items', 'Active','Active_list', 'title', 'table','pageNum']));
        } else {
            abort(403);
        }
    }
}
