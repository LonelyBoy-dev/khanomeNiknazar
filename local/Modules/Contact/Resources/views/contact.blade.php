@extends('admin.layout.app')
@section('style')
    <style>
        @media screen and (max-width: 992px) {
            .limiter table tbody tr td:nth-child(1):before {
                content: "انتخاب";
            }
            .limiter table tbody tr td:nth-child(2):before {
                content: " نام";
            }

            .limiter table tbody tr td:nth-child(3):before {
                content: "موبایل";
            }
            .limiter table tbody tr td:nth-child(4):before {
                content: "ایمیل";
            }
            .limiter table tbody tr td:nth-child(5):before {
                content: "متن پیام";
            }

            .limiter table tbody tr td:nth-child(6):before {
                content: "تاریخ ایجاد";
            }

            .limiter table tbody tr td:nth-child(7):before {
                content: "فعالیت ها";
            }
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">

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
                                <button onclick="delete_all_items('{{$table}}','{{ csrf_token() }}','forceDelete')" style="margin-right: -20px" type="button" class="btn waves-effect waves-light btn-info btn-color-topbar">انجام</button>
                            </div>
                        </div>
                        <form style="display: contents;" method="get" action="/admin/contact">
                        <div class="col-xs-12  col-md-4">
                            <div class="row">
                                <label class="w-100 p-r-20">جستجو</label>
                                <div class="form-group col-10 col-md-8">
                                    <input type="text" name="search" value="{{@$_GET['search']}}" placeholder="ایمیل را وارد کنید" class="form-control form-control-line">
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
                                            <th>نام</th>
                                            <th>شماره موبایل</th>
                                            <th> ایمیل</th>
                                            <th> متن پیام</th>
                                            <th>تاریخ ایجاد</th>
                                            <th>فعالیت ها</th>
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
                                                <td data-toggle="modal" data-target="#responsive-modal" onclick="getContact('{{$item->id}}','{{$table}}','{{ csrf_token() }}')">{{$item->name.' '.$item->family}}</td>
                                                <td>{{$item->mobile}}</td>
                                                <td>{{$item->email}}</td>
                                                <td data-toggle="modal" data-target="#responsive-modal" onclick="getContact('{{$item->id}}','{{$table}}','{{ csrf_token() }}')">{{substr($item->message,0,50).'...'}} <a style="display: inline-block;">مشاهده پیام</a></td>
                                                <td class="5">{{Verta::instance($item->created_at)}}</td>
                                                <td class="text-nowrap">
                                                    <a  data-original-title="مشاهده" onclick="getContact('{{$item->id}}','{{$table}}','{{ csrf_token() }}')" data-toggle="modal" data-target="#responsive-modal" > <i class="mdi mdi-eye"></i> </a>
                                                    <a onclick="delete_solo_item(this,'{{$item->id}}','{{$table}}','{{ csrf_token() }}','forceDelete')" data-toggle="tooltip" data-original-title="حذف"> <i class="fa fa-trash-o text-danger"></i> </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7">
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


    <div id="responsive-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">مشاهده پیام <span id="name"></span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>

                    <div class="modal-body">

                        <div class="form-group m-b-10">
                            <label class="control-label">متن پیام</label>
                            <span id="message" style="display: block;"></span>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">بستن</button>
                    </div>



            </div>
        </div>
    </div>
@endsection
@section('script')

@endsection
