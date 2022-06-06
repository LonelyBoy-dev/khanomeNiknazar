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
                                        <h3>  رمز عبور جدید</h3>
                                        <p class="small text-muted">رمز عبور جدید خود را وارد کنید</p>                                    </div>

                                    <!-- Forgot Password Form -->
                                        <form method="POST" action="/password/remember/NewPass/store">
                                            @csrf
                                            <div class="form-group form-focus">
                                                <input type="password" class="form-control floating @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                                <label class="focus-label"> رمزعبور جدید</label>

                                                @error('password')
                                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                                @enderror
                                            </div>

                                            <div class="form-group form-focus">
                                                <input type="password" class="form-control floating " name="password_confirmation" required autocomplete="new-password">
                                                <label class="focus-label">تکرار رمزعبور جدید</label>

                                            </div>

                                            <button class="btn btn-primary btn-block btn-lg login-btn" type="submit">تغییر رمزعبور</button>
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
