<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\BlockList;
use App\Models\Category;
use App\Models\CategoryUser;
use App\Models\Comment;
use App\Models\FeaturesHairstylist;
use App\Models\Gallery;
use App\Models\More;
use App\Models\Pay;
use App\Models\Payment;
use App\Models\PostExam;
use App\Models\PostModule;
use App\Models\PostScore;
use App\Models\Report;
use App\Models\Reserve;
use App\Models\SpecialtiesHairstylist;
use App\Models\Timing;
use App\Models\TimingsDay;
use App\Models\User;
use App\Models\DesksService;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Contact\Entities\Contact;
use Modules\Posts\Entities\Post;
use Modules\Posts\Entities\PostCategory;
use Modules\Slider\Entities\Slider;

class FrontsController extends Controller
{
    public function index()
    {
      /*  $posts = Post::where('status', 'PUBLISHED')->with('postcategories')->with('admin')->with('user')->orderby('id', 'desc')->take(4)->get();
        $sliders = Slider::where('status', 'ACTIVE')->get();
        if (setting()['Payment_membership']=="ACTIVE") {
            $rates = User::where(['HairStylist' => 'YES', 'verifire' => 'YES', 'status' => 'ACTIVE','membership_status'=>'OK'])->orderby('rate', 'desc')->take(20)->get();
        }else{
            $rates = User::where(['HairStylist' => 'YES', 'verifire' => 'YES', 'status' => 'ACTIVE'])->orderby('rate', 'desc')->take(20)->get();
        }
        $features_hairstylist = FeaturesHairstylist::where(['status' => 'ACTIVE'])->orderby('id', 'desc')->take(20)->get();
        $specialties_hairstylist = SpecialtiesHairstylist::where(['status' => 'ACTIVE'])->orderby('id', 'desc')->take(20)->get();*/

        $categories=PostCategory::all();
        $exams=PostExam::all();
        $scores=PostScore::all();
        $modules=PostModule::all();
        $title = setting()['title'];
        $seo_title = setting()['seo_title'];
        $seo_content = setting()['seo_content'];
        $Active = "index";
       // return view('front.index.index', compact('title', 'seo_title', 'seo_content', 'Active', 'posts', 'sliders', 'rates', 'features_hairstylist', 'specialties_hairstylist'));
        return view('front.index.index', compact('title', 'seo_title', 'seo_content', 'Active','categories','exams','scores','modules'));
    }

    public function get_data(Request $request)
    {

        $categories=DB::select("SELECT * FROM post_post_category where post_category_id=?",[$request->category]);
        $category=[];
        foreach ($categories as $item){
            $category[]=$item->post_id;
        }



        $targets=DB::table('posts')
            ->leftJoin('post_post_score','posts.id','=','post_post_score.post_id')
            ->leftJoin('post_post_exam','posts.id','=','post_post_exam.post_id')
            ->leftJoin('post_post_module','posts.id','=','post_post_module.post_id')
            ->where('post_post_score.post_score_id','>=', $request->category)
            ->where('post_post_score.post_score_id','<=', $request->score)
            ->where('post_post_exam.post_exam_id', $request->exam)
            ->where('post_post_module.post_module_id', $request->module)
            ->get();

        $id_targets=[];
        foreach ($targets as $target){
            $id_targets[]=$target->id;
        }


        $ids=[];
        if (count($category)){
            if (count($id_targets)){
                $ids=array_merge($category,$id_targets);
                $ids=array_unique($ids);
            }else{
                $ids=array_unique($category);
            }
        }



        $posts=Post::whereIn('id',$ids)->where('status','PUBLISHED')->get();
        sleep(2);
        return response([
            "posts"=>$posts,
        ]);

    }

    public function blogs($category = null)
    {
        @$search = $_GET['search'];

        if (@$category) {
            if (@$search) {
                $posts = Post::where('status', 'PUBLISHED')->where('title', 'like', "%" . $search . "%")->whereHas('postcategories', function ($q) use ($category) {
                    $q->where('post_categories.slug', $category);
                })->paginate(10);
            } else {
                $posts = Post::where('status', 'PUBLISHED')->whereHas('postcategories', function ($q) use ($category) {
                    $q->where('post_categories.slug', $category);
                })->paginate(10);
            }
        } else {
            if (@$search) {
                $posts = Post::where('status', 'PUBLISHED')->where('title', 'like', "%" . $search . "%")->with('postcategories')->orderby('id', 'desc')->paginate(20);
            } else {
                $posts = Post::where('status', 'PUBLISHED')->with('postcategories')->orderby('id', 'desc')->paginate(10);
            }
        }

        $posts_rand = Post::where('status', 'PUBLISHED')->with('postcategories')->orderByRaw("RAND()")->take(2)->get();
        $last_posts = Post::where('status', 'PUBLISHED')->with('postcategories')->orderByRaw('id', 'desc')->take(9)->get();
        $posts_view = Post::where('status', 'PUBLISHED')->with('postcategories')->orderByRaw('view', 'desc')->take(2)->get();
        $categories = PostCategory::all();
        $title = "اخبار و مقالات | " . setting()['title'];
        $seo_title = "اخبار و مقالات," . setting()['seo_title'];
        $seo_content = "اخبار و مقالات," . setting()['seo_content'];
        return view('front.blog.index', compact('title', 'seo_title', 'seo_content', 'posts', 'categories', 'posts_rand', 'last_posts', 'posts_view'));
    }

    public function blog($slug)
    {
        $posts_rand = Post::where('status', 'PUBLISHED')->with('postcategories')->orderByRaw("RAND()")->take(3)->get();
        $categories = Postcategory::all();

        $post = Post::where(['status' => 'PUBLISHED', 'slug' => $slug])->with('postcategories')->first();
        if ($post != "") {
            $comments = Comment::with('user')->where(['post_id' => $post->id, 'status' => 'SEEN', 'parent' => '0', 'type' => 'post'])->paginate(10);
            $last_posts = Post::where('status', 'PUBLISHED')->with('postcategories')->orderByRaw('id', 'desc')->take(5)->get();
            $posts_view = Post::where('status', 'PUBLISHED')->with('postcategories')->orderByRaw('view', 'desc')->take(2)->get();
            $like_posts = collect([]);
            foreach ($post->postcategories as $val) {
                $category_posts = $val->post;
                foreach ($category_posts as $post2) {
                    if ($post->id != $post2->id) {
                        if (!$like_posts->contains('id', $post2->id)) {
                            $like_posts->push($post2);
                        }
                    }

                }
            }
            $title = $post->title . ' | ' . setting()['title'];
            $seo_title = $post->seoTitle;
            $seo_content = $post->seoContent;
            return view('front.blog.show', compact('title', 'seo_title', 'seo_content', 'post', 'categories', 'posts_rand', 'last_posts', 'posts_view', 'comments', 'like_posts'));
        } else {
            return abort(404);
        }
    }

