@extends('admin.layout.app')

@section('style_href')
    <link href="{{asset('admin-panel/plugins/bootstrap-switch/bootstrap-switch.min.css')}}" rel="stylesheet">
    <link href="{{asset('admin-panel/plugins/waitme/waitMe.css')}}" rel="stylesheet">
    <link href="{{asset('packages/barryvdh/elfinder/css/colorbox.css')}}" rel="stylesheet">
@endsection
@section('style')
    <style>
        .hr-span{
            position: relative;
            margin: 40px 0 40px;
        }
        .hr-span span{
            position: absolute;
            top: -14px;
            right: 14px;
            border: 1px dashed #61c579;
            padding: 3px 9px;
            background: #fff;
            border-radius: 5px;
            font-size: 12px;

        }

        .addresses li{
            position: relative;
        }
        .addresses [type="radio"] + label:before,.addresses [type="radio"] + label:after{
            opacity: 0;
        }
        .addresses .address-nowrap{
            width: 80px;
            position: absolute;
            top: 5px;
            left: 0;
            z-index: 1;
        }
        .addresses .address-nowrap button{
            padding: 3px 7px 0;
            border: 1px solid rgba(120, 130, 140, 0.13);
            box-shadow: none;
        }
        .addresses .address-nowrap button:first-child{
            padding: 3px 8px 0 9px;
        }

        .comment-click-status{
            text-align: left;
        }
        .comment-click-status button{
            padding: 3px 7px 0;background: #fbeaea;
        }
        .comment-click-status button:last-child{
            background: #edf7ed;
        }
        .comment-click-status button:last-child:hover{
            background:#5a6268;
        }

    </style>
    <style>
        .popup-selector-image-box{
            border-radius: 100%;
            margin: 0 auto;
            width: 150px;
            height: 150px;
            cursor: pointer;
            overflow: hidden;
        }
        .popup-selector-image-box p{
            padding-top: 27px;
        }
        .img-after-upload {
            position: absolute;
            right: auto;
            top: 0px;
            width: 100%;
            height: 100%;
        }
    </style>
