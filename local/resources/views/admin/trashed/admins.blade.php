@php $table = base64_encode('admins'); @endphp
<style>
    @media screen and (max-width: 992px) {
        .limiter table tbody tr td:nth-child(1):before {
            content: "انتخاب";
        }
        .limiter table tbody tr td:nth-child(2):before {
            content: "آواتار";
        }

        .limiter table tbody tr td:nth-child(3):before {
            content: "نام و نام خانوادگی";
        }

        .limiter table tbody tr td:nth-child(4):before {
            content: "موبایل";
        }

        .limiter table tbody tr td:nth-child(5):before {
            content: "ایمیل";
        }

        .limiter table tbody tr td:nth-child(6):before {
            content: "تاریخ آخرین ورود";
        }

        .limiter table tbody tr td:nth-child(7):before {
            content: "تاریخ ثبت نام";
        }

        .limiter table tbody tr td:nth-child(8):before {
            content: "وضعیت";
        }

        .limiter table tbody tr td:nth-child(9):before {
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
                <button
                    onclick="delete_all_items('{{$table}}','{{ csrf_token() }}','forceDelete')"
                    style="margin-right: -20px" type="button"
                    class="btn waves-effect waves-light btn-info btn-color-topbar">انجام
                </button>
            </div>
        </div>
        <form style="display: contents;" method="get" action="/admin/trashed/users">
            <div class="col-xs-12  col-md-4">
                <div class="row">
                    <label class="w-100 p-r-20">جستجو</label>
                    <div class="form-group col-10 col-md-8">
                        <input type="text" name="search" value="{{@$_GET['search']}}"
                               placeholder="نام،شماره موبایل،وضعیت را وارد کنید"
                               class="form-control form-control-line">
                    </div>
                    <div class="form-group col-2 col-md-4">
                        <button type="submit" class="btn waves-effect waves-light btn-info btn-color-topbar"
                                style="margin-right: -20px;"><i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>

            </div>
            <div class="col-xs-12  col-md-2">

                <div class="form-group col-12 col-md-8 p-0">
                    <label>تعداد نمایش</label>
                    <select class="form-control" name="cView" onchange="countView()">
                        <option @if(@$_GET['cView']=="10") selected @endif value="10">10
                        </option>
                        <option @if(@$_GET['cView']=="20") selected @endif value="20">20
                        </option>
                        <option @if(@$_GET['cView']=="40") selected @endif value="40">40
                        </option>
                    </select>
                </div>
            </div>
        </form>
    </div>


    <div class="limiter">
        <div class="container-table100">
            <div class="wrap-table100">
                <div class="table100">
                    <table class="table table-bordered" data-page="<?= $pageNum + 1?>">
                        <thead>
                        <tr class="table100-head topbar">
                            <th class="">
                                <input type="checkbox" id="check_All"
                                       class="filled-in chk-col-light-blue">
                                <label style="top: 20px" for="check_All"></label>
                            </th>
                            <th class="2">آواتار</th>
                            <th class="3">نام و نام خانوادگی</th>
                            <th class="4">موبایل</th>
                            <th class="5">ایمیل</th>
                            <th class="7">تاریخ آخرین ورود</th>
                            <th class="8">تاریخ ثبت نام</th>
                            <th class="9">وضعیت</th>
                            <th class="10">فعالیت ها</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($items))
                        @foreach($items as $item)
                        <tr id="item{{$item->id}}">
                            <td>
                                <input type="checkbox" name="delete"
                                       id="check_{{$item->id}}" value="{{$item->id}}"
                                       class="filled-in chk-col-light-blue checkBox">
                                <label style="top: 8px" for="check_{{$item->id}}"></label>
                            </td>
                            <td>
                                @if($item->avatar=="")
                                <img src="{{asset('admin-panel/images/users/profile.png')}}"
                                     style="width: 30px;border-radius: 100%">
                                @else
                                <img src="{{asset($item->avatar)}}" width="30" height="30"
                                     style="border-radius: 100%">
                                @endif
                            </td>
                            <td>{{$item->name.' '.$item->family}}</td>
                            <td>{{$item->mobile}}</td>
                            <td>{{$item->email}}</td>
                            <td>@if($item->created_at!=$item->updated_at){{Verta::instance($item->updated_at)}}@endif</td>
                            <td>{{Verta::instance($item->created_at)}}</td>
                            <td id="status">@if($item->status=="ACTIVE")<span
                                    class="badge badge-success ml-auto">فعال</span>@else
                                <span
                                    class="badge badge-danger ml-auto">غیر فعال</span> @endif
                            </td>
                            <td class="text-nowrap">
                                <a href="/admin/admins/permissions/{{$item->id}}" data-toggle="tooltip" data-original-title="دسترسی ها"> <i class="fa fa-key text-inverse"></i> </a>
                                <a href="{{route('admins.edit',$item->id)}}" target="_blank"
                                   data-toggle="tooltip" data-original-title="ویرایش"> <i
                                        class="fa fa-pencil text-inverse"></i> </a>
                                <a onclick="restore('{{$item->id}}','{{$table}}','{{ csrf_token() }}')"
                                   class="m-r-5" data-toggle="tooltip"
                                   data-original-title="بازگردانی"> <i
                                        class="fa fa-history"></i> </a>
                                <a onclick="delete_solo_item(this,'{{$item->id}}','{{$table}}','{{ csrf_token() }}','forceDelete')"
                                   data-toggle="tooltip" data-original-title="حذف"> <i
                                        class="fa fa-trash-o text-danger"></i> </a>
                            </td>
                        </tr>
                        @endforeach
                        @else
                            <tr>
                                <td colspan="9">
                                    کاربر حذف شده ای وجود ندارد
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
