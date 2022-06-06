@extends('admin.layout.app')
@section('style')
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
                content: "کیف پول";
            }

            .limiter table tbody tr td:nth-child(7):before {
                content: "تاریخ آخرین ورود";
            }

            .limiter table tbody tr td:nth-child(8):before {
                content: "تاریخ ثبت نام";
            }

            .limiter table tbody tr td:nth-child(9):before {
                content: "وضعیت";
            }

            .limiter table tbody tr td:nth-child(10):before {
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
                    <a href="/admin/users/create" type="button" class="btn btn-primary"><i class="mdi mdi-plus"></i> افزودن جدید</a>
                    {{--<button type="button" class="btn btn-info" data-toggle="modal" data-target="#responsive-modal"><i class="mdi mdi-email"></i> ارسال پیام </button>--}}
                    <a href="/admin/users/report" type="button" class="btn btn-success  "><i class="mdi mdi-content-save"></i> خروجی اکسل </a>
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
                                    <option value="changeStatusTrue">فعال کردن</option>
                                    <option value="changeStatusFalse">غیر فعال کردن</option>
                                    <option value="delete">حذف</option>
                                </select>
                            </div>
                            <div class="form-group col-2 col-md-4">
                                <button onclick="delete_all_items('{{$table}}','{{ csrf_token() }}')" style="margin-right: -20px" type="button" class="btn waves-effect waves-light btn-info btn-color-topbar">انجام</button>
                            </div>
                        </div>
                        <form style="display: contents;" method="get" action="{{route('users.index')}}">
                        <div class="col-xs-12  col-md-4">
                            <div class="row">
                                <label class="w-100 p-r-20">جستجو</label>
                                <div class="form-group col-10 col-md-8">
                                    <input type="text" name="search" value="{{@$_GET['search']}}" placeholder="نام،شماره موبایل،وضعیت را وارد کنید" class="form-control form-control-line">
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
                                            <th class="2">آواتار</th>
                                            <th class="3">نام و نام خانوادگی</th>
                                            <th class="4">موبایل</th>
                                            <th class="5">ایمیل</th>
                                            <th class="6">کیف پول</th>
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
                                                <td class="1">
                                                    <input type="checkbox" name="delete" id="check_{{$item->id}}" value="{{$item->id}}" class="filled-in chk-col-light-blue checkBox">
                                                    <label style="top: 8px" for="check_{{$item->id}}"></label>
                                                </td>
                                                <td class="2">
                                                    @if($item->avatar=="")
                                                        <img src="{{asset('assets/profile.png')}}" style="width: 30px;border-radius: 100%">
                                                    @else
                                                        <img src="{{asset($item->avatar)}}" width="30" height="30" style="border-radius: 100%">
                                                    @endif
                                                </td>
                                                <td class="3">{{$item->name.' '.$item->family}}</td>
                                                <td class="4">{{$item->mobile}}</td>
                                                <td class="4">{{$item->email}}</td>
                                                <td class="4">{{number_format($item->wallet)}} تومان </td>
                                                <td class="5">@if($item->created_at!=$item->updated_at){{Verta::instance($item->updated_at)}}@endif</td>
                                                <td class="5">{{Verta::instance($item->created_at)}}</td>
                                                <td id="status">@if($item->status=="ACTIVE")<span class="badge badge-success ml-auto">فعال</span>@else<span class="badge badge-danger ml-auto">غیر فعال</span> @endif</td>
                                                <td class="text-nowrap">
                                                    <a href="{{route('users.edit',$item->id)}}" data-toggle="tooltip" data-original-title="ویرایش" aria-describedby="tooltip488585"> <i class="fa fa-pencil text-inverse m-r-10"></i> </a>
                                                    <a onclick="delete_solo_item(this,'{{$item->id}}','{{$table}}','{{ csrf_token() }}')" data-toggle="tooltip" data-original-title="حذف"> <i class="fa fa-trash-o text-danger"></i> </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @else
                                            <tr>
                                                <td colspan="10">
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

    <div id="responsive-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">ارسال پیام</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form>
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="message-type" class="control-label">نوع ارسال:</label>
                            <select id="message-type" class="form-control" name="message-type">
                                <option>انتخاب کنید</option>
                                <option value="sms">پیامک</option>
                                <option value="email">ایمیل</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="control-label">متن پیام:</label>
                            <textarea class="form-control" name="message" id="message-text"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">لغو</button>
                        <button id="send-message-user" type="button" class="btn btn-info">ارسال پیام</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