@endsection
@section('content')
    <!-- Column -->
    <div class="row">
        <form class="row" action="{{route('admins.update',$user->id)}}" method="post" autocomplete="off" enctype="multipart/form-data">
            @csrf
            {{ @method_field('PATCH') }}
            <div class="col-lg-4 col-xlg-3 col-md-5">
                <div class="card">
                    <div class="card-body profile-body p-b-0">
                        <div class="form-group">
                            <input type="hidden" id="feature_image" name="feature_image" value="@if(old('feature_image')){{old('feature_image')}}@else{{$user->avatar}}@endif">
                            <a data-inputid="feature_image" data-path="{{asset('')}}" class="popup_selector popup-selector-image-box feature_image">
                                <p><span style="display: block;font-size: 25pt" class="mdi mdi-cloud-upload"></span>
                                    <span style="margin-top: -15px;display: block;">
                                                برای آپلود تصویر کلیک کنید
                                            </span>
                                </p>
                                @if(old('feature_image') or $user->avatar)<div class="img-after-upload"><img class="card-img-top img-responsive " src="@if(old('feature_image')){{asset(old('feature_image'))}}@else{{asset($user->avatar)}}@endif" alt="Card image cap"></div> @endif
                                <div id="remove-icon">
                                    @if(old('feature_image') or $user->avatar)<span onclick="remove_image(this)" class="remove-img-after-upload"><i class="mdi mdi-close-circle"></i>حذف</span> @endif
                                </div>
                            </a>


                        </div>
                        <h4 class="card-title m-t-10">{{$user->name.' '.$user->lastname}}</h4>
                        <hr>
                        <div class="row text-center justify-content-md-center profile-footer" style="padding-bottom: 0 !important;">

                            <ul>

                                <li>
                                    <span>آدرس ایمیل :</span>
                                    <span>{{$user->email}}</span>
                                </li>
                                <li>
                                    <span>شماره موبایل :</span>
                                    <span>{{$user->mobile}}</span>
                                </li>
                                <li>
                                    <span>تاریخ ثبت نام :</span>
                                    <span>{{Verta::instance($user->created_at)}}</span>
                                </li>

                                <li style="margin-bottom: 0">
                                    <div style="text-align: right" class="form-group">
                                        <span style="margin-bottom: 10px;display: block">وضعیت کاربر :</span>
                                        <select class="form-control" name="Change_status_user"
                                                onchange="Change_status(this,'YES','{{$user->id}}','status','{{ csrf_token() }}')">
                                            <option value="ACTIVE" @if($user->status=="ACTIVE")selected @endif>فعال
                                            </option>
                                            <option value="INACTIVE" @if($user->status=="INACTIVE")selected @endif>
                                                غیرفعال
                                            </option>
                                        </select>
                                    </div>
                                </li>




                                <li id="isadmin"  align="center">

                                    <!--                                    <a href="https://mersiz.com/admin/admins/2/edit" style="DISPLAY: block;margin-top: 10px;">مدیریت دسترسی ها</a>-->
                                    <a href="/admin/admins/permissions/{{$user->id}}" type="button" class="btn btn-primary m-t-10 m-b-20">مدیریت دسترسی ها</a>

                                </li>
                            </ul>
                        </div>
                        </center>

                    </div>


                </div>
            </div>
            <!-- Column -->
            <!-- Column -->
            <div class="col-lg-8 col-xlg-9 col-md-7">
                <div class="card">

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 col-xs-6 b-r"> <strong>نام و نام خانوادگی</strong>
                                <br>
                                <p class="text-muted">{{$user->name.' '.$user->lastname}}</p>
                            </div>
                            <div class="col-md-3 col-xs-6 b-r"> <strong>موبایل</strong>
                                <br>
                                <p class="text-muted">{{$user->mobile}}</p>
                            </div>
                            <div class="col-md-3 col-xs-6 b-r"> <strong>ایمیل</strong>
                                <br>
                                <p class="text-muted">{{$user->email}}</p>
                            </div>
                            <div class="col-md-3 col-xs-6"> <strong>نقش</strong>
                                <br>
                                <p class="text-muted">مدیر</p>


                            </div>
                        </div>
                        <hr>
                        <h4 class="card-title">ویرایش اطلاعات</h4>

                        <div class="form-horizontal form-material">

                            <div class="form-group">
                                <label class="col-md-12">نام و نام خانوادگی</label>
                                <div class="col-md-12">
                                    <input type="text" name="name" value="@if(old('name')){{old('name')}}@else{{$user->name}}@endif" placeholder="نام و نام خانوادگی را وارد کنید" class="form-control form-control-line">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            {{-- <div class="form-group">
                                 <label class="col-md-12">نام خانوادگی</label>
                                 <div class="col-md-12">
                                     <input type="text" name="lastname" value="@if(old('lastname')){{old('lastname')}}@else{{$user->lastname}}@endif" placeholder="نام خانوادگی را وارد کنید" class="form-control form-control-line">
                                     @error('lastname')
                                     <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                                     @enderror
                                 </div>
                             </div>--}}
                            <div class="form-group">
                                <label for="example-email" class="col-md-12">شماره موبایل</label>
                                <div class="col-md-12">
                                    <input type="number" value="@if(old('mobile')){{old('mobile')}}@else{{$user->mobile}}@endif" placeholder="شماره موبایل را وارد کنید" class="form-control form-control-line" name="mobile">
                                    @error('mobile')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="example-email" class="col-md-12">ایمیل</label>
                                <div class="col-md-12">
                                    <input type="email" value="@if(old('email')){{old('email')}}@else{{$user->email}}@endif" placeholder="ایمیل را وارد کنید" class="form-control form-control-line" name="email" id="example-email">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            {{--  <div class="form-group">
                                  <label for="example-email" class="col-md-12">جنسیت</label>
                                  <div class="col-md-12">
                                      <div class="form-line" style="margin-top: 10px;">
                                          <input @if(old('sex'))@if(old('sex')=="M")checked @endif @elseif($user->sex=="M") checked @endif name="sex" value="M" class="radio-col-blue" type="radio" id="radio_1" >
                                          <label for="radio_1">آقا</label>
                                          <input @if(old('sex'))@if(old('sex')=="F")checked @endif @elseif($user->sex=="F") checked @endif name="sex" value="F" class="radio-col-blue" type="radio" id="radio_2">
                                          <label for="radio_2">خانم</label>
                                      </div>
                                  </div>
                              </div>--}}

                            <div class="form-group">
                                <label class="col-md-12">بیوگرافی</label>
                                <div class="col-md-12">
                                    <textarea class="form-control" name="Biography" rows="5">@if(old('Biography')){{old('Biography')}}@else{{$user->Biography}}@endif</textarea>
                                </div>
                            </div>
                            <div class="hr-span">
                                <hr>
                                <span>در صورت تغییر، پسورد را وارد کنید</span>
                            </div>


                            <div class="form-group">
                                <label class="col-md-12">رمز ورود</label>
                                <div class="col-md-12">
                                    <input type="password" value="{{old('password')}}" name="password" placeholder="در صورت تغییر رمز ورود را وارد کنید" class="form-control form-control-line" readonly
                                           onfocus="this.removeAttribute('readonly');">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-12">تکرار رمز ورود</label>
                                <div class="col-md-12">
                                    <input type="password" value="{{old('password_confirmation')}}" name="password_confirmation" placeholder="در صورت تغییر تکرار رمز ورود را وارد کنید" class="form-control form-control-line" readonly
                                           onfocus="this.removeAttribute('readonly');">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button style="float: left" class="btn btn-success">ویرایش </button>
                                </div>
                            </div>
                        </div>

                    </div>


                </div>
            </div>
            <!-- Column -->
        </form>
    </div>


