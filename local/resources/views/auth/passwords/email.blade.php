@extends('front.layout.app')
@section('style')
    <style>
        .invalid-feedback strong{
            margin: 2px 0 8px;
            float: right;
        }
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
                                    @if(session('error'))
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            {{session('error')}}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                    @endif
                                    <div class="login-header">
                                        <h3> فراموشی رمز عبور؟</h3>
                                        <p class="small text-muted">شماره موبایل خود را وارد کنید تا مجدد رمز عبور را دریافت کنید</p>
                                    </div>

                                    <!-- Forgot Password Form -->
                                    <form method="POST" action="/password/remember">
                                        @csrf
                                        <div class="form-group form-focus">
                                            <input type="number" class="form-control floating @error('mobile') is-invalid @enderror" name="mobile" value="{{ old('mobile') }}" required autocomplete="email" autofocus>
                                            <label class="focus-label">شماره موبایل</label>
                                            @error('mobile')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                        <div class="text-right">
                                            <a class="forgot-link" href="/login">رمز عبور خود را به خاطر می آورید؟</a>
                                        </div>
                                        <button class="btn btn-primary btn-block btn-lg login-btn" type="submit">بازیابی رمزعبور</button>
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
