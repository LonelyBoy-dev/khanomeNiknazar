@extends('admin.layout.app')

@section('style_href')
    <link href="{{asset('admin-panel/plugins/bootstrap-switch/bootstrap-switch.min.css')}}" rel="stylesheet">
    <link href="{{asset('admin-panel/plugins/waitme/waitMe.css')}}" rel="stylesheet">
    <link href="{{asset('packages/barryvdh/elfinder/css/colorbox.css')}}" rel="stylesheet">
@endsection
@section('style')
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
    <form class="row" action="{{route('admins.store')}}" method="post" autocomplete="off" enctype="multipart/form-data">
        @csrf
        <div class="col-lg-4 col-xlg-3 col-md-5">
            <div class="card">
                <div class="card-body profile-body">
                    <div class="form-group">
                        <input type="hidden" id="feature_image" name="feature_image" value="{{old('feature_image')}}">
                        <a data-inputid="feature_image" data-path="{{asset('')}}" class="popup_selector popup-selector-image-box feature_image">
                            <p><span style="display: block;font-size: 25pt" class="mdi mdi-cloud-upload"></span>
                                <span style="margin-top: -15px;display: block;">
                                                برای آپلود تصویر کلیک کنید
                                            </span>
                            </p>
                            @if(old('feature_image'))<div class="img-after-upload"><img class="card-img-top img-responsive " src="{{asset(old('feature_image'))}}" alt="Card image cap"></div> @endif
                            <div id="remove-icon">
                                @if(old('feature_image'))<span onclick="remove_image(this)" class="remove-img-after-upload"><i class="mdi mdi-close-circle"></i>حذف</span> @endif
                            </div>
                        </a>


                    </div>
                </div>
                <div>
                    <hr> </div>
                <div class="" style="text-align: center">

                    <div style="margin-bottom: 0">
                        <span style="margin-bottom: 10px">وضعیت کاربر : </span>
                        <div class="switch" align="center" style="margin: 15px 0">
                            <label><span style="float: left">غیر فعال</span><input id="active_user" name="status" type="checkbox" @if(old('status')=="on")checked @endif><span class="lever switch-col-green"></span><span style="float: right">فعال</span></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="col-lg-8 col-xlg-9 col-md-7">
            <div class="card">
                <div class="tab-content">
                    <div class="card-body">
                        <div class="form-horizontal form-material">

                            <div class="form-group">
                                <label class="col-md-12">نام و نام خانوادگی</label>
                                <div class="col-md-12">
                                    <input type="text" name="name" value="{{old('name')}}" placeholder="نام و نام خانوادگی را وارد کنید" class="form-control form-control-line">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            {{-- <div class="form-group">
                                 <label class="col-md-12"></label>
                                 <div class="col-md-12">
                                     <input type="text" name="lastname" value="{{old('lastname')}}" placeholder="نام خانوادگی را وارد کنید" class="form-control form-control-line">
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
                                    <input type="number" value="{{old('mobile')}}" placeholder="شماره موبایل را وارد کنید" class="form-control form-control-line" name="mobile">
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
                                    <input type="email" value="{{old('email')}}" placeholder="ایمیل را وارد کنید" class="form-control form-control-line" name="email" id="example-email">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            {{-- <div class="form-group">
                                 <label for="example-email" class="col-md-12">جنسیت</label>
                                 <div class="col-md-12">
                                     <div class="form-line" style="margin-top: 10px;">
                                         <input @if(old('sex')=="M")checked @elseif(old('sex')!="M")checked @endif name="sex" value="M" class="radio-col-blue" type="radio" id="radio_1" >
                                         <label for="radio_1">آقا</label>
                                         <input @if(old('sex')=="F")checked @endif name="sex" value="F" class="radio-col-blue" type="radio" id="radio_2">
                                         <label for="radio_2">خانم</label>
                                     </div>
                                 </div>
                             </div>--}}
                            <div class="form-group">
                                <label class="col-md-12">بیوگرافی</label>
                                <div class="col-md-12">
                                    <textarea class="form-control" name="Biography" rows="5">{{old('Biography')}}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-12">رمز ورود</label>
                                <div class="col-md-12">
                                    <input type="password" value="{{old('password')}}" name="password" placeholder="رمز ورود را وارد کنید" class="form-control form-control-line" readonly
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
                                    <input type="password" value="{{old('password_confirmation')}}" name="password_confirmation" placeholder="تکرار رمز ورود را وارد کنید" class="form-control form-control-line" readonly
                                           onfocus="this.removeAttribute('readonly');">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button style="float: left" class="btn btn-success">افزودن مدیر</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
    </form>
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


@endsection
