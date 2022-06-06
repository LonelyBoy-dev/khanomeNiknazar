<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminTrashedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($data_table = null)
    {
        if (admin()->can('trashed')) {
            if ($data_table == "") {
                if (admin()->can('users')) {
                    $data_table = "users";
                }elseif (admin()->can('admins')) {
                    $data_table = "admins";
                }
                elseif (admin()->can('posts')) {
                    $data_table = "posts_index";
                }
                elseif (admin()->can('postCategories')) {
                    $data_table = "posts_category";
                }
            }
            $count_items = [];
            $items = [];
            @$search = $_GET['search'];
            @$cView = $_GET['cView'];
            if ($cView) {
                $pageNum = $cView;
            } else {
                $pageNum = 10;
            }
            if ($search) {

                if ($search == "فعال" or $search == "غیر فعال" or $search == "فعال" or $search == "غیرفعال") {
                    if ($search == "فعال") {
                        $status = "ACTIVE";
                    } elseif ($search == "غیر فعال") {
                        $status = "INACTIVE";
                    } elseif ($search == "غیرفعال") {
                        $status = "INACTIVE";
                    }
                    if ($data_table == "users") {
                        if (admin()->can('users')) {
                            $items = User::onlyTrashed()->where('status',$status)->orderBy('id', 'desc')->paginate($pageNum);
                            $count_items = User::onlyTrashed()->where('status',$status)->orderBy('id', 'desc')->get();
                        } else {
                            abort(403);
                        }
                    }
                    if ($data_table == "admins") {
                        if (admin()->can('admins')) {
                            $items = Admin::onlyTrashed()->where('status',$status)->orderBy('id', 'desc')->paginate($pageNum);
                            $count_items = Admin::onlyTrashed()->where('status',$status)->orderBy('id', 'desc')->get();
                        } else {
                            abort(403);
                        }
                    }
                    if ($data_table == "posts") {
                        if (admin()->can('posts_index')) {
                            $items = Post::onlyTrashed()->with('postcategories')->where('status',$status)->orderBy('id', 'desc')->paginate($pageNum);
                            $count_items = Post::onlyTrashed()->with('postcategories')->where('status',$status)->orderBy('id', 'desc')->get();
                        } else {
                            abort(403);
                        }
                    }


                } else {


                    if ($data_table == "users") {
                        if (admin()->can('users')) {
                            $items = User::onlyTrashed()->where('name', 'like', '%' . $search . '%')->orwhere('mobile', 'like', '%' . $search . '%')->orderBy('id', 'desc')->paginate($pageNum);
                            $count_items = User::onlyTrashed()->where('name', 'like', '%' . $search . '%')->orwhere('mobile', 'like', '%' . $search . '%')->orderBy('id', 'desc')->get();
                        } else {
                            abort(403);
                        }
                    }
                    if ($data_table == "admins") {
                        if (admin()->can('admins')) {
                            $items = Admin::onlyTrashed()->where('name', 'like', '%' . $search . '%')->orwhere('mobile', 'like', '%' . $search . '%')->orderBy('id', 'desc')->paginate($pageNum);
                            $count_items = Admin::onlyTrashed()->where('name', 'like', '%' . $search . '%')->orwhere('mobile', 'like', '%' . $search . '%')->orderBy('id', 'desc')->get();
                        } else {
                            abort(403);
                        }
                    }
                    if ($data_table == "posts") {
                        if (admin()->can('posts_index')) {
                            $items = Post::onlyTrashed()->with('postcategories')->where('name', 'like', '%' . $search . '%')->orwhere('mobile', 'like', '%' . $search . '%')->orderBy('id', 'desc')->paginate($pageNum);
                            $count_items = Post::onlyTrashed()->with('postcategories')->where('name', 'like', '%' . $search . '%')->orwhere('mobile', 'like', '%' . $search . '%')->orderBy('id', 'desc')->get();
                        } else {
                            abort(403);
                        }
                    }

                    if ($data_table == "postCategories") {
                        if (admin()->can('posts_category')) {
                            $items = PostCategory::onlyTrashed()->where('title', 'like', '%' . $search . '%')->orderBy('id', 'desc')->paginate($pageNum);
                            $count_items = PostCategory::onlyTrashed()->where('title', 'like', '%' . $search . '%')->orderBy('id', 'desc')->get();
                        } else {
                            abort(403);
                        }
                    }


                }
            } else {


                if ($data_table == "users") {
                    if (admin()->can('users')) {
                        $items = User::onlyTrashed()->orderBy('id', 'desc')->paginate($pageNum);
                        $count_items = User::onlyTrashed()->orderBy('id', 'desc')->get();
                    } else {
                        abort(403);
                    }
                }
                if ($data_table == "admins") {
                    if (admin()->can('admins')) {
                        $items = Admin::onlyTrashed()->orderBy('id', 'desc')->paginate($pageNum);
                        $count_items = Admin::onlyTrashed()->orderBy('id', 'desc')->get();
                    } else {
                        abort(403);
                    }
                }
                if ($data_table == "posts") {
                    if (admin()->can('posts_index')) {
                        $items = Post::onlyTrashed()->with('postcategories')->orderBy('id', 'desc')->paginate($pageNum);
                        $count_items = Post::onlyTrashed()->with('postcategories')->orderBy('id', 'desc')->get();
                    } else {
                        abort(403);
                    }
                }
                if ($data_table == "postCategories") {
                    if (admin()->can('posts_category')) {
                        $items = PostCategory::onlyTrashed()->orderBy('id', 'desc')->paginate($pageNum);
                        $count_items = PostCategory::onlyTrashed()->orderBy('id', 'desc')->get();
                    } else {
                        abort(403);
                    }
                }


            }
            $title = "فایل های حذف شده";
            $Active_list = "";
            $Active = "trashed";
            $pageNum = count($count_items);
            return view('admin.trashed.index', compact(['items', 'Active', 'Active_list', 'title', 'pageNum', 'data_table']));
        } else {
            abort(403);
        }
    }


}
