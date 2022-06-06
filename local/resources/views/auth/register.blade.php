@extends('front.layout.app')
@section('style')
    <style>
        .invalid-feedback strong{
            margin: 2px 0 8px;
            float: right;
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

                    <!-- Register Content -->
                    <div class="account-content">
                        <div class="row align-items-center justify-content-center">
                            <div class="col-md-7 col-lg-6 login-left">
                                <img src="{{asset('assets/images/hello-76473187d0.png')}}" class="img-fluid" alt="Register">
                            </div>
                            <div class="col-md-12 col-lg-6 login-right">
                                <div class="login-header">
                                    <h3>ثبت نام  <a href="/register/hairStylist">آرایشگر هستید؟</a></h3>
                                </div>

                                <!-- Register Form -->
                                <form method="POST" action="/register/register">
                                    @csrf
                                    <div class="form-group form-focus">
                                        <input type="text" class="form-control floating @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                        <label class="focus-label">نام و نام خانوادگی</label>
                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                    <div class="form-group form-focus">
                                        <input type="number" id="mobile" onkeyup="toEnglishNumber(this.value,'mobile')" class="form-control floating @error('mobile') is-invalid @enderror" name="mobile" value="{{ old('mobile') }}" required autocomplete="mobile">
                                        <label class="focus-label">شماره موبایل </label>
                                        @error('mobile')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                    <div class="form-group form-focus">
                                        <input type="email" class="form-control floating @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autocomplete="email">
                                        <label class="focus-label">ایمیل  </label>
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                    <div class="form-group form-focus">
                                        <input type="password" class="form-control floating @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                        <label class="focus-label"> رمزعبور </label>

                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>

                                    <div class="form-group form-focus">
                                        <input type="password" class="form-control floating " name="password_confirmation" required autocomplete="new-password">
                                        <label class="focus-label">تکرار رمزعبور </label>

                                    </div>
                                    <div class="text-right">
                                        <a class="forgot-link" href="/login">اکانت دارید؟</a>
                                    </div>
                                    <button class="btn btn-primary btn-block btn-lg login-btn" type="submit">ثبت‌نام</button>
<!--                                    <div class="login-or">
                                        <span class="or-line"></span>
                                        <span class="span-or">یا</span>
                                    </div>
                                    <div class="row form-row social-login">
                                        <div class="col-6">
                                            <a href="#" class="btn btn-facebook btn-block"><i class="fab fa-facebook-f mr-1"></i>ورود</a>
                                        </div>
                                        <div class="col-6">
                                            <a href="#" class="btn btn-google btn-block"><i class="fab fa-google mr-1"></i>ورود</a>
                                        </div>
                                    </div>-->
                                </form>
                                <!-- /Register Form -->

                            </div>
                        </div>
                    </div>
                    <!-- /Register Content -->

                </div>
            </div>

        </div>

    </div>
    <!-- /Page Content -->
    </div>


@endsection
