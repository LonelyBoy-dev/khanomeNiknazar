
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{setting()['title']}}</title>
    <meta name="author" content="">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="">
    <meta name="description" content="{{setting()['seo_content']}}">
    <meta name="keywords" content="{{setting()['seo_title']}}">
    <link href="{{asset('admin-panel/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- morris CSS -->
    <link href="{{asset('admin-panel/plugins/morrisjs/morris.css')}}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{asset('admin-panel/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('admin-panel/scss/icons/themify-icons/themify-icons.css')}}" rel="stylesheet">
    <link href="{{asset('admin-panel/scss/icons/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{asset('admin-panel/scss/icons/material-design-iconic-font/css/materialdesignicons.min.css')}}" rel="stylesheet">
    <!-- You can change the theme colors from here -->
    <link href="{{asset('admin-panel/css/colors/blue.css')}}" id="theme" rel="stylesheet">
    <link href="{{asset('admin-panel/css/Table.css')}}" rel="stylesheet">

    <link href="{{asset('admin-panel/plugins/toast-master/css/jquery.toast.css')}}" rel="stylesheet">
    <link href="{{asset('admin-panel/plugins/dropify/dist/css/dropify.min.css')}}" rel="stylesheet">
    <link href="{{asset('admin-panel/plugins/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet">
    <link href="{{asset('admin-panel/css/lobibox.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('admin-panel/css/rtl-style.css')}}" rel="stylesheet"/>
    <link href="{{asset('admin-panel/plugins/icheck/skins/all.css')}}" rel="stylesheet">
    <style>
        .icheckbox_line-red .icheck_line-icon, .iradio_line-red .icheck_line-icon{
            background: url({{asset('admin-panel/plugins/icheck/skins/line/line.png')}});
        }
        html,body{
            background: #272c33;
        }
    </style>
    @yield('style')
</head>
<body>
<div class="container-fluid">
    @yield('content')
</div>


</body>

<script src="{{asset('admin-panel/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="{{asset('admin-panel/plugins/bootstrap/js/popper.min.js')}}"></script>
<script src="{{asset('admin-panel/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{asset('admin-panel/plugins/bootstrap-select/bootstrap-select.min.js')}}"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="{{asset('admin-panel/js/jquery.slimscroll.js')}}"></script>
<!--Wave Effects -->
<script src="{{asset('admin-panel/js/waves.js')}}"></script>
<!--Menu sidebar -->
<script src="{{asset('admin-panel/js/sidebarmenu.js')}}"></script>
<!--stickey kit -->
<script src="{{asset('admin-panel/plugins/sticky-kit-master/dist/sticky-kit.min.js')}}"></script>
<!--Custom JavaScript -->
<script src="{{asset('admin-panel/js/custom.min.js')}}"></script>
<script src="{{asset('admin-panel/js/lobibox.min.js')}}"></script>
<!-- ============================================================== -->
<!-- This page plugins -->
<!-- ============================================================== -->
<!--sparkline JavaScript -->
<script src="{{asset('admin-panel/plugins/icheck/icheck.min.js')}}"></script>
<script src="{{asset('admin-panel/plugins/icheck/icheck.init.js')}}"></script>
<script src="{{asset('admin-panel/plugins/sparkline/jquery.sparkline.min.js')}}"></script>
<script src="{{asset('admin-panel/plugins/dropify/dist/js/dropify.min.js')}}"></script>
<script src="{{asset('admin-panel/plugins/toast-master/js/jquery.toast.js')}}"></script>
<!--morris JavaScript -->
<script src="{{asset('admin-panel/plugins/raphael/raphael-min.js')}}"></script>
<script src="{{asset('admin-panel/plugins/morrisjs/morris.min.js')}}"></script>
<!-- Chart JS -->
<script src="{{asset('admin-panel/js/dashboard1.js')}}"></script>
<script src="{{asset('admin-panel/js/validation.js')}}"></script>
<!-- ============================================================== -->
<!-- Style switcher -->
<!-- ============================================================== -->
<script src="{{asset('admin-panel/plugins/styleswitcher/jQuery.style.switcher.js')}}"></script>

<script src="{{asset('admin-panel/js/script.js')}}"></script>
@yield('script')
<script>
    @if(session('store-success'))
    Lobibox.notify('success', {
        size: 'mini',
        showClass: 'Lobibox-custom-class hide-close-icon',
        iconSource: "fontAwesome",
        delay:5000,
        soundPath: '{{asset('admin-panel/sounds/sounds/')}}',
        position: 'left top', //or 'center bottom'
        msg: '{{session('store-success')}}',
    });
    @endif
</script>
</html>
