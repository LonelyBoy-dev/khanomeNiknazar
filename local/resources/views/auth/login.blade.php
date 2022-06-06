@extends('front.layout.app')

@section('style')
    <style>
        input{
            text-align: left;
            padding-left: 10px !important;
        }
        input::placeholder{
            text-align:right;
        }
    </style>
@endsection
@section('content')
    <div class="main-wrapper" style="    background: #fff;">
        <!-- Page Content -->
        <div class="content" style="padding: 50px 0;">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-md-8 offset-md-2">

                        <!-- Login  محتوای تب -->
                        <div class="account-content">
                            <div class="row align-items-center justify-content-center">
                                <div class="col-md-7 col-lg-6 login-left">
                                    <img src="{{asset('assets/images/hello-76473187d0.png')}}" class="img-fluid" alt="Login">
                                </div>
                                <div class="col-md-12 col-lg-6 login-right">
                                    @if(session('error'))
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                             {{session('error')}}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                    @endif
                                    <div class="login-header">
                                        <h3> ورود به <span>اکانت</span></h3>
                                    </div>
                                    @error('email')
                                    <span class="invalid-feedback" role="alert" style="margin: 10px 0;">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    @error('mobile')
                                    <span class="invalid-feedback" role="alert" style="margin: 10px 0;">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                        @error('password')
                                        <span class="invalid-feedback" role="alert" style="margin: 10px 0;">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror

                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf
                                        <div class="form-group form-focus">
                                            <input type="text" id="username" name="username" class="form-control floating @error('username') is-invalid @enderror" value="{{ old('username') }}" required autocomplete="email" placeholder="ایمیل یا شماره موبایل را وارد کنید" autofocus onkeyup="toEnglishNumber(this.value,'username');">
                                            <label class="focus-label">شماره موبایل/ایمیل</label>
                                        </div>
                                        <div class="form-group form-focus">
                                            <input type="password" id="password" class="form-control floating @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="رمز ورود را وارد کنید" onkeyup="toEnglishNumber(this.value,'password');">
                                            <label class="focus-label">رمزعبور</label>

                                        </div>

                                        <div class="form-group form-focus">
                                            <div class="col-md-6">
                                                <label class="custom_check">
                                                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                    <span class="checkmark"></span> من را به خاطر بسپار
                                                </label>
                                            </div>


                                        </div>


                                        <div class="text-right">
                                           <a class="forgot-link" href="/password/reset">فراموشی رمزعبور</a>
                                        </div>
                                        <button class="btn btn-primary btn-block btn-lg login-btn" type="submit">‌ورود</button>
                                        <div class="login-or">
                                            <span class="or-line"></span>
                                            <span class="span-or">یا</span>
                                        </div>
                                        <!--                                    <div class="row form-row social-login">
                                                                                <div class="col-6">
                                                                                    <a href="#" class="btn btn-facebook btn-block"><i class="fab fa-facebook-f mr-1"></i>ورود</a>
                                                                                </div>
                                                                                <div class="col-6">
                                                                                    <a href="#" class="btn btn-google btn-block"><i class="fab fa-google mr-1"></i>ورود</a>
                                                                                </div>
                                                                            </div>-->
                                        <div class="text-center dont-have">اکانت ندارید؟<a href="/register">‌ثبت‌نام</a></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- /Login  محتوای تب -->

                    </div>
                </div>

            </div>

        </div>
        <!-- /Page Content -->
    </div>


@endsection
@php
    session()->forget('error');
    session()->forget('ConfirmMobileResetPassMobile');
    session()->forget('UserHairStylistInfo');
 @endphp

