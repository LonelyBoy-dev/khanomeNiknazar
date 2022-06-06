@extends('front.layout.app')
@section('style')
    <style>
        .invalid-feedback strong{
            width: 100%;
            margin: 10px 0;
            float: right;
            text-align: center;
        }
        #code{
            direction: ltr;
            border: unset;
            text-align: center;
            font-size: 40pt;
            border-bottom: 2px solid #dcdcdc;
            width: 271px;
            margin: 0 auto;
        }
    </style>
@endsection
@section('content')
    <!-- Page Content -->
    <div class="main-wrapper" style="    background: #fff;">
        <!-- Page Content -->
        <div class="content" style="padding: 50px 0;">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8 offset-md-2">

                        <!-- Account Content -->
                        <div class="account-content">
                            <div class="row align-items-center justify-content-center">
                                <div class="col-md-7 col-lg-6 login-left">
                                    <img  src="{{asset('assets/images/hello-76473187d0.png')}}" class="img-fluid" alt="reset pass">
                                </div>
                                <div class="col-md-12 col-lg-6 login-right">
                                    <div class="login-header">
                                        <h3>تائید شماره موبایل</h3>
                                        <p class="small text-muted"> کد پنج رقمی به شماره {{session('UserInfo')['mobile']}} ارسال شد</p>
                                    </div>

                                    <!-- Forgot Password Form -->
                                    <form method="POST" action="/register/ConfirmMobile/checkCode">
                                        @csrf
                                        <div class="form-group form-focus">
                                            <input id="code" type="text" onkeyup="toEnglishNumber(this.value,'code')" class="form-control floating @error('code') is-invalid @enderror" name="code" value="{{ old('code') }}" required  autocomplete="off" autofocus>
                                            @if(session('checkCode'))
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ session('checkCode') }}</strong>
                                    </span>
                                            @endif
                                        </div>
                                        <button class="btn btn-primary btn-block btn-lg login-btn" type="submit">تائید</button>
                                    </form>
                                    <!-- /Forgot Password Form -->

                                </div>
                            </div>
                        </div>
                        <!-- /Account Content -->

                    </div>
                </div>

            </div>

        </div>
        <!-- /Page Content -->
    </div>



@endsection
@section('script-link')
    <script src="{{asset('assets/js/jquery.maskedinput.min.js')}}"></script>
@endsection

@section('script')
    <script>
        $('#code').mask('9 9 9 9 9');
        document.getElementById("code").focus();
    </script>
@endsection
@php session()->forget('checkCode'); @endphp
