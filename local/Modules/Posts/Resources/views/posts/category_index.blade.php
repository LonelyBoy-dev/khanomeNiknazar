@extends('admin.layout.app')
@section('style')
    <style>
        @media screen and (max-width: 992px) {
            .limiter table tbody tr td:nth-child(1):before {
                content: "انتخاب";
            }
            .limiter table tbody tr td:nth-child(2):before {
                content: "عنوان";
            }

            .limiter table tbody tr td:nth-child(3):before {
                content: "نامک";
            }

            .limiter table tbody tr td:nth-child(4):before {
                content: "دسته مادر";
            }

            .limiter table tbody tr td:nth-child(5):before {
                content: "تاریخ ایجاد";
            }

            .limiter table tbody tr td:nth-child(6):before {
                content: "فعالیت ها";
            }
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-body header-button-group">
                <div class="button-group">
                    <a href="/admin/posts/categories/create" type="button" class="btn btn-primary"><i class="mdi mdi-plus"></i> افزودن جدید</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row operation">
                        <div id="Action-show" class="row col-md-4">
                            <label class="w-100 p-r-20">نوع عملیات</label>
                            <div class="form-group col-10 col-md-8">
                                <select class="form-control" name="Select-Action-Show">
                                    <option>انتخاب کنید</option>
                                    <option value="delete">حذف</option>
                                </select>
                            </div>
                            <div class="form-group col-2 col-md-4">
                                <button onclick="delete_all_items('{{$table}}','{{ csrf_token() }}')" style="margin-right: -20px" type="button" class="btn waves-effect waves-light btn-info btn-color-topbar">انجام</button>
                            </div>
                        </div>
                        <form style="display: contents;" method="get" action="/admin/posts/categories">
                        <div class="col-xs-12  col-md-4">
                            <div class="row">
                                <label class="w-100 p-r-20">جستجو</label>
                                <div class="form-group col-10 col-md-8">
                                    <input type="text" name="search" value="{{@$_GET['search']}}" placeholder="عنوان را وارد کنید" class="form-control form-control-line">
                                </div>
                                <div class="form-group col-2 col-md-4">
                                    <button  type="submit" class="btn waves-effect waves-light btn-info btn-color-topbar" style="margin-right: -20px"><i class="fa fa-search"></i></button>
                                </div>
                            </div>

                        </div>
                        <div class="col-xs-12  col-md-2">

                            <div class="form-group col-12 col-md-8 p-0">
                                <label>تعداد نمایش</label>
                            <select class="form-control" name="cView" onchange="countView()">
                                <option @if(@$_GET['cView']=="10") selected @endif value="10">10</option>
                                <option @if(@$_GET['cView']=="20") selected @endif value="20">20</option>
                                <option @if(@$_GET['cView']=="40") selected @endif value="40">40</option>
                            </select>
                            </div>
                        </div>
                    </form>
                    </div>


                    <div class="limiter">
                        <div class="container-table100">
                            <div class="wrap-table100">
                                <div class="table100">
                                    <table class="table table-bordered" data-page="<?= $pageNum+1?>">
                                        <thead>
                                        <tr class="table100-head topbar">
                                            <th class="">
                                                <input type="checkbox" id="check_All" class="filled-in chk-col-light-blue">
                                                <label style="top: 20px" for="check_All"></label>
                                            </th>
                                            <th>عنوان</th>
                                            <th>نامک</th>
                                            <th>دسته مادر</th>
                                            <th>تاریخ ایجاد</th>
                                            <th>فعالیت ها</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(count($items))
                                        @foreach($items as $item)

                                            @php $parent = \App\Models\PostCategory::where('id', $item->parent)->first();
                                        if(empty($parent)){
                                        $parent = 'ندارد';
                                        }else{
                                        $parent = $parent->title;
                                        } @endphp
                                            <tr id="item{{$item->id}}">
                                                <td class="1">
                                                    <input type="checkbox" name="delete" id="check_{{$item->id}}" value="{{$item->id}}" class="filled-in chk-col-light-blue checkBox">
                                                    <label style="top: 8px" for="check_{{$item->id}}"></label>
                                                </td>
                                                <td>{{$item->title}}</td>
                                                <td>{{$item->slug}}</td>
                                                <td><span class="badge badge-success ml-auto">{{$parent}}</span></td>
                                                <td class="5">{{Verta::instance($item->created_at)}}</td>
                                                <td class="text-nowrap">
                                                    <a href="/admin/posts/categories/edit/{{$item->id}}" data-toggle="tooltip" data-original-title="ویرایش" aria-describedby="tooltip488585"> <i class="fa fa-pencil text-inverse m-r-10"></i> </a>
                                                    <a onclick="delete_solo_item(this,'{{$item->id}}','{{$table}}','{{ csrf_token() }}')" data-toggle="tooltip" data-original-title="حذف"> <i class="fa fa-trash-o text-danger"></i> </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6">
                                                    هیچ داده‌ای در جدول وجود ندارد
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        {{$items->links("pagination::bootstrap-4")}}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
