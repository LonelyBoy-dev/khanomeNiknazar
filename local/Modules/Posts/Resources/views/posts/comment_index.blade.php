@extends('admin.layout.app')
@section('style')
    <style>

        .address-nowrap {
            width: 80px;
            position: absolute;
            top: 5px;
            left: 0;
            z-index: 1;
        }


        .address-nowrap button {
            padding: 3px 7px 0;
            border: 1px solid rgba(120, 130, 140, 0.13);
            box-shadow: none;
        }

        .address-nowrap button {
            padding: 3px 7px 0;
            border: 1px solid rgba(120, 130, 140, 0.13);
            box-shadow: none;
        }

        .address-nowrap button:first-child {
            padding: 3px 8px 0 9px;
        }

        .comment-click-status {
            text-align: left;
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
                                    <option value="SEEN">تایید شده</option>
                                    <option value="UNSEEN">تایید نشده</option>
                                    <option value="delete">حذف</option>
                                </select>
                            </div>
                            <div class="form-group col-2 col-md-4">
                                <button onclick="delete_all_items('{{$table}}','{{ csrf_token() }}','forceDelete')"
                                        style="margin-right: -20px" type="button"
                                        class="btn waves-effect waves-light btn-info  btn-color-topbar">انجام
                                </button>
                            </div>
                        </div>
                        <form style="display: contents;" method="get" action="/admin/posts/comments">
                            <div class="col-xs-12  col-md-4">
                                <div class="row">
                                    <label class="w-100 p-r-20">جستجو</label>
                                    <div class="form-group col-10 col-md-8">
                                        <input type="text" name="search" value="{{@$_GET['search']}}"
                                               placeholder="نام،ایمیل را وارد کنید" class="form-control form-control-line">
                                    </div>
                                    <div class="form-group col-2 col-md-4">
                                        <button type="submit" class="btn waves-effect waves-light btn-info btn-color-topbar"
                                                style="margin-right: -20px"><i class="fa fa-search"></i></button>
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

                    @if(count($items))
                    <div class="row">
                        <div class="card" style="width: 100%">
                            <div class="row" style="padding-right: 35px;padding-bottom: 7px;">
                                    <input type="checkbox" id="check_All" class="filled-in chk-col-light-blue">
                                    <label style="top: 11px" for="check_All">انتخاب همه</label>
                            </div>

                        </div>
                    </div>


                    <div class="row">
                        <table data-page="<?= $pageNum + 1?>"></table>
                        @foreach($items as $item)
                            @php $answe=\App\Models\Comment::where('parent',$item->id)->first() @endphp
                            <div class="col-md-6 col-lg-6 col-xlg-4" id="item{{$item->id}}">
                                <div class="card card-body" style="border: 1px solid #ebf2f6;">
                                    <div class="address-nowrap w-100" style="text-align: left">
                                        <div class="comment-status " style="display: inline-block">
                                            @if($item->status=="SEEN")
                                                <span
                                                    class="c-profile-comments__status c-profile-comments__status--approved m-l-5">تایید شده</span>
                                            @elseif($item->status=="Waiting")
                                                <span
                                                    class="c-profile-comments__status c-profile-comments__status--Waiting m-l-5">درانتظار تائید</span>
                                            @elseif($item->status=="UNSEEN")
                                                <span
                                                    class="c-profile-comments__status c-profile-comments__status--rejected m-l-5">تایید نشده</span>
                                            @endif
                                        </div>

                                        <!--                                                    <button type="button" class="btn btn-secondary font-18"><i class="mdi mdi-pencil"></i></button>-->
                                        <button type="button" class="btn btn-secondary font-18 m-l-5"
                                                onclick="delete_solo_item(this,'{{$item->id}}','{{$table}}','{{ csrf_token() }}','forceDelete')">
                                            <i class="mdi mdi-delete"></i></button>
                                        <div style="float: right;margin-top: -9px;margin-right: 4px;">
                                            <input type="checkbox" name="delete" id="check_{{$item->id}}"
                                                   value="{{$item->id}}" class="filled-in chk-col-light-blue checkBox">
                                            <label style="top: 8px" for="check_{{$item->id}}"></label>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h3 class="box-title m-b-0">{{$item->name.' '.$item->family}}</h3>
                                            <h6 class="mt-2 mb-2" style="font-weight: 700"> مطلب :<small><a href="/posts/{{@$item->post->slug}}"> {{@$item->post->title}} </a></small></h6>
                                            <address>
                                                <h6 class="mt-2 mb-2" style="font-weight: 700"> عنوان نظر: <span
                                                        style="font-weight: 400">{{$item->title}}</span></h6>
                                                <h6 class="mt-0" style="font-weight: 700"> متن نظر: <span
                                                        style="font-weight: 400">{{$item->comment}}</span></h6>
                                                @if(@$answe!="")<h6 class="mt-2 your-answer-comment"
                                                                    style="font-weight: 700"> پاسخ شما: <span
                                                        style="font-weight: 400">{{$answe->comment}}</span></h6>@endif
                                            </address>
                                            <div class="w-100 comment-click-status" style="text-align: left">
                                                <span type="button" class="btn btn-secondary font-18 answer-comment-btn"
                                                      data-toggle="modal" data-target="#responsive-modal" title="پاسخ"
                                                      data-id="{{$item->id}}"><i
                                                        class="mdi mdi-comment-text"></i></span>
                                                <button type="button" class="btn btn-secondary font-18"
                                                        title="عدم تائید" data-val="UNSEEN" data-id="{{$item->id}}"><i
                                                        class="mdi mdi-thumb-down"></i></button>
                                                <button type="button" class="btn btn-secondary font-18" title="تائید"
                                                        data-val="SEEN" data-id="{{$item->id}}"><i
                                                        class="mdi mdi-thumb-up"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    {{$items->links("pagination::bootstrap-4")}}
                    @else
                        <div class="row">
                            <div class="card" style="width: 100%">
                                <div class="row" style="text-align: center;display: block;padding: 10px;">
                                    نظری وجود ندارد
                                </div>

                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div id="responsive-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">ارسال پاسخ به <span></span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">

                    <address>
                        <h6 class="mt-2 mb-2 " style="font-weight: 700"> عنوان نظر: <span class="comment-title"
                                                                                          style="font-weight: 400"></span>
                        </h6>
                        <h6 class="mt-0" style="font-weight: 700"> متن نظر: <span class="comment-content"
                                                                                  style="font-weight: 400"></span></h6>
                    </address>
                    <div class="form-group">
                        <label for="message-text" class="control-label"> پاسخ شما:</label>
                        <textarea class="form-control comment-answer" rows="3" name="comment"
                                  id="message-text"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">لغو</button>
                    <button id="send-message-user" onclick="store_answer_comment(this,'{{ csrf_token() }}')"
                            type="button" data-id="" class="btn btn-info store_answer_comment">ثبت پاسخ
                    </button>
                </div>

            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
        $('.comment-click-status button').click(function () {
            var item = this;
            var status = $(item).attr('data-val');
            var id = $(item).attr('data-id');
            var CSRF_TOKEN = '{{ csrf_token() }}';
            change_status_comment(item, id, status, CSRF_TOKEN)
        });

        $('.answer-comment-btn').click(function () {
            var item = this;
            var id = $(item).attr('data-id');
            get_comment(id, item, '{{ csrf_token() }}');

        })
    </script>
@endsection
