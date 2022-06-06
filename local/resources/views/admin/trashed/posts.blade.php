@php $table = base64_encode('posts'); @endphp
<style>
    @media screen and (max-width: 992px) {
        .limiter table tbody tr td:nth-child(1):before {
            content: "انتخاب";
        }
        .limiter table tbody tr td:nth-child(2):before {
            content: "تصویر";
        }

        .limiter table tbody tr td:nth-child(3):before {
            content: "عنوان";
        }

        .limiter table tbody tr td:nth-child(4):before {
            content: "دسته بندی";
        }

        .limiter table tbody tr td:nth-child(5):before {
            content: "بازدید";
        }

        .limiter table tbody tr td:nth-child(6):before {
            content: "تاریخ انتشار";
        }


        .limiter table tbody tr td:nth-child(7):before {
            content: "وضعیت";
        }

        .limiter table tbody tr td:nth-child(8):before {
            content: "فعالیت ها";
        }
    }
</style>
<div class="card-body">
    <div class="row operation">
        <div id="Action-show" class="row col-md-4">
            <label class="w-100 p-r-20">نوع عملیات</label>
            <div class="form-group col-10 col-md-8">
                <select class="form-control" name="Select-Action-Show">
                    <option>انتخاب کنید</option>
                    <option value="restore_trash">بازگردانی</option>
                    <option value="delete">حذف</option>
                </select>
            </div>
            <div class="form-group col-2 col-md-4">
                <button onclick="delete_all_items('{{$table}}','{{ csrf_token() }}','forceDelete')" style="margin-right: -20px" type="button" class="btn waves-effect waves-light btn-info btn-color-topbar">انجام</button>
            </div>
        </div>
        <form style="display: contents;" method="get" action="{{route('posts.index')}}">
            <div class="col-xs-12  col-md-4">
                <div class="row">
                    <label class="w-100 p-r-20">جستجو</label>
                    <div class="form-group col-10 col-md-8">
                        <input type="text" name="search" value="{{@$_GET['search']}}" placeholder="عنوان،وضعیت را وارد کنید" class="form-control form-control-line">
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
                            <th>تصویر</th>
                            <th>عنوان</th>
                            <th>دسته بندی</th>
                            <th>بازدید</th>
                            <th>تاریخ انتشار</th>
                            <th>وضعیت</th>
                            <th>فعالیت ها</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($items))
                        @foreach($items as $item)
                            <tr id="item{{$item->id}}">
                                <td>
                                    <input type="checkbox" name="delete" id="check_{{$item->id}}" value="{{$item->id}}" class="filled-in chk-col-light-blue checkBox">
                                    <label style="top: 8px" for="check_{{$item->id}}"></label>
                                </td>
                                <td>
                                    <a href="/blog/{{$item->slug}}">  <img src="{{asset($item->image)}}" style="width: 100px;border-radius: 3px"></a>
                                </td>
                                <td><a href="/blog/{{$item->slug}}">{{$item->title}}</a></td>
                                <td>
                                    @foreach ($item->postcategories as $val)
                                        <span class="label label-primary m-b-5">{{$val->title}}</span>
                                    @endforeach
                                </td>
                                <td><span class="label label-success">{{$item->view}}</span></td>
                                <td>{{Verta::instance($item->created_at)}}</td>
                                <td id="status">@if($item->status=="PUBLISHED")<span class="badge badge-success ml-auto">انتشار</span>@else<span class="badge badge-danger ml-auto">پیش نویس</span> @endif</td>
                                <td class="text-nowrap">
                                    <a href="{{route('posts.edit',$item->id)}}" target="_blank" data-toggle="tooltip" data-original-title="ویرایش"> <i class="fa fa-pencil text-inverse"></i> </a>
                                    <a onclick="restore('{{$item->id}}','{{$table}}','{{ csrf_token() }}')" class="m-r-5" data-toggle="tooltip" data-original-title="بازگردانی"> <i class="fa fa-history"></i> </a>
                                    <a onclick="delete_solo_item(this,'{{$item->id}}','{{$table}}','{{ csrf_token() }}','forceDelete')" data-toggle="tooltip" data-original-title="حذف"> <i class="fa  fa-trash-o text-danger"></i> </a>
                                </td>
                            </tr>
                        @endforeach
                        @else
                            <tr>
                                <td colspan="9">
                                    مطالب حذف شده ای وجود ندارد
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