    public function blog_comment_store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'comment' => 'required',
        ], [
            'name.required' => 'نام الزامی است',
            'email.required' => 'ایمیل الزامی است',
            'email.email' => 'یک ایمیل صحیح وارد کنید',
            'comment.required' => 'نظر الزامی است',
        ]);
        $item = new Comment();
        $item->name = $request->name;
        $item->user_id = Auth::id();
        $item->email = $request->email;
        $item->comment = $request->comment;
        $item->post_id = $request->post_id;
        $item->type = "post";
        $item->save();

        $post = Post::find($request->post_id);
        session()->put('create-success', 'نظر شما با موفقیت ذخیره شد، و بعد از تائید نمایش داده می شود.');
        return redirect('blog/' . $post->slug . '#comments');
    }

    public function search(Request $request)
    {
        @$search = $_GET['search'];
        $limit = 10;
        $pageNum2 = 0;
        if ($request->sort) {
            $sort2 = 'desc';
            if ($request->sort == "new") {
                $sort1 = 'id';
            }
            if ($request->sort == "rate") {
                $sort1 = 'rate';
            }

            $products = [];
            $id_user=[];
            $users = User::where(['status' => 'ACTIVE', 'HairStylist' => 'YES', 'verifire' => 'YES'])->get();
            /*foreach ($users as $user){
                $id_user[]=$user->id;
            }*/
            if ($request->type) {
                $id_user=[];
                if (@$request->type == "M,F" or @$request->type == "F,M") {
                    $users = User::where(['status' => 'ACTIVE', 'HairStylist' => 'YES', 'verifire' => 'YES'])->get();
                    foreach ($users as $user){
                        $id_user[]=$user->id;
                    }
                } else {
                    $users = User::where(['status' => 'ACTIVE', 'HairStylist' => 'YES', 'verifire' => 'YES', 'Type_hairdresser' => $request->type])->get();
                    foreach ($users as $user){
                        $id_user[]=$user->id;
                    }
                }
            }
            if ($request->city) {
                $city = explode('|', $request->city);
                $id_user=[];
                $users = User::where(['status' => 'ACTIVE', 'HairStylist' => 'YES', 'verifire' => 'YES', 'city_id' => $city[1]])->get();
                foreach ($users as $user){
                    $id_user[]=$user->id;
                }
            }
            if ($request->search) {
                $id_user=[];
                $users = User::where(['status' => 'ACTIVE', 'HairStylist' => 'YES', 'verifire' => 'YES'])->where('name', 'like', "%" . $search . "%")->orwhere('username', 'like', "%" . $search . "%")->orwhere('nameShop', 'like', "%" . $search . "%")->get();
                foreach ($users as $user){
                    $id_user[]=$user->id;
                }
            }
            if ($request->attribute) {
                $attribute_url = explode(',', $request->attribute);
                foreach ($attribute_url as $url) {
                    $CategoryUsers = CategoryUser::where('category_id', $url)->get();
                    if (count($CategoryUsers)) {
                        $id_user=[];
                        foreach ($CategoryUsers as $CategoryUser) {
                            $id_user[]=$CategoryUser->user_id;
                        }
                    }
                }

            }
            if ($request->type and $request->city) {
                $users= User::where(['status' => 'ACTIVE', 'HairStylist' => 'YES', 'verifire' => 'YES', 'Type_hairdresser' => $request->type, 'city_id' => $city[1]])->get();
                $id_user=[];
                foreach ($users as $user){
                    $id_user[]=$user->id;
                }
            }
            if ($request->type and $request->attribute) {

                $attribute_url = explode(',', $request->attribute);
                foreach ($attribute_url as $url) {
                    $id_user=[];
                    $CategoryUsers = CategoryUser::where('category_id', $url)->get();
                    if (count($CategoryUsers)) {
                        foreach ($CategoryUsers as $CategoryUser) {
                            $users = User::where(['status' => 'ACTIVE', 'HairStylist' => 'YES', 'verifire' => 'YES', 'Type_hairdresser' => $request->type, 'id' => $CategoryUser->user_id])->get();
                            foreach ($users as $user){
                                $id_user[]=$user->id;
                            }
                        }
                    }
                }

            }
            if ($request->city and $request->attribute) {
                $attribute_url = explode(',', $request->attribute);
                foreach ($attribute_url as $url) {
                    $id_user=[];
                    $CategoryUsers = CategoryUser::where('category_id', $url)->get();
                    if (count($CategoryUsers)) {
                        foreach ($CategoryUsers as $CategoryUser) {
                            $users = User::where(['status' => 'ACTIVE', 'HairStylist' => 'YES', 'verifire' => 'YES', 'city_id' => $city[1], 'id' => $CategoryUser->user_id])->get();
                            foreach ($users as $user){
                                $id_user[]=$user->id;
                            }
                        }
                    }
                }
            }
            if ($request->type and $request->city and $request->attribute) {
                $attribute_url = explode(',', $request->attribute);
                foreach ($attribute_url as $url) {
                    $id_user=[];
                    $CategoryUsers = CategoryUser::where('category_id', $url)->get();
                    if (count($CategoryUsers)) {
                        foreach ($CategoryUsers as $CategoryUser) {
                            $users = User::where(['status' => 'ACTIVE', 'HairStylist' => 'YES', 'verifire' => 'YES', 'Type_hairdresser' => $request->type, 'city_id' => $city[1], 'id' => $CategoryUser->user_id])->get();
                            foreach ($users as $user){
                                $id_user[]=$user->id;
                            }
                        }
                    }
                }
            }
            if ($request->type and $request->search) {
                $id_user=[];
                $users = User::where(['status' => 'ACTIVE', 'HairStylist' => 'YES', 'verifire' => 'YES', 'Type_hairdresser' => $request->type])->where('name', 'like', "%" . $search . "%")->orwhere('username', 'like', "%" . $search . "%")->orwhere('nameShop', 'like', "%" . $search . "%")->get();
                foreach ($users as $user){
                    $id_user[]=$user->id;
                }
            }
            if ($request->type and $request->search and $request->attribute) {
                $attribute_url = explode(',', $request->attribute);
                foreach ($attribute_url as $url) {
                    $id_user=[];
                    $CategoryUsers = CategoryUser::where('category_id', $url)->get();
                    if (count($CategoryUsers)) {
                        foreach ($CategoryUsers as $CategoryUser) {
                            $users = User::where(['status' => 'ACTIVE', 'HairStylist' => 'YES', 'verifire' => 'YES', 'Type_hairdresser' => $request->type, 'id' => $CategoryUser->user_id])->where('name', 'like', "%" . $search . "%")->orwhere('username', 'like', "%" . $search . "%")->orwhere('nameShop', 'like', "%" . $search . "%")->get();
                            foreach ($users as $user){
                                $id_user[]=$user->id;
                            }
                        }
                    }
                }

            }
            if ($request->city and $request->search) {
                $id_user=[];
                $users = User::where(['status' => 'ACTIVE', 'HairStylist' => 'YES', 'verifire' => 'YES', 'city_id' => $city[1]])->where('name', 'like', "%" . $search . "%")->orwhere('username', 'like', "%" . $search . "%")->orwhere('nameShop', 'like', "%" . $search . "%")->get();
                foreach ($users as $user){
                    $id_user[]=$user->id;
                }
            }
            if ($request->city and $request->search and $request->attribute) {
                $attribute_url = explode(',', $request->attribute);
                foreach ($attribute_url as $url) {
                    $id_user=[];
                    $CategoryUsers = CategoryUser::where('category_id', $url)->get();
                    if (count($CategoryUsers)) {
                        foreach ($CategoryUsers as $CategoryUser) {
                            $users = User::where(['status' => 'ACTIVE', 'HairStylist' => 'YES', 'verifire' => 'YES', 'city_id' => $city[1], 'id' => $CategoryUser->user_id])->where('name', 'like', "%" . $search . "%")->orwhere('username', 'like', "%" . $search . "%")->orwhere('nameShop', 'like', "%" . $search . "%")->get();
                            foreach ($users as $user){
                                $id_user[]=$user->id;
                            }
                        }
                    }
                }
            }
            if ($request->type and $request->city and $request->search) {
                $id_user=[];
                $Total_items[] = User::where(['status' => 'ACTIVE', 'HairStylist' => 'YES', 'verifire' => 'YES', 'Type_hairdresser' => $request->type, 'city_id' => $city[1]])->where('name', 'like', "%" . $search . "%")->orwhere('username', 'like', "%" . $search . "%")->orwhere('nameShop', 'like', "%" . $search . "%")->get();
                foreach ($users as $user){
                    $id_user[]=$user->id;
                }
            }
            if ($request->type and $request->city and $request->search and $request->attribute) {
                $attribute_url = explode(',', $request->attribute);
                foreach ($attribute_url as $url) {
                    $id_user=[];
                    $CategoryUsers = CategoryUser::where('category_id', $url)->get();
                    if (count($CategoryUsers)) {
                        foreach ($CategoryUsers as $CategoryUser) {
                            $users = User::where(['status' => 'ACTIVE', 'HairStylist' => 'YES', 'verifire' => 'YES', 'Type_hairdresser' => $request->type, 'city_id' => $city[1], 'id' => $CategoryUser->user_id])->where('name', 'like', "%" . $search . "%")->orwhere('username', 'like', "%" . $search . "%")->orwhere('nameShop', 'like', "%" . $search . "%")->get();
                            foreach ($users as $user){
                                $id_user[]=$user->id;
                            }
                        }
                    }
                }
            }


            $products = array_unique($id_user);
            $pageNum = count($products);
            $page = $limit;
            if ($pageNum >= $limit) {
                $pageNum = round($pageNum / $limit);
                $pageNum2 = $pageNum;
            } else {
                $pageNum = $limit;
                $pageNum2 = 0;
            }
            if (setting()['Payment_membership']=="ACTIVE") {
                $items = User::where([['status', 'ACTIVE'],['membership_status', 'OK'], ['verifire', 'YES'], ['HairStylist', 'YES']])->whereIn('id', $products)->orwhere([['status', 'ACTIVE'],['membership_status', 'OK'], ['verifire', 'YES'], ['HairStylist', 'YES'], ['username', 'like', "%" . $search . "%"]])->whereIn('id', $products)->orderby($sort1, $sort2)->paginate($page);
            }else{
                $items = User::where([['status', 'ACTIVE'], ['verifire', 'YES'], ['HairStylist', 'YES']])->whereIn('id', $products)->orwhere([['status', 'ACTIVE'], ['verifire', 'YES'], ['HairStylist', 'YES'], ['username', 'like', "%" . $search . "%"]])->whereIn('id', $products)->orderby($sort1, $sort2)->paginate($page);
            }
        } else {
            if (setting()['Payment_membership']=="ACTIVE") {
                $items = User::where(['status' => 'ACTIVE', 'HairStylist' => 'YES', 'verifire' => 'YES','membership_status'=>'OK'])->paginate($limit);
            }else {
                $items = User::where(['status' => 'ACTIVE', 'HairStylist' => 'YES', 'verifire' => 'YES'])->paginate($limit);
            }
        }

        $services = Category::where('status', 'ACTIVE')->get();
        $title = "لیست آرایشگاها | " . setting()['title'];
        $seo_title = "لیست آرایشگاها," . setting()['seo_title'];
        $seo_content = "لیست آرایشگاها" . setting()['seo_content'];
        return view('front.search.index', compact('title', 'seo_title', 'seo_content', 'items', 'pageNum2', 'services'));

    }

    public function doSearch(Request $request)
    {
        @$search = $request->search;
        $limit = 10;
        if ($request->sort) {
            $sort2 = 'desc';
            if ($request->sort == "new") {
                $sort1 = 'id';
            }
            if ($request->sort == "rate") {
                $sort1 = 'rate';
            }
            $products = [];

            $id_user=[];
            $users = User::where(['status' => 'ACTIVE', 'HairStylist' => 'YES', 'verifire' => 'YES'])->get();
            foreach ($users as $user){
                $id_user[]=$user->id;
            }
            if ($request->type) {
                $id_user=[];
                if (@$request->type == "M,F" or @$request->type == "F,M") {
                    $users = User::where(['status' => 'ACTIVE', 'HairStylist' => 'YES', 'verifire' => 'YES'])->get();
                    foreach ($users as $user){
                        $id_user[]=$user->id;
                    }
                } else {
                    $users = User::where(['status' => 'ACTIVE', 'HairStylist' => 'YES', 'verifire' => 'YES', 'Type_hairdresser' => $request->type])->get();
                    foreach ($users as $user){
                        $id_user[]=$user->id;
                    }
                }
            }
            if ($request->city) {
                $city = explode('|', $request->city);
                $id_user=[];
                $users = User::where(['status' => 'ACTIVE', 'HairStylist' => 'YES', 'verifire' => 'YES', 'city_id' => $city[1]])->get();
                foreach ($users as $user){
                    $id_user[]=$user->id;
                }
            }
            if ($request->search) {
                $id_user=[];
                $users = User::where(['status' => 'ACTIVE', 'HairStylist' => 'YES', 'verifire' => 'YES'])->where('name', 'like', "%" . $search . "%")->orwhere('username', 'like', "%" . $search . "%")->orwhere('nameShop', 'like', "%" . $search . "%")->get();
                foreach ($users as $user){
                    $id_user[]=$user->id;
                }
            }
            if ($request->attribute) {

                foreach ($request->attribute as $url) {
                    $id_user=[];
                    $CategoryUsers = CategoryUser::where('category_id', $url)->get();
                    if (count($CategoryUsers)) {
                        foreach ($CategoryUsers as $CategoryUser) {
                            $users = User::where(['status' => 'ACTIVE', 'HairStylist' => 'YES', 'verifire' => 'YES', 'id' => $CategoryUser->user_id])->get();
                            foreach ($users as $user){
                                $id_user[]=$user->id;
                            }
                        }
                    }
                }
            }
            if ($request->type and $request->city) {
                $users= User::where(['status' => 'ACTIVE', 'HairStylist' => 'YES', 'verifire' => 'YES', 'Type_hairdresser' => $request->type, 'city_id' => $city[1]])->get();
                $id_user=[];
                foreach ($users as $user){
                    $id_user[]=$user->id;
                }
            }
            if ($request->type and $request->attribute) {
                foreach ($request->attribute as $url) {
                    $id_user=[];
                    $CategoryUsers = CategoryUser::where('category_id', $url)->get();
                    if (count($CategoryUsers)) {
                        foreach ($CategoryUsers as $CategoryUser) {
                            $users = User::where(['status' => 'ACTIVE', 'HairStylist' => 'YES', 'verifire' => 'YES', 'Type_hairdresser' => $request->type, 'id' => $CategoryUser->user_id])->get();
                            foreach ($users as $user){
                                $id_user[]=$user->id;
                            }
                        }
                    }
                }
            }
            if ($request->city and $request->attribute) {
                foreach ($request->attribute as $url) {
                    $id_user=[];
                    $CategoryUsers = CategoryUser::where('category_id', $url)->get();
                    if (count($CategoryUsers)) {
                        foreach ($CategoryUsers as $CategoryUser) {
                            $users = User::where(['status' => 'ACTIVE', 'HairStylist' => 'YES', 'verifire' => 'YES', 'city_id' => $city[1], 'id' => $CategoryUser->user_id])->get();
                            foreach ($users as $user){
                                $id_user[]=$user->id;
                            }
                        }
                    }
                }
            }
            if ($request->type and $request->city and $request->attribute) {
                foreach ($request->attribute as $url) {
                    $id_user=[];
                    $CategoryUsers = CategoryUser::where('category_id', $url)->get();
                    if (count($CategoryUsers)) {
                        foreach ($CategoryUsers as $CategoryUser) {
                            $users = User::where(['status' => 'ACTIVE', 'HairStylist' => 'YES', 'verifire' => 'YES', 'Type_hairdresser' => $request->type, 'city_id' => $city[1], 'id' => $CategoryUser->user_id])->get();
                            foreach ($users as $user){
                                $id_user[]=$user->id;
                            }
                        }
                    }
                }
            }
            if ($request->type and $request->search) {
                $id_user=[];
                $users = User::where(['status' => 'ACTIVE', 'HairStylist' => 'YES', 'verifire' => 'YES', 'Type_hairdresser' => $request->type])->where('name', 'like', "%" . $search . "%")->orwhere('username', 'like', "%" . $search . "%")->orwhere('nameShop', 'like', "%" . $search . "%")->get();
                foreach ($users as $user){
                    $id_user[]=$user->id;
                }
            }
            if ($request->type and $request->search and $request->attribute) {
                foreach ($request->attribute as $url) {
                    $CategoryUsers = CategoryUser::where('category_id', $url)->get();
                    $id_user=[];
                    if (count($CategoryUsers)) {
                        foreach ($CategoryUsers as $CategoryUser) {
                            $users = User::where(['status' => 'ACTIVE', 'HairStylist' => 'YES', 'verifire' => 'YES', 'Type_hairdresser' => $request->type, 'id' => $CategoryUser->user_id])->where('name', 'like', "%" . $search . "%")->orwhere('username', 'like', "%" . $search . "%")->orwhere('nameShop', 'like', "%" . $search . "%")->get();
                            foreach ($users as $user){
                                $id_user[]=$user->id;
                            }
                        }
                    }
                }

            }
            if ($request->city and $request->search) {
                $id_user=[];
                $users = User::where(['status' => 'ACTIVE', 'HairStylist' => 'YES', 'verifire' => 'YES', 'city_id' => $city[1]])->where('name', 'like', "%" . $search . "%")->orwhere('username', 'like', "%" . $search . "%")->orwhere('nameShop', 'like', "%" . $search . "%")->get();
                foreach ($users as $user){
                    $id_user[]=$user->id;
                }
            }
            if ($request->city and $request->search and $request->attribute) {
                foreach ($request->attribute as $url) {
                    $CategoryUsers = CategoryUser::where('category_id', $url)->get();
                    $id_user=[];
                    if (count($CategoryUsers)) {
                        foreach ($CategoryUsers as $CategoryUser) {
                            $users = User::where(['status' => 'ACTIVE', 'HairStylist' => 'YES', 'verifire' => 'YES', 'city_id' => $city[1], 'id' => $CategoryUser->user_id])->where('name', 'like', "%" . $search . "%")->orwhere('username', 'like', "%" . $search . "%")->get();
                            foreach ($users as $user){
                                $id_user[]=$user->id;
                            }
                        }
                    }
                }
            }
            if ($request->type and $request->city and $request->search) {
                $id_user=[];
                $users = User::where(['status' => 'ACTIVE', 'HairStylist' => 'YES', 'verifire' => 'YES', 'Type_hairdresser' => $request->type, 'city_id' => $city[1]])->where('name', 'like', "%" . $search . "%")->orwhere('username', 'like', "%" . $search . "%")->orwhere('nameShop', 'like', "%" . $search . "%")->get();
                foreach ($users as $user){
                    $id_user[]=$user->id;
                }
            }
            if ($request->type and $request->city and $request->search and $request->attribute) {
                foreach ($request->attribute as $url) {
                    $CategoryUsers = CategoryUser::where('category_id', $url)->get();
                    $id_user=[];
                    if (count($CategoryUsers)) {
                        foreach ($CategoryUsers as $CategoryUser) {
                            $users = User::where(['status' => 'ACTIVE', 'HairStylist' => 'YES', 'verifire' => 'YES', 'Type_hairdresser' => $request->type, 'city_id' => $city[1], 'id' => $CategoryUser->user_id])->where('name', 'like', "%" . $search . "%")->orwhere('username', 'like', "%" . $search . "%")->get();
                            foreach ($users as $user){
                                $id_user[]=$user->id;
                            }
                        }
                    }
                }
            }


            $products = array_unique($id_user);

            $pageNum = count($products);
            $page = $limit;
            if ($pageNum >= $limit) {
                $pageNum = round($pageNum / $limit);
                $pageNum2 = $pageNum;
            } else {
                $pageNum = $limit;
                $pageNum2 = 0;
            }
            if (setting()['Payment_membership']=="ACTIVE") {
                $items = User::where([['status', 'ACTIVE'],['membership_status', 'OK'], ['verifire', 'YES'], ['HairStylist', 'YES']])->whereIn('id', $products)->orwhere([['status', 'ACTIVE'],['membership_status', 'OK'], ['verifire', 'YES'], ['HairStylist', 'YES'], ['username', 'like', "%" . $search . "%"]])->whereIn('id', $products)->orderby($sort1, $sort2)->paginate($page);
            }else{
                $items = User::where([['status', 'ACTIVE'], ['verifire', 'YES'], ['HairStylist', 'YES']])->whereIn('id', $products)->orwhere([['status', 'ACTIVE'], ['verifire', 'YES'], ['HairStylist', 'YES'], ['username', 'like', "%" . $search . "%"]])->whereIn('id', $products)->orderby($sort1, $sort2)->paginate($page);
            }
        } else {
            if (setting()['Payment_membership']=="ACTIVE") {
                $items = User::where(['status' => 'ACTIVE', 'HairStylist' => 'YES', 'verifire' => 'YES','membership_status'=> 'OK'])->paginate($limit);
            }else{
                $items = User::where(['status' => 'ACTIVE', 'HairStylist' => 'YES', 'verifire' => 'YES'])->paginate($limit);
            }
        }

        if (count($items)) {
            foreach ($items as $item) {
                $count_comments = Comment::with('user')->where(['post_id' => $item->id, 'parent' => 0, 'status' => 'SEEN', 'type' => 'product'])->get();
                ?>
                <div id="product" data-page="<?= $pageNum2 + 1 ?>" class="card">
                    <div class="card-body">
                        <div class="doctor-widget">
                            <div class="doc-info-left">
                                <div class="doctor-img">
                                    <a href="/hairstylist/<?= $item->username ?>">
                                        <?php if ($item->avatar == "") { ?>
                                            <img class="img-fluid" src="<?= asset('assets/profile.png') ?>"
                                                 alt="<?= $item->nameShop ?>">
                                        <?php } else { ?>
                                            <img class="img-fluid" src="<?= asset($item->avatar) ?>"
                                                 alt="<?= $item->nameShop ?>">
                                        <?php } ?>
                                    </a>
                                </div>
                                <div class="doc-info-cont">
                                    <h4 class="doc-name"><a
                                                href="/hairstylist/<?= $item->username ?>"><?= $item->nameShop ?></a>
                                    </h4>

                                    <?php
                                    $rating = [];
                                    $sum_rating = 0;
                                    foreach ($count_comments as $count) {
                                        @$rating[] = @$count->rating;
                                    }
                                    if ($rating) {
                                        $sum_rating = array_sum($rating) / count($count_comments);
                                    }
                                    ?>
                                    <div class="rating" style="width: 80px;overflow: hidden;display: inline-flex;">
                                        <?php for ($i = 0; $i < $sum_rating; $i++) { ?>
                                            <i class="fas fa-star filled"></i>
                                        <?php } ?>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <span class="d-inline-block average-rating">(<?= count($count_comments) ?>)</span>
                                    <div class="clinic-details">
                                        <p class="doc-location"><i style="margin-left: 5px;"
                                                                   class="fas fa-map-marker-alt"></i><?= $item->address ?>
                                        </p>
                                        <?php $galleries = Gallery::where('user_id', $item->id)->take(6)->get() ?>
                                        <?php if (count($galleries)) { ?>
                                            <ul class="clinic-gallery">
                                                <?php foreach ($galleries as $gallery) { ?>
                                                    <li>
                                                        <a href="<?= asset($gallery->imagePath) ?>"
                                                           data-fancybox="gallery">
                                                            <img src="<?= asset($gallery->imagePath) ?>"
                                                                 alt="<?= $gallery->imageName ?>">
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        <?php } ?>
                                    </div>

                                    <?php  $services_users = CategoryUser::with('category')->where('user_id', $item->id)->get(); ?>
                                    <div class="clinic-services">
                                        <?php foreach($services_users as $service_user) { ?>
                                            <span><?= $service_user->category->title ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="doc-info-right">
                                <div class="clini-infos">
                                    <ul>
                                        <li><i class="far fa-comment"></i> <?= count($count_comments) ?> فید بک</li>
                                        <?php if ($item->workTime) { ?>
                                            <li>
                                            <i class="far fa-clock"></i><?= $item->workTime ?>
                                            </li><?php } ?>
                                        <?php if ($item->approximate_price) { ?>
                                            <li>
                                            <i class="far fa-money-bill-alt"></i><?= $item->approximate_price ?>
                                            </li><?php } ?>
                                    </ul>
                                </div>
                                <div class="clinic-booking">
                                    <a class="view-pro-btn" href="/hairstylist/<?= $item->username ?>">مشاهده
                                        پروفایل</a>
                                    <a class="apt-btn" href="/reserve/<?= $item->username ?>
">رزرو نوبت</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            <?php }
        } else {
            ?>
            <div class="card-footer bg-white border-top">
                <h6 style="text-align: right;width: 100%">آرایشگاه مورد نظر یافت نشد!</h6>
            </div>

        <?php }
    }

    public function hairstylist($username)
    {
        $item = User::where('username', $username)->first();
        if ($item){
            if (setting()['Payment_membership']=="ACTIVE") {
                if ($item) {
                    if ($item->membership_status == "OK") {
                        $galleries = \App\Models\Gallery::where('user_id', $item->id)->take(6)->get();
                        $comments = Comment::with('user')->where(['post_id' => $item->id, 'parent' => 0, 'status' => 'SEEN', 'type' => 'product'])->paginate(10);
                        $services = CategoryUser::with('category')->where('user_id', $item->id)->get();
                        $title = $item->name . " | " . setting()['title'];
                        $seo_title = $item->name . "," . setting()['seo_title'];
                        $seo_content = $item->name . "," . setting()['seo_content'];
                        return view('front.hairstylist.index', compact('title', 'seo_title', 'seo_content', 'item', 'galleries', 'comments', 'services'));
                    }else{
                        abort(404);
                    }
                }
            }else{
                $galleries = \App\Models\Gallery::where('user_id', $item->id)->take(6)->get();
                $comments = Comment::with('user')->where(['post_id' => $item->id, 'parent' => 0, 'status' => 'SEEN', 'type' => 'product'])->paginate(10);
                $services = CategoryUser::with('category')->where('user_id', $item->id)->get();
                $title = $item->name . " | " . setting()['title'];
                $seo_title = $item->name . "," . setting()['seo_title'];
                $seo_content = $item->name . "," . setting()['seo_content'];
                return view('front.hairstylist.index', compact('title', 'seo_title', 'seo_content', 'item', 'galleries', 'comments', 'services'));
            }
        }

        abort(404);
    }

    public function comment_store(Request $request)
    {
        session()->put('error', 'error');
        $this->validate($request, [
            'rating' => 'required',
            'title' => 'required',
            'comment' => 'required',
            'check' => 'required',
        ], [
            'rating.required' => 'امتیاز خود را ثبت کنید',
            'title.required' => 'عنوان نظر خود را وارد کنید',
            'comment.required' => 'نظر خود را وارد کنید',
            'check.required' => 'تیک شرایط را قبول دارم الزامی است',
        ]);
        session()->forget('error');
        $item = new Comment();
        $item->rating = $request->rating;
        $item->title = $request->title;
        $item->user_id = Auth::id();
        $item->post_id = $request->post_id;
        $item->type = "product";
        $item->comment = $request->comment;
        $item->save();
        session()->put('create-success', 'نظر شما با موفقیت ذخیره شد، بعد از تائید در سایت نمایش داده می شود');
        return redirect()->back();
    }


    public function contact()
    {

        $title = "تماس باما | " . setting()['title'];
        $seo_title = "," . setting()['seo_title'];
        $seo_content = "," . setting()['seo_content'];
        return view('front.contact.index', compact('title', 'seo_title', 'seo_content'));

    }

    public function about()
    {
        $item = More::where('type', 'about')->first();
        $title = "درباره ما | " . setting()['title'];
        $seo_title = "," . setting()['seo_title'];
        $seo_content = "," . setting()['seo_content'];
        return view('front.about.index', compact('title', 'seo_title', 'seo_content', 'item'));

    }

    public function guide()
    {
        $item = More::where('type', 'guide')->first();
        $title = "راهنمای سایت | " . setting()['title'];
        $seo_title = "," . setting()['seo_title'];
        $seo_content = "," . setting()['seo_content'];
        return view('front.guide.index', compact('title', 'seo_title', 'seo_content', 'item'));
    }

    public function privacy()
    {
        $item = More::where('type', 'privacy')->first();
        $title = "حریم خصوصی | " . setting()['title'];
        $seo_title = "," . setting()['seo_title'];
        $seo_content = "," . setting()['seo_content'];
        return view('front.privacy.index', compact('title', 'seo_title', 'seo_content', 'item'));
    }

    public function contact_store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'nullable|email',
            'message' => 'required',
            'mobile' => 'required|regex:/(09)[0-9]{9}/|digits:11',
        ], [
            'name.required' => 'فیلد نام و نام خانوادگی ضروری است',
            'email.required' => 'فیلد ایمیل ضروری است',
            'email.email' => 'فرمت ایمیل صحیح نیست',
            'mobile.regex' => 'فرمت موبایل صحیح نیست',
            'mobile.digits' => 'فرمت موبایل صحیح نیست',
            'message.required' => 'فیلد پیام ضروری است',
        ]);
        $item = new Contact();
        $item->name = $request->name;
        $item->email = $request->email;
        $item->message = $request->message;
        $item->mobile = $request->mobile;
        $item->save();
        session()->put('store-success', 'اطلاعات شما با موفقیت ذخیره شد');
        return redirect()->back();
    }

    public function reserve($username)
    {
        if (Auth::user()) {
            session()->forget('UrlBackReserve');
            if (Auth::user()->HairStylist == "NO") {
                $item = User::where('username', $username)->first();
                $blocked = BlockList::with('hairstylist', 'user')->where(['hairstylist_id' => $item->id, 'user_id' => Auth::id()])->first();
                if (!$blocked) {
                    $galleries = \App\Models\Gallery::where('user_id', $item->id)->take(6)->get();
                    $DesksServices= DesksService::where(['user_id'=> $item->id])->get();
                    $comments = Comment::with('user')->where(['post_id' => $item->id, 'parent' => 0, 'status' => 'SEEN', 'type' => 'product'])->paginate(10);
                    $services_user = CategoryUser::with('category')->where('user_id', $item->id)->get();
                    $TimingsDay = TimingsDay::orderby('id', 'asc')->get();
                    $title = $item->name . " | " . setting()['title'];
                    $seo_title = $item->name . "," . setting()['seo_title'];
                    $seo_content = $item->name . "," . setting()['seo_content'];
                    return view('front.hairstylist.reserve', compact('title', 'seo_title', 'seo_content', 'item', 'galleries', 'comments', 'services_user', 'TimingsDay','DesksServices'));

                } else {
                    session()->put('error-alert', 'کاربر گرامی شما توسط آرایشگر مسدود می باشید');
                    return redirect('/hairstylist/' . $username);
                }
            } else {
                return redirect('/hairstylist/' . $username);
            }
        } else {
            session()->put('UrlBackReserve', asset('') . 'reserve/' . $username);
            return redirect('/login');
        }

    }

    public function set_reserve(Request $request, $id)
    {

        $this->validate($request, [
            'Timeings_id' => 'required',
            'service' => 'required',
            'hairstylist_id' => 'required',
            'rozhafteTitle' => 'required',
            'rozhafte' => 'required',
            'DayMonthYear' => 'required',
        ], [
            'Timeings_id.required' => 'زمان های روزو خود را انتخاب کنید',
            'service.required' => 'سرویس خود را انتخاب کنید',
        ]);

        $ServiceTime=[];
        foreach ($request->service as $time_service) {
            $TimeService = CategoryUser::where(['category_id' => $time_service, 'user_id' => $id,'desks_services_id'=>$request->DesksService])->first();
            $ServiceTime[] = $TimeService->time;
        }
        $AllServiceTime=array_sum($ServiceTime);
        $AllTimeings_id=explode(',',$request->Timeings_id);
        $AllTime=count($AllTimeings_id)*10;
        if ($AllTime==$AllServiceTime) {

            $DayMonthYear = explode('-', $request->DayMonthYear);
            $dayTitle = $DayMonthYear['0'];
            $year = $DayMonthYear['1'];
            $month = $DayMonthYear['2'];
            $day = $DayMonthYear['3'];


            $Timeings_ids = explode(',', $request->Timeings_id);


            $Reserves = Reserve::where(['hairstylist_id' => $request->hairstylist_id, 'year' => $year, 'month' => $month, 'day' => $day, 'dayTitle' => $dayTitle])->where('status', '!=', 'Cancel')->get();
            $isSet = "no";
            if (count($Reserves)) {
                foreach ($Reserves as $Reserve) {
                    $times = explode(',', $Reserve->times);

                    foreach ($Timeings_ids as $Timeings_id) {

                        if (in_array($Timeings_id,$times)) {
                            if ($Reserve->user_id!=Auth::id()){
                                if ($Reserve->desks_services_id!=$request->DesksService){
                                    $isSet="no";
                                }else{
                                    $isSet="yes";
                                }
                            }else{
                                $isSet="yes";
                            }
                        }
                    }
                }
            }

            /*if (count($Reserves)) {
                foreach ($Reserves as $Reserve) {
                    $times = explode(',', $Reserve->times);
                    foreach ($Timeings_ids as $Timeings_id) {
                        if (in_array($Timeings_id, $times)) {
                            $isSet = "yes";
                        }
                    }
                }
            }*/
            if ($isSet == "no") {

                $hairstylist = User::find($request->hairstylist_id);
                $user = User::find(Auth::id());
                if (@$request->pishPay == "YES") {
                    if ($request->pishPay_val < $hairstylist->pishPay_val) {
                        session()->put('error-alert', 'مبلغ پیش پرداخت کمتر از مبلغ مجاز است');
                        return redirect()->back();
                    }
                }


                $item = new Reserve();
                $item->user_id = Auth::id();
                $service = implode(',', $request->service);

                //$item->timings_id = $request->time;
                $item->service = $service;
                $item->times = $request->Timeings_id;
                $item->desks_services_id = $request->DesksServiceVal;


                $timings = Timing::wherein('id', $Timeings_ids)->get();
                $count_timings = count($timings);
                $hour_min = explode(':', $timings[0]->startTime);
                $hour_max = explode(':', $timings[$count_timings - 1]->endTime);

                $item->hour_min = $hour_min[0];
                $item->hour_max = $hour_max[0];
                $item->minute_min = $hour_min[1];
                $item->minute_max = $hour_max[1];
                $item->Time = $timings[0]->startTime . ' | ' . $timings[$count_timings - 1]->endTime;

                $hourMin = ($hour_min[0] * 60) + $hour_min[1];
                $hourMax = ($hour_max[0] * 60) + $hour_max[1];
                $item->hourMin = $hourMin;
                $item->hourMax = $hourMax;

                if ($request->service) {
                    $price = [];
                    foreach ($request->service as $service) {
                        $CategoryUser = CategoryUser::where(['category_id' => $service, 'user_id' => $id])->first();
                        $price[] = $CategoryUser->price;
                    }
                    $TotalPrice = array_sum($price);
                } else {
                    $TotalPrice = 0;
                }
                $pay_method_price = $TotalPrice;
                if ($request->pishPay == "YES") {
                    $pay_method_price = $request->pishPay_val;
                }

                /*================حداقا میزان پول==================*/
                if ($pay_method_price >= setting()['pay_min']) {


                    if ($request->pay_method == "wallet") {

                        if ($user->wallet < $pay_method_price) {
                            session()->put('error-alert', 'موجودی کیف پول شما کافی نمی باشد');
                            return redirect()->back();
                        }
                    }

                    $item->price = $TotalPrice;
                    $item->DayMonthYear = $request->DayMonthYear;
                    $item->dayTitle = $dayTitle;
                    $item->year = $year;
                    $item->month = $month;
                    $item->day = $day;
                    $item->hairstylist_id = $request->hairstylist_id;
                    $item->save();

                    $pay = new Pay();
                    $v = new Verta();
                    $factor = 'P-' . $v->year . $v->month . $v->day . $v->second . rand(100, 999) . Auth::id();
                    $pay->factor_number = $factor;
                    $pay->hairstylist_id = $request->hairstylist_id;
                    $pay->user_id = Auth::id();
                    $pay->reserve_id = $item->id;
                    $pay->price = $TotalPrice;
                    $pay->remaining_price = $TotalPrice;
                    $pay->pay_method = $request->pay_method;
                    $pay->save();


                    if (@$request->pishPay != "NOPay") {

                        if ($request->pay_method == "wallet") {

                            $user->wallet = $user->wallet - $pay_method_price;
                            $hairstylist->wallet = $hairstylist->wallet + $pay_method_price;
                            $update_pay = Pay::find($pay->id);
                            $update_pay->pay_price = $pay_method_price;

                            $remaining_price = $TotalPrice - $pay_method_price;
                            $update_pay->remaining_price = $remaining_price;
                            $update_pay->pay_status = "OK";
                            if ($request->pishPay == "YES" and $remaining_price == 0) {
                                $update_pay->pay_status = "OK";
                            } elseif ($request->pishPay == "YES" and $remaining_price != 0) {
                                $update_pay->pay_status = "PishPay";
                            }
                            $update_pay->save();
                            $hairstylist->save();
                            $user->save();


                            /* ===================== report=======================*/
                            $report = new Report();
                            $v = new Verta();
                            $factor = 'P-' . $v->year . $v->month . $v->day . $v->second . rand(100, 999) . Auth::id();
                            $report->factor_number = $factor;
                            $report->hairstylist_id = $hairstylist->id;
                            $report->user_id = $user->id;
                            $report->price = $TotalPrice;
                            $report->pay_price = $pay_method_price;
                            $report->remaining_price = $remaining_price;
                            $report->pay_status = "OK";
                            if ($request->pishPay == "YES" and $remaining_price == 0) {
                                $report->pay_status = "OK";
                            } elseif ($request->pishPay == "YES" and $remaining_price != 0) {
                                $report->pay_status = "PishPay";
                            }
                            $report->pay_method = $request->pay_method;
                            $report->type = "deposit";
                            $report->save();
                            /* =====================End report=======================*/
                            $username = trim(setting()['username_sms']);
                            $password = trim(setting()['password_sms']);
                            $from = "+983000505";
                            $pattern_code = "x0w5nw2upe";
                            $to = array($user->mobile);
                            $input_data = array("name" => $user->name, "hairStylist" => $hairstylist->nameShop, "date" => $item->DayMonthYear, "time" => $item->hour_min . ':' . $item->minute_min . ' تا ' . $item->hour_max . ':' . $item->minute_max);
                            $url = "https://ippanel.com/patterns/pattern?username=" . $username . "&password=" . urlencode($password) . "&from=$from&to=" . json_encode($to) . "&input_data=" . urlencode(json_encode($input_data)) . "&pattern_code=$pattern_code";
                            $handler = curl_init($url);
                            curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
                            curl_setopt($handler, CURLOPT_POSTFIELDS, $input_data);
                            curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
                            $response = curl_exec($handler);

                            session()->put('reserve-success', $update_pay->id);
                            return redirect('/profile/reserve-success');
                        } elseif ($request->pay_method == "online") {

                            $payment = new Payment('payment-verify-reserve', $pay_method_price, null, $item->id, $pay_method_price);
                            $result = $payment->doPayment();
                            $res = $result->return . ',test';
                            $res = explode(',', $res);
                            if ($res[0] == "0") {
                                return view('mellat')->with(['tokenId' => $res[1]]);
                            } else {
                                $item->delete();
                                echo $this->MellatErrors($result->return);
                            }

                        }

                    } elseif (@$request->pishPay == "NOPay") {
                        $update_pay = Pay::find($pay->id);
                        $update_pay->pay_price = 0;

                        $remaining_price = $TotalPrice;
                        $update_pay->remaining_price = $remaining_price;
                        $update_pay->pay_status = "NOPay";
                        $update_pay->save();
                        $user->save();


                        /* ===================== report=======================*/
                        $report = new Report();
                        $v = new Verta();
                        $factor = 'P-' . $v->year . $v->month . $v->day . $v->second . rand(100, 999) . Auth::id();
                        $report->factor_number = $factor;
                        $report->hairstylist_id = $hairstylist->id;
                        $report->user_id = $user->id;
                        $report->price = $TotalPrice;
                        $report->pay_price = 0;
                        $report->remaining_price = $remaining_price;
                        $report->pay_status = "NOPay";
                        $report->pay_method = "NOPay";
                        $report->type = "deposit";
                        $report->save();
                        /* =====================End report=======================*/
                        $username = trim(setting()['username_sms']);
                        $password = trim(setting()['password_sms']);
                        $from = "+983000505";
                        $pattern_code = "x0w5nw2upe";
                        $to = array($user->mobile);
                        $input_data = array("name" => $user->name, "hairStylist" => $hairstylist->nameShop, "date" => $item->DayMonthYear, "time" => $item->hour_min . ':' . $item->minute_min . ' تا ' . $item->hour_max . ':' . $item->minute_max);
                        $url = "https://ippanel.com/patterns/pattern?username=" . $username . "&password=" . urlencode($password) . "&from=$from&to=" . json_encode($to) . "&input_data=" . urlencode(json_encode($input_data)) . "&pattern_code=$pattern_code";
                        $handler = curl_init($url);
                        curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
                        curl_setopt($handler, CURLOPT_POSTFIELDS, $input_data);
                        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
                        $response = curl_exec($handler);

                        session()->put('reserve-success', $update_pay->id);
                        return redirect('/profile/reserve-success');
                    }
                } else {
                    session()->put('error-alert', 'حداقل مبلغ شما باید ' . setting()['pay_min'] . ' تومان باشد');
                    return redirect()->back();
                }


            } else {
                session()->put('error-alert', 'تایم انتخابی شما توسط شخص دیگری رزرو شده است');
                return redirect()->back();
            }
        }else{
            session()->put('error-alert', 'تایم ها را طبق زمان سرویس انتخاب نمایید');
            return redirect()->back();
        }

    }

    public function payment_verify_reserve(Request $request)
    {
        $sood_price = setting()['sood'];
        if (setting()['sood'] == "") {
            $sood_price = 0;
        }
        $reserve = Reserve::find($request->SaleOrderId);
        $user = User::find($reserve->user_id);
        $hairstylist = User::find($reserve->hairstylist_id);
        $payment = new Payment(null, $request->data, null, $request->SaleOrderId, $request->SaleReferenceId);
        $result = $payment->verifyPayment();
        if ($result->return == "0") {
            $result = $payment->settleRequest();
            if ($result->return == "0") {
                $pay = Pay::where('reserve_id',$reserve->id)->first();
                $pay->authority = $request->SaleReferenceId;
                $pay->RefId = $request->RefId;

                /* =====================report=======================*/
                $report = new Report();
                $v = new Verta();
                $factor = 'P-' . $v->year . $v->month . $v->day . $v->second . rand(100, 999) . Auth::id();
                $report->factor_number = $factor;
                $report->authority = $request->SaleReferenceId;
                $report->hairstylist_id = $reserve->hairstylist_id;
                $report->RefId = $request->RefId;
                $report->user_id = $reserve->user_id;
                $report->price = $reserve->price;
                $report->pay_price = $request->data - $sood_price;
                $remaining_price = $reserve->price - $request->data;
                $report->remaining_price = $remaining_price;
                $report->pay_status = "OK";
                if ($remaining_price == 0) {
                    $report->pay_status = "OK";
                } elseif ($remaining_price != 0) {
                    $report->pay_status = "PishPay";
                }
                $report->pay_method = "online";
                $report->type = "deposit";
                $report->save();
                /* =====================End report=======================*/

                $pay->pay_price = $request->data - $sood_price;
                $pay->remaining_price = $remaining_price;
                if ($remaining_price == 0) {
                    $pay->pay_status = "OK";
                } elseif ($remaining_price != 0) {
                    $pay->pay_status = "PishPay";
                }
                $pay->save();
                $wallet_price = $hairstylist->wallet + $request->data;
                $hairstylist->wallet = $wallet_price - $sood_price;
                $hairstylist->save();

                /*==============SMS================*/
                /*
                 * عزیز رزرو نوبت با موفقیت انجام شد.
نام آرایشگاه: %hairStylist%
تاریخ رزرو: %date%
ساعت رزرو: %time%
                 * */
                $username = trim(setting()['username_sms']);
                $password = trim(setting()['password_sms']);
                $from = "+983000505";
                $pattern_code = "x0w5nw2upe";
                $to = array($user->mobile);
                $input_data = array("name" => $user->name, "hairStylist" => $hairstylist->nameShop, "date" => $reserve->DayMonthYear, "time" => $reserve->hour_min.':'.$reserve->minute_min . ' تا ' . $reserve->hour_max.':'.$reserve->minute_max);
                $url = "https://ippanel.com/patterns/pattern?username=" . $username . "&password=" . urlencode($password) . "&from=$from&to=" . json_encode($to) . "&input_data=" . urlencode(json_encode($input_data)) . "&pattern_code=$pattern_code";
                $handler = curl_init($url);
                curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($handler, CURLOPT_POSTFIELDS, $input_data);
                curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($handler);
                /*==============SMS=============*/


                Auth::login($user);
                session()->put('reserve-success', $pay->id);
                return redirect('/profile/reserve-success');
                //پرداخت شما انجام شد
                //ریدایرکت
            } else {
                $pay = Pay::where('reserve_id',$reserve->id)->first();
                $pay->pay_status = "NOK";
                $pay->authority = $request->SaleReferenceId;
                $pay->RefId = $request->RefId;
                $pay->save();

                $report = new Report();
                $v = new Verta();
                $factor = 'P-' . $v->year . $v->month . $v->day . $v->second . rand(100, 999) . Auth::id();
                $report->factor_number = $factor;
                $report->authority = $request->SaleReferenceId;
                $report->hairstylist_id = $reserve->hairstylist_id;
                $report->RefId = $request->RefId;
                $report->user_id = $reserve->user_id;
                $report->price = $reserve->price;
                $report->pay_price = 0;
                $remaining_price = $reserve->price;
                $report->remaining_price = $remaining_price;
                $report->pay_status = "NOK";
                $report->pay_method = "online";
                $report->type = "deposit";
                $report->save();
                $reserve->delete();
                Auth::login($user);

                /*==============SMS================*/
                /*
                 *عزیز رزرو نوبت انجام نشد.پرداخت شما با شکست مواجه شد.
                 * */
                $username = trim(setting()['username_sms']);
                $password = trim(setting()['password_sms']);
                $from = "+983000505";
                $pattern_code = "6owczd0t5v";
                $to = array($user->mobile);
                $input_data = array("name" => $user->name);
                $url = "https://ippanel.com/patterns/pattern?username=" . $username . "&password=" . urlencode($password) . "&from=$from&to=" . json_encode($to) . "&input_data=" . urlencode(json_encode($input_data)) . "&pattern_code=$pattern_code";
                $handler = curl_init($url);
                curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($handler, CURLOPT_POSTFIELDS, $input_data);
                curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($handler);
                /*==============SMS=============*/


                session()->put('reserve-danger', $pay->id);
                return redirect('/profile/reserve-danger');
                //پرداخت شما انجام نشد
                //ریدایرکت
            }
        } else {
            $pay = Pay::where('reserve_id',$reserve->id)->first();
            $pay->pay_status = 'NOK';
            $pay->authority = $request->SaleReferenceId;
            $pay->RefId = $request->RefId;
            $pay->save();

            $report = new Report();
            $v = new Verta();
            $factor = 'P-' . $v->year . $v->month . $v->day . $v->second . rand(100, 999) . Auth::id();
            $report->factor_number = $factor;
            $report->authority = $request->SaleReferenceId;
            $report->hairstylist_id = $reserve->hairstylist_id;
            $report->RefId = $request->RefId;
            $report->user_id = $reserve->user_id;
            $report->price = $reserve->price;
            $report->pay_price = 0;
            $remaining_price = $reserve->price;
            $report->remaining_price = $remaining_price;
            $report->pay_status = "NOK";
            $report->pay_method = "online";
            $report->type = "deposit";
            $report->save();
            $reserve->delete();
            Auth::login($user);
            /*==============SMS================*/
            /*
             *عزیز رزرو نوبت انجام نشد.پرداخت شما با شکست مواجه شد.
             * */
            $username = trim(setting()['username_sms']);
            $password = trim(setting()['password_sms']);
            $from = "+983000505";
            $pattern_code = "6owczd0t5v";
            $to = array($user->mobile);
            $input_data = array("name" => $user->name);
            $url = "https://ippanel.com/patterns/pattern?username=" . $username . "&password=" . urlencode($password) . "&from=$from&to=" . json_encode($to) . "&input_data=" . urlencode(json_encode($input_data)) . "&pattern_code=$pattern_code";
            $handler = curl_init($url);
            curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($handler, CURLOPT_POSTFIELDS, $input_data);
            curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($handler);
            /*==============SMS=============*/

            session()->put('reserve-danger', $pay->id);
            return redirect('/profile/reserve-danger');
            //پرداخت شما انجام نشد
            //ریدایرکت
        }

    }


    public function MellatErrors($err)
    {
        switch ($err) {
            case "11":
                return "شماره كارت نامعتبر است";
                break;
            case "12":
                return "موجودي كافي نيست";
                break;
            case "13":
                return "رمز نادرست است";
                break;
            case "14":
                return "تعداد دفعات وارد كردن رمز بيش از حد مجاز است";
                break;
            case "15":
                return "كارت نامعتبر است";
                break;
            case "16":
                return "دفعات برداشت وجه بيش از حد مجاز است";
                break;
            case "17":
                return "كاربر از انجام تراكنش منصرف شده است";
                break;
            case "18":
                return "تاريخ انقضاي كارت گذشته است";
                break;
            case "19":
                return "مبلغ برداشت وجه بيش از حد مجاز است";
                break;
            case "111":
                return "صادر كننده كارت نامعتبر است";
                break;
            case "112":
                return "خطاي سوييچ صادر كننده كارت";
                break;
            case "113":
                return "پاسخي از صادر كننده كارت دريافت نشد";
                break;
            case "114":
                return "دارنده كارت مجاز به انجام اين تراكنش نيست";
                break;
            case "21":
                return "پذيرنده نامعتبر است";
                break;
            case "23":
                return "خطاي امنيتي رخ داده است";
                break;
            case "24":
                return "اطلاعات كاربري پذيرنده نامعتبر است";
                break;
            case "25":
                return "مبلغ نامعتبر است";
                break;
            case "31":
                return "پاسخ نامعتبر است";
                break;
            case "32":
                return "فرمت اطلاعات وارد شده صحيح نمي باشد";
                break;
            case "33":
                return "حساب نامعتبر است";
                break;
            case "34":
                return "خطاي سيستمي";
                break;
            case "35":
                return "تاريخ نامعتبر است";
                break;
            case "41":
                return "شماره درخواست تكراري است";
                break;
            case "42":
                return "تراكنش Sale يافت نشد";
                break;
            case "43":
                return "قبلا درخواست Verify داده شده است";
                break;
            case "44":
                return "درخواست Verfiy يافت نشد";
                break;
            case "45":
                return "تراكنش Settle شده است";
                break;
            case "46":
                return "تراكنش Settle نشده است";
                break;
            case "47":
                return "تراكنش Settle يافت نشد";
                break;
            case "48":
                return "تراكنش Reverse شده است";
                break;
            case "49":
                return "تراكنش Refund يافت نشد";
                break;
            case "412":
                return "شناسه قبض نادرست است";
                break;
            case "413":
                return "شناسه پرداخت نادرست است";
                break;
            case "414":
                return "سازمان صادر كننده قبض نامعتبر است";
                break;
            case "415":
                return "زمان جلسه كاري به پايان رسيده است";
                break;
            case "416":
                return "خطا در ثبت اطلاعات";
                break;
            case "417":
                return "شناسه پرداخت كننده نامعتبر است";
                break;
            case "418":
                return "اشكال در تعريف اطلاعات مشتري";
                break;
            case "419":
                return "تعداد دفعات ورود اطلاعات از حد مجاز گذشته است";
                break;
            case "421":
                return "IP نامعتبر است";
                break;
            case "51":
                return "تراكنش تكراري است";
                break;
            case "54":
                return "تراكنش مرجع موجود نيست";
                break;
            case "55":
                return "تراكنش نامعتبر است";
                break;
            case "61":
                return "خطا در واريز";
                break;
            default:
                return "خطای مورد نظر در سیستم وجود ندارد!";
        }
    }
}