@endsection

@section('script_src')
    <script src="{{asset('admin-panel/plugins/bootstrap-switch/bootstrap-switch.min.js')}}"></script>
    <script src="{{asset('admin-panel/plugins/waitme/waitMe.js')}}"></script>
    <script type="text/javascript" src="{{asset('packages/barryvdh/elfinder/js/jquery.colorbox-min.js')}}"></script>
    <script type="text/javascript" src="{{asset('packages/barryvdh/elfinder/js/standalonepopup.min.js')}}"></script>
@endsection
@section('script')
    <script>
        $(".bt-switch input[type='checkbox'], .bt-switch input[type='radio']").bootstrapSwitch();
        var radioswitch = function() {
            var bt = function() {
                $(".radio-switch").on("switch-change", function() {
                    $(".radio-switch").bootstrapSwitch("toggleRadioState")
                }), $(".radio-switch").on("switch-change", function() {
                    $(".radio-switch").bootstrapSwitch("toggleRadioStateAllowUncheck")
                }), $(".radio-switch").on("switch-change", function() {
                    $(".radio-switch").bootstrapSwitch("toggleRadioStateAllowUncheck", !1)
                })
            };
            return {
                init: function() {
                    bt()
                }
            }
        }();
        $(document).ready(function() {
            radioswitch.init()
        });
    </script>


    <script>
        $('#active_user').on('change', function () {
            var status = "INACTIVE";
            if ($(this).is(':checked')) {
                status = "ACTIVE";
            }
            Change_status_user('YES',status,'{{$user->id}}','{{ csrf_token() }}')
        });

        $('.SelectAddress').click(function () {
            SelectAddress(this,'{{ csrf_token() }}')
        });

        $('.comment-click-status button').click(function () {
            comment_click_status(this,'{{ csrf_token() }}')
        });

        function upload_Image_User() {
            uploadImageUser('{{$user->id}}','admin','{{ csrf_token() }}')
        }
    </script>


@endsection
