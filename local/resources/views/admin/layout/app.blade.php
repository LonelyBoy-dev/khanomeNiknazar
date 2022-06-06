<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset(setting()['site_icon'])}}">
    <title>پنل مدیریتی</title>
    <!-- Bootstrap Core CSS -->
    @yield('style_map')
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
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
@yield('style_href')

    <style>
        #Action-show{
            display: none;
        }
        .header-button-group{
            text-align: left;
            direction: ltr;
            padding-left: 0;
            padding-top: 0;
        }
        .header-button-group button{
            padding-bottom: 4px;
        }
        .header-button-group button i{
            vertical-align: sub;
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
        .bootstrap-switch-container{
            margin-top: 1px;
        }
        .form-control{
            font-size: 13px;
        }
        table thead{
            z-index: 1!important;
        }
        .lobibox-notify{
            width: auto !important;
        }
    </style>
@yield('style')
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
</head>

<body class="fix-header fix-sidebar card-no-border">
<input name="PublicUrl" type="hidden" value="{{asset('')}}">
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
<div id="main-wrapper">
    <!-- ============================================================== -->
    <!-- Topbar header - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <header class="topbar">
        <nav class="navbar top-navbar navbar-expand-md navbar-light">
            <!-- ============================================================== -->
            <!-- Logo -->
            <!-- ============================================================== -->
          {{--  <div class="navbar-header">
                <a class="navbar-brand" href="/admin/dashboard">
                    <!-- Logo icon --><b>
                        <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                        <!-- Dark Logo icon -->
                        <img src="{{asset('admin-panel/images/logo-icon.png')}}" alt="homepage" class="dark-logo" />
                        <!-- Light Logo icon -->
                        <img src="{{asset('admin-panel/images/logo-light-icon.png')}}" alt="homepage" class="light-logo" />
                    </b>
                    <!--End Logo icon -->
                    <!-- Logo text --><span>
                         <!-- dark Logo text -->
                         <img src="{{asset('admin-panel/images/logo-text.png')}}" alt="homepage" class="dark-logo" />
                        <!-- Light Logo text -->
                         <img src="{{asset('admin-panel/images/logo-light-text.png')}}" class="light-logo" alt="homepage" /></span> </a>
            </div>--}}
            <!-- ============================================================== -->
            <!-- End Logo -->{{--dd--}}
            <!-- ============================================================== -->
            <div class="navbar-collapse">
                <!-- ============================================================== -->
                <!-- toggle and nav items -->
                <!-- ============================================================== -->
                <ul class="navbar-nav mr-auto mt-md-0">
                    <!-- This is  -->
                    <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
                    <li class="nav-item m-l-10"> <a class="nav-link sidebartoggler hidden-sm-down text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                    <!-- ============================================================== -->
                    <!-- Comment -->
                    <!-- ============================================================== -->
                    <li class="nav-item dropdown">
                        @if(admin()->can('tickets'))
                        @php $tickets=\App\Models\Ticket::where(['status'=>'waiting'])->orderby('updated_at','asc')->get(); @endphp
                        @else
                            @php $tickets=[]; @endphp
                        @endif

                        @if(admin()->can('walletList'))
                        @php $wallets=\App\Models\DepositRequest::where('status','Waiting')->orderby('updated_at','asc')->get(); @endphp
                        @else
                        @php $wallets=[]; @endphp
                        @endif
                            @if (setting()['Payment_membership']=="ACTIVE")
                            @if(admin()->can('Payment_membership_Report'))

                                <?php
                                    $membership_Report_count=0;
                                    $membership_Report=[];
                                        $membership_Report_users=App\Models\User::where(['HairStylist'=>"YES"])->where('membership_status','!=','NOK')->orderBy('id', 'desc')->get();

                                         foreach ($membership_Report_users as $membership_Report_user){
                                                $current_date = \Carbon\Carbon::now();
                                                $dt = \Carbon\Carbon::parse($membership_Report_user->membership_dateBuy);
                                                $dt2 = $dt->diffInDays($current_date);
                                                $membership_day = $membership_Report_user->membership_day - $dt2;

                                                if ($membership_day<=3){
                                                    $membership_Report[]=$membership_Report_user->id;
                                                }
                                            }

                                ?>
                            @else
                                @php $membership_Report=[]; @endphp
                            @endif

                            @else
                                @php $membership_Report=[]; @endphp
                            @endif
                        <a class="nav-link dropdown-toggle text-muted text-muted waves-effect waves-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-message"></i>
                            <div class="notify">
                                @if(count($tickets) or count($wallets) or count($membership_Report))
                                <span class="heartbit"></span>
                                <span class="point"></span>
                                    @endif
                            </div>
                        </a>
                        <div class="dropdown-menu mailbox dropdown-menu-right animated slideInUp">
                            <ul>
                                <li>
                                    <div class="drop-title">نوتیفیکیشن</div>
                                </li>
                                <li>
                                    <div class="message-center">
                                        <!-- Message -->

                                        @if(count($tickets))
                                        <a href="/admin/tickets">
                                            <div class="btn btn-danger btn-circle"><i class="fa fa-link"></i></div>
                                            <div class="mail-contnet">
                                                <h5>تیکت ها</h5> <span class="mail-desc"><span style="font-size: 13px;" class="badge badge-danger ml-auto">{{count($tickets)}}</span> تیکت جدید برای شما ارسال شد</span> <span class="time">{{Verta::instance($tickets[0]->updated_at)}}</span> </div>
                                        </a>
                                            @endif

                                        @if(count($wallets))
                                        <a href="/admin/wallets">
                                            <div class="btn btn-danger btn-circle"><i class="fa fa-link"></i></div>
                                            <div class="mail-contnet">
                                                <h5>کیف پول</h5> <span class="mail-desc"><span style="font-size: 13px;" class="badge badge-danger ml-auto">{{count($wallets)}}</span> درخواست جدید برای شما ارسال شده</span> <span class="time">{{Verta::instance($wallets[0]->updated_at)}}</span> </div>
                                        </a>
                                            @endif
                                        @if(count($membership_Report))
                                        <a href="/admin/wallets">
                                            <div class="btn btn-danger btn-circle"><i class="fa fa-link"></i></div>
                                            <div class="mail-contnet">
                                                <h5>انقضاء اشتراک</h5> <span class="mail-desc"><span style="font-size: 13px;" class="badge badge-danger ml-auto">{{count($membership_Report)}}</span> نفر در انقضاء اشتراک</span> <span class="time"></span> </div>
                                        </a>
                                            @endif

                                    </div>
                                </li>

                            </ul>
                        </div>
                    </li>
                    <!-- ============================================================== -->
                    <!-- End Comment -->

                </ul>
                <!-- ============================================================== -->
                <!-- User profile and search -->
                <!-- ============================================================== -->
                <ul class="navbar-nav my-lg-0">

                    <!-- ============================================================== -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="flag-icon flag-icon-us"></i></a>
                        <div class="dropdown-menu animated slideInUp"> <a class="dropdown-item" href="#"><i class="flag-icon flag-icon-in"></i> India</a> <a class="dropdown-item" href="#"><i class="flag-icon flag-icon-fr"></i> French</a> <a class="dropdown-item" href="#"><i class="flag-icon flag-icon-cn"></i> China</a> <a class="dropdown-item" href="#"><i class="flag-icon flag-icon-de"></i> Dutch</a> </div>
                    </li>
                    <!-- ============================================================== -->
                    <!-- Profile -->
                    <!-- ============================================================== -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">@if(Admin()->avatar=="")<img src="{{asset('admin-panel/images/users/profile.png')}}" alt="profile-user"  class="profile-pic"/>@else<img width="30" height="30" src="{{asset(Admin()->avatar)}}" alt="profile-user"  class="profile-pic"/> @endif</a>
                        <div class="dropdown-menu animated slideInUp">
                            <ul class="dropdown-user">
                                <li>
                                    <div class="dw-user-box">
                                        <div class="u-img">@if(Admin()->avatar=="")<img src="{{asset('assets/profile.png')}}" alt="profile-user" />@else<img width="70" height="70" src="{{asset(Admin()->avatar)}}" alt="profile-user" /> @endif</div>
                                        <div class="u-text">
                                            <h4>{{Admin()->name.' '.Admin()->lastname}}</h4>
                                            <p class="text-muted">{{Admin()->email}}</p><a href="/admin/profile" class="btn btn-rounded btn-danger btn-sm btn-color-topbar">نمایش پروفایل</a></div>
                                    </div>
                                </li>
                                <li role="separator" class="divider"></li>
                                <li><a href="/admin/profile"><i class="ti-user"></i>پروفایل من</a></li>
                                @if(admin()->can('setting'))
                                <li><a href="/admin/settings"><i class="ti-settings"></i>تنضیمات</a></li>
                                @endif
                                <li role="separator" class="divider"></li>
                                <li><a href="/admin/logout"><i class="fa fa-power-off"></i> خروج</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- ============================================================== -->
    <!-- End Topbar header -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Left Sidebar - style you can find in sidebar.scss  -->
    <!-- ============================================================== -->
    <aside class="left-sidebar">
        <!-- Sidebar scroll-->
        <div class="scroll-sidebar">
            <!-- User profile -->
            <div class="user-profile">
                <!-- User profile image -->
                <div class="profile-img"> @if(Admin()->avatar=="")<img src="{{asset('admin-panel/images/users/profile.png')}}" alt="profile-user" />@else<img width="70" height="70" src="{{asset(Admin()->avatar)}}" alt="profile-user" /> @endif
                    <!-- this is blinking heartbit-->
                    <div class="notify setpos"> <span class="heartbit"></span> <span class="point"></span> </div>
                </div>
                <!-- User profile text-->
                <div class="profile-text">
                    <h5 class="m-b-10">{{Admin()->name.' '.Admin()->lastname}}</h5>
                    <a href="" class="dropdown-toggle u-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"><i class="mdi mdi-settings"></i></a>
                    <a href="/admin/logout" class="" data-toggle="tooltip" title="خروج"><i class="mdi mdi-power"></i></a>
                    <div class="dropdown-menu animated flipInY">
                        <!-- text-->
                        <a href="/admin/profile" class="dropdown-item"><i class="ti-user"></i>پروفایل من</a>
                        @if(admin()->can('setting'))
                        <a href="/admin/settings" class="dropdown-item"><i class="ti-settings"></i>تنضیمات</a>
                        @endif
                        <!-- text-->
                        <div class="dropdown-divider"></div>
                        <!-- text-->
                        <a href="/admin/logout" class="dropdown-item"><i class="fa fa-power-off"></i>خروج</a>
                        <!-- text-->
                    </div>
                </div>
            </div>
            <!-- End User profile text-->
            <!-- Sidebar navigation-->
            <nav class="sidebar-nav">
                <ul id="sidebarnav">
                    <li class="nav-devider"></li>
                    <li> <a class="has-arrow waves-effect waves-dark" aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">پیشخوان </span></a>
                        <ul aria-expanded="false" class="collapse">
                            <li><a href="/admin/dashboard">داشبورد </a></li>
                           {{-- @if(admin()->can('online_chat'))
                            <li><a href="index2.html">پنل چت آنلاین</a></li>
                            @endif--}}
                        </ul>
                    </li>
                                        @if(admin()->can('posts'))
                                        <li @if(@$Active_list=="posts")class="active" @endif> <a class="has-arrow waves-effect waves-dark" aria-expanded="false"><i class="mdi mdi-book-open-page-variant"></i><span class="hide-menu">مدیریت مطالب</span></a>
                                            <ul aria-expanded="false" class="collapse">
                                                @if(admin()->can('posts_index'))<li><a @if(@$Active=="posts")class="active" @endif href="/admin/posts">لیست مطالب</a></li>@endif
                                                @if(admin()->can('posts_category'))<li><a @if(@$Active=="postsCategories")class="active" @endif href="/admin/posts/categories">دسته بندی مطالب</a></li>@endif
                                           {{--     @if(admin()->can('posts_comments'))<li><a @if(@$Active=="postsComments")class="active" @endif href="/admin/posts/comments">مدیریت نظرات</a></li>@endif--}}
                                            </ul>
                                        </li>
                                        @endif
{{--                    @if(admin()->can('posts'))--}}
{{--                    <li @if(@$Active_list=="posts")class="active" @endif> <a class="has-arrow waves-effect waves-dark" aria-expanded="false"><i class="mdi mdi-book-open-page-variant"></i><span class="hide-menu">مدیریت مطالب</span></a>--}}
{{--                        <ul aria-expanded="false" class="collapse">--}}
{{--                            @if(admin()->can('posts_index'))<li><a @if(@$Active=="posts")class="active" @endif href="/admin/posts">لیست مطالب</a></li>@endif--}}
{{--                            @if(admin()->can('posts_category'))<li><a @if(@$Active=="postsCategories")class="active" @endif href="/admin/posts/categories">دسته بندی مطالب</a></li>@endif--}}
{{--                            @if(admin()->can('posts_comments'))<li><a @if(@$Active=="postsComments")class="active" @endif href="/admin/posts/comments">مدیریت نظرات</a></li>@endif--}}
{{--                        </ul>--}}
{{--                    </li>--}}
{{--                    @endif--}}


{{--                    @if(admin()->can('pages'))--}}
{{--                    <li @if(@$Active_list=="pages")class="active" @endif> <a class="has-arrow waves-effect waves-dark" aria-expanded="false"><i class="mdi mdi-book-open-page-variant"></i><span class="hide-menu">صفحات</span></a>--}}
{{--                        <ul aria-expanded="false" class="collapse">--}}
{{--                            @if(admin()->can('about')) <li><a @if(@$Active=="about")class="active" @endif href="/admin/about">درباره ما</a></li>@endif--}}
{{--                            @if(admin()->can('guide')) <li><a @if(@$Active=="guide")class="active" @endif href="/admin/guide">راهنمای سایت</a></li>@endif--}}
{{--                            @if(admin()->can('privacy')) <li><a  @if(@$Active=="privacy")class="active" @endif href="/admin/privacy">حریم خصوصی</a></li>@endif--}}
{{--                        </ul>--}}
{{--                    </li>--}}
{{--                    @endif--}}


{{--                    @if(admin()->can('wallet'))--}}
{{--                        <li @if(@$Active_list=="deposit_requests")class="active" @endif> <a class="has-arrow waves-effect waves-dark" aria-expanded="false"><i class="mdi mdi-wallet"></i><span class="hide-menu">کیف پول</span>@if(count($wallets))<span style="font-size: 13px;float: left;margin-top: 5px;" class="badge badge-danger ml-auto">{{count($wallets)}}</span>@endif</a>--}}
{{--                            <ul aria-expanded="false" class="collapse">--}}
{{--                                @if(admin()->can('walletList')) <li><a class=" @if(@$Active=="deposit_requests")active @endif" href="/admin/wallets"> لیست درخواست ها</a></li>@endif--}}
{{--                                @if(admin()->can('walletReport')) <li><a class=" @if(@$Active=="deposit_report")active @endif" href="/admin/wallets/reports"> گزارش واریزی ها</a></li>@endif--}}
{{--                            </ul>--}}
{{--                        </li>--}}
{{--                        --}}{{--  <li @if(@$Active=="HairStylist")class="active" @endif> <a class="waves-effect waves-dark @if(@$Active=="HairStylist")active @endif" href="/admin/HairStylist" aria-expanded="false"><i class="mdi mdi-content-cut"></i><span class="hide-menu">لیست آرایشگرها</span></a></li>--}}
{{--                    @endcan--}}

{{--                        @if(admin()->can('Payment_membership'))--}}
{{--                        <li @if(@$Active_list=="Payment_membership")class="active" @endif> <a class="has-arrow waves-effect waves-dark" aria-expanded="false"><i class="mdi mdi-account-key"></i><span class="hide-menu"> حق عضویت</span>@if(count($membership_Report))<span style="font-size: 13px;float: left;margin-top: 5px;" class="badge badge-danger ml-auto">{{count($membership_Report)}}</span>@endif</a>--}}
{{--                            <ul aria-expanded="false" class="collapse">--}}
{{--                                @if(admin()->can('Payment_membership_package')) <li><a class=" @if(@$Active=="Payment_membership_package")active @endif" href="/admin/membership-package">پکیج ها</a></li>@endif--}}
{{--                                @if(admin()->can('Payment_membership_Report')) <li><a class=" @if(@$Active=="Payment_membership_Report")active @endif" href="/admin/membership-Report"> لیست درحال انقضاء اشتراک ها</a></li>@endif--}}
{{--                            </ul>--}}
{{--                        </li>--}}
{{--                        --}}{{--  <li @if(@$Active=="HairStylist")class="active" @endif> <a class="waves-effect waves-dark @if(@$Active=="HairStylist")active @endif" href="/admin/HairStylist" aria-expanded="false"><i class="mdi mdi-content-cut"></i><span class="hide-menu">لیست آرایشگرها</span></a></li>--}}
{{--                        @endcan--}}
{{--                    @if(admin()->can('users'))--}}
{{--                    <li @if(@$Active=="users")class="active" @endif> <a class="waves-effect waves-dark @if(@$Active=="users")active @endif" href="/admin/users" aria-expanded="false"><i class="mdi mdi-account-multiple"></i><span class="hide-menu">کاربران</span></a></li>--}}
{{--                    @endcan--}}

                    @if(admin()->can('fileManager'))
                    <li> <a class="waves-effect waves-dark" href="/admin/FileManager" target="_blank" aria-expanded="false"><i class="mdi mdi-file-cloud"></i><span class="hide-menu">مدیریت فایل ها</span></a></li>
                    @endcan

                    @if(admin()->can('contact_us'))
                    <li @if(@$Active=="contact")class="active" @endif> <a class="waves-effect waves-dark @if(@$Active=="contact")active @endif" href="/admin/contact" aria-expanded="false"><i class="mdi mdi-account-card-details"></i><span class="hide-menu">لیست تماس باما</span></a></li>
                    @endcan

{{--                    @if(admin()->can('tickets'))--}}
{{--                    <li @if(@$Active=="tickets")class="active" @endif> <a class="waves-effect waves-dark @if(@$Active=="tickets")active @endif" href="/admin/tickets" aria-expanded="false"><i class="mdi mdi-ticket-account"></i><span class="hide-menu">تیکت ها</span>@if(count($tickets))<span style="font-size: 13px;float: left;margin-top: 5px;" class="badge badge-danger ml-auto">{{count($tickets)}}</span>@endif</a></li>--}}
{{--                    @endcan--}}

{{--                    @if(admin()->can('trashed'))--}}
{{--                    <li @if(@$Active=="trashed")class="active" @endif> <a class="waves-effect waves-dark @if(@$Active=="trashed")active @endif" href="/admin/trashed" aria-expanded="false"><i class="mdi mdi-delete"></i><span class="hide-menu">سطل زباله</span></a></li>--}}
{{--                    @endcan--}}
{{--                    @if(admin()->can('tools'))--}}
{{--                        <li @if(@$Active_list=="tools")class="active" @endif> <a class="has-arrow waves-effect waves-dark" aria-expanded="false"><i class="mdi mdi-wrench"></i><span class="hide-menu">ابزارها</span></a>--}}
{{--                            <ul aria-expanded="false" class="collapse">--}}
{{--                                @if(admin()->can('menus'))<li><a @if(@$Active=="menus")class="active" @endif href="/admin/menus">مدیریت منوها</a></li> @endif--}}
{{--                                --}}{{--  @if(admin()->can('news')) <li><a @if(@$Active=="news")class="active" @endif href="/admin/news">مدیریت خبرها</a></li>@endif--}}
{{--                                @if(admin()->can('admins')) <li><a @if(@$Active=="admins")class="active" @endif href="/admin/admins">مدیریت مدیران</a></li>@endif--}}
{{--                                @if(admin()->can('sliders')) <li><a href="/admin/sliders">مدیریت اسلایدر</a></li>@endif--}}

{{--                                --}}{{-- @if(admin()->can('spacial'))  <li><a href="form-validation.html">مدیریت محصولات ویژه</a></li>@endif--}}
{{--                                 @if(admin()->can('banners'))  <li><a @if(@$Active=="banners")class="active" @endif href="/admin/banners">مدیریت بنر</a></li>@endif--}}
{{--                                 @if(admin()->can('brands')) <li><a @if(@$Active=="brands")class="active" @endif  href="/admin/brands">مدیریت برندها</a></li>@endif--}}
{{--                                 @if(admin()->can('discount_code')) <li><a @if(@$Active=="discountCode")class="active" @endif href="/admin/discountCode">مدیریت کد تخفیف</a></li>@endif--}}
{{--                                 @if(admin()->can('messages')) <li><a href="/admin/messages">مدیریت پیغام ها</a></li>@endif--}}
{{--                                @if(admin()->can('features_hairstylist')) <li><a @if(@$Active=="features_hairstylists")class="active" @endif href="/admin/features-hairstylist">امکانات موجود در آرایشگاها</a></li>@endif--}}
{{--                                @if(admin()->can('specialties_hairstylists')) <li><a @if(@$Active=="specialties_hairstylists")class="active" @endif href="/admin/specialties-hairstylist">تخصص ها</a></li>@endif--}}
{{--                            </ul>--}}
{{--                        </li>--}}
{{--                    @endif--}}
                    @if(admin()->can('settings'))
                    <li @if(@$Active=="settings")class="active" @endif> <a class="waves-effect waves-dark @if(@$Active=="settings")active @endif" href="/admin/settings" aria-expanded="false"><i class="mdi mdi-settings"></i><span class="hide-menu"> تنضیمات</span></a></li>
                    @endcan
{{--
                    @if(admin()->can('products') or admin()->can('orders'))
                    <li class="nav-small-cap">فروشگاه</li>
                    @endcan

                   @if(admin()->can('products'))
                    <li> <a class="has-arrow waves-effect waves-dark" aria-expanded="false"><i class="mdi mdi-cart-outline"></i><span class="hide-menu">مدیریت محصولات</span></a>
                        <ul aria-expanded="false" class="collapse">
                            @if(admin()->can('products_index')) <li><a href="/admin/products">لیست محصولات</a></li> @endcan
                            @if(admin()->can('product_category')) <li><a href="/admin/categories">دسته بندی محصولات</a></li> @endcan
                            @if(admin()->can('product_filters')) <li><a href="/admin/attribute">مدیریت فیلترها</a></li> @endcan
                            @if(admin()->can('product_comments')) <li><a href="/admin/comment-product">مدیریت نظرات</a></li> @endcan
                        </ul>
                    </li>
                    @endcan

                    @if(admin()->can('orders'))
                    <li> <a class="waves-effect waves-dark" href="/admin/orders-product" aria-expanded="false"><i class="mdi mdi-basket"></i><span class="hide-menu">مدیریت سفارشات</span></a></li>
                    @endcan--}}
                </ul>
            </nav>
            <!-- End Sidebar navigation -->
        </div>
        <!-- End Sidebar scroll-->
    </aside>
    <!-- ============================================================== -->
    <!-- End Left Sidebar - style you can find in sidebar.scss  -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Page wrapper  -->
    <!-- ============================================================== -->
    <div class="page-wrapper">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">{{$title}}</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    @if(@$back_link)<li class="breadcrumb-item active"><a href="/admin/{{@$back_link}}"><h3><i class="mdi mdi-arrow-left"></i></h3></a></li>@endif

                </ol>
            </div>
            <div>
                <button class="right-side-toggle waves-effect waves-light btn-inverse btn btn-circle btn-sm pull-right m-l-10"><i class="ti-settings text-white"></i></button>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">
        @yield('content')
            <div class="right-sidebar">
                <div class="slimscrollright">
                    <div class="rpanel-title topbar"> تنضیمات پنل <span><i class="ti-close right-side-toggle"></i></span> </div>
                    <div class="r-panel-body">
                        <ul id="themecolors" class="m-t-20">
                            <li><b>نوار کناری روشن</b></li>
                            <li><a href="javascript:void(0)" data-theme="default" class="default-theme">1</a></li>
                            <li><a href="javascript:void(0)" data-theme="green" class="green-theme">2</a></li>
                            <li><a href="javascript:void(0)" data-theme="red" class="red-theme">3</a></li>
                            <li><a href="javascript:void(0)" data-theme="blue" class="blue-theme working">4</a></li>
                            <li><a href="javascript:void(0)" data-theme="purple" class="purple-theme">5</a></li>
                            <li><a href="javascript:void(0)" data-theme="megna" class="megna-theme">6</a></li>
                            <li class="d-block m-t-30"><b>نوار کناری تاریک</b></li>
                            <li><a href="javascript:void(0)" data-theme="default-dark" class="default-dark-theme">7</a></li>
                            <li><a href="javascript:void(0)" data-theme="green-dark" class="green-dark-theme">8</a></li>
                            <li><a href="javascript:void(0)" data-theme="red-dark" class="red-dark-theme">9</a></li>
                            <li><a href="javascript:void(0)" data-theme="blue-dark" class="blue-dark-theme">10</a></li>
                            <li><a href="javascript:void(0)" data-theme="purple-dark" class="purple-dark-theme">11</a></li>
                            <li><a href="javascript:void(0)" data-theme="megna-dark" class="megna-dark-theme ">12</a></li>
                        </ul>
                        <input id="asset_admin_style" type="hidden" value="{{asset('admin-panel')}}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Page wrapper  -->
    <!-- ============================================================== -->

    @php
        $URL_SITE=url()->current();
        $URL_SITE=explode('/',$URL_SITE);
        @endphp

</div>
<!-- ============================================================== -->
<!-- End Wrapper -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- All Jquery -->
@yield('script_map')
<!-- ============================================================== -->
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
<script src="{{asset('admin-panel/plugins/sparkline/jquery.sparkline.min.js')}}"></script>
<script src="{{asset('admin-panel/plugins/dropify/dist/js/dropify.min.js')}}"></script>
<script src="{{asset('admin-panel/plugins/toast-master/js/jquery.toast.js')}}"></script>
<!--morris JavaScript -->
<script src="{{asset('admin-panel/plugins/raphael/raphael-min.js')}}"></script>
<script src="{{asset('admin-panel/plugins/morrisjs/morris.min.js')}}"></script>
<!-- Chart JS -->
<script src="{{asset('admin-panel/js/dashboard1.js')}}"></script>

<!-- ============================================================== -->
<!-- Style switcher -->
<!-- ============================================================== -->
<script src="{{asset('admin-panel/plugins/styleswitcher/jQuery.style.switcher.js')}}"></script>

<script src="{{asset('admin-panel/js/script.js')}}"></script>
@yield('script_src')

<script>
    loadScreen();
</script>
<script>
    @if(session('store-success'))
    Lobibox.notify('success', {
        size: 'mini',
        showClass: 'Lobibox-custom-class hide-close-icon',
        iconSource: "fontAwesome",
        delay:3000,
        soundPath: '{{asset('admin-panel/sounds/sounds/')}}',
        position: 'left top', //or 'center bottom'
        msg: '{{session('store-success')}}',
    });
        @endif
</script>
<script>
    @if(@$_GET['search'])
    paginate('{{@$_GET['search']}}','{{@$_GET['page']}}')
    @endif


    function countView() {
        paginate('{{@$_GET['search']}}','{{@$_GET['page']}}');
        var url=['{{@$URL_SITE[3]}}','{{@$URL_SITE[4]}}','{{@$URL_SITE[5]}}']
        count_View('{{@$_GET['cView']}}','{{@$_GET['page']}}','{{@$_GET['search']}}',url);
    }
</script>
@yield('script')
</body>

</html>
@php session()->forget('store-success') @endphp
