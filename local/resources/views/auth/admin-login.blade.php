<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('admin-panel/images/favicon.png')}}">
    <title>صفحه ورود به ادمین</title>
    <!-- Bootstrap Core CSS -->
    <link href="{{asset('admin-panel/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{asset('admin-panel/css/style.css')}}" rel="stylesheet">
    <!-- You can change the theme colors from here -->
    <link href="{{asset('admin-panel/css/colors/blue.css')}}" id="theme" rel="stylesheet">
    <link href="{{asset('admin-panel/plugins/toast-master/css/jquery.toast.css')}}" rel="stylesheet">
    <link href="{{asset('admin-panel/scss/icons/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{asset('admin-panel/css/lobibox.min.css')}}" rel="stylesheet"/>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<style>
    .invalid-feedback{
        display: block;
    }
    .Lobibox-custom-class{
        width: auto !important;
    }
    .hide-close-icon .lobibox-close{
        display: none;
    }
    .Lobibox-custom-class-confirm{
        text-align: right;
        color: #555;
    }
    .Lobibox-custom-class-confirm .btn-close{
        float: left!important;
    }
    table.dataTable tbody tr {
        background-color: #242526;
    }
    input{
        text-align: left;
        padding-left: 10px !important;
    }
    input::placeholder{
        text-align:right;
    }
</style>

<body>
<!-- ============================================================== -->
<!-- Preloader - style you can find in spinners.css -->
<!-- ============================================================== -->
<div class="preloader">
    <svg class="circular" viewBox="25 25 50 50">
        <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
</div>
<!-- ============================================================== -->
<!-- Main wrapper - style you can find in pages.scss -->
<!-- ============================================================== -->
<section id="wrapper" class="login-register" style="background-image:url({{asset('admin-panel/images/background/login-register.jpg')}});">
    <div class="login-box card" style="top: 0;">
        <div class="card-body">
                <form method="POST" class="form-horizontal form-material" id="loginform" action="{{ route('admin.login.submit') }}">
                    @csrf
                    <h3 class="box-title m-b-20 text-center">ورود به پنل</h3>
                   {{-- <a href="javascript:void(0)" class="text-center db">
                        <img src="{{asset(setting()['logo'])}}" style="width: 100%" alt="Home" />
                        <br/>
                    </a>--}}
                <div class="form-group m-t-40">

                    <div class="col-xs-12">
                        <input class="form-control" name="email" value="{{old('email')}}" type="text" required="" placeholder="ایمیل">
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12">
                        <input class="form-control" type="password" name="password" required="" placeholder="رمز ورود">
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <div class="checkbox checkbox-primary pull-left p-t-0" style="float: right;">
                            <input id="checkbox-signup" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label for="checkbox-signup"> مرا به خاطر بسپار </label>
                        </div>
<!--                        <a href="javascript:void(0)" id="to-recover" class="text-dark pull-right"><i class="fa fa-lock m-r-5"></i> Forgot pwd?</a>-->
                    </div>
                </div>
                <div class="form-group text-center m-t-20">
                    <div class="col-xs-12">
                        <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">ورود</button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</section>
<!-- ============================================================== -->
<!-- End Wrapper -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- All Jquery -->
<!-- ============================================================== -->
<script src="{{asset('admin-panel/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="{{asset('admin-panel/plugins/bootstrap/js/popper.min.js')}}"></script>
<script src="{{asset('admin-panel/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="{{asset('admin-panel/js/jquery.slimscroll.js')}}"></script>
<!--Wave Effects -->
<script src="{{asset('admin-panel/js/waves.js')}}"></script>
<!--Menu sidebar -->
<script src="{{asset('admin-panel/js/sidebarmenu.js')}}"></script>
<!--stickey kit -->
<script src="{{asset('admin-panel/plugins/sticky-kit-master/dist/sticky-kit.min.js')}}"></script>
<script src="{{asset('admin-panel/plugins/sparkline/jquery.sparkline.min.js')}}"></script>
<script src="{{asset('admin-panel//plugins/toast-master/js/jquery.toast.js')}}"></script>
<!--Custom JavaScript -->
<script src="{{asset('admin-panel/js/lobibox.min.js')}}"></script>
<script src="{{asset('admin-panel/js/custom.min.js')}}"></script>
<!-- ============================================================== -->
<!-- Style switcher -->
<!-- ============================================================== -->
<script>
    @if(session('admin_login_error'))

    Lobibox.notify('error', {
        size: 'mini',
        showClass: 'Lobibox-custom-class hide-close-icon',
        iconSource: "fontAwesome",
        delay:3000,
        soundPath: '{{asset('admin-panel/sounds/sounds/')}}',
        position: 'left top', //or 'center bottom'
        msg: '{{session('admin_login_error')}}',
    });

    @endif
</script>
</body>

</html>
@php session()->forget('admin_login_error') @endphp
