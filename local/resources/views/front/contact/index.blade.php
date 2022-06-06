@extends('front.layout.app')
@section('style-map')
    <link rel="stylesheet" href="{{asset('map/css/all.css')}}"/>
    <link rel="stylesheet" href="{{asset('map/css/leaflet.css')}}" />
    <link rel="stylesheet" href="{{asset('map/css/leaflet.draw.css')}}"/>
    <link rel="stylesheet" href="{{asset('map/css/leaflet-routing-machine.css')}}" />
    <link rel="stylesheet" href="{{asset('map/css/style.css')}}" />

@endsection

@section('style-link')
    <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css')}}">
    <link rel="stylesheet" href="{{asset('admin-panel/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/fancybox/jquery.fancybox.min.css')}}">
@endsection
@section('style')
    <style>


        @keyframes blinkingText{
            0%{    background-color: #07b789;    }
            49%{   background-color: #07b789; }
            50%{   background-color: #09e5ab; }
            99%{   background-color:#09e5ab;  }
            100%{  background-color: #09e5ab;    }
        }
        #plussweb-map{
            height: 400px;
            position: relative;
            max-height: 400px!important;
        }
        .tag.badge.badge-info{
            margin: 0;
            padding-right: 0;
        }
        .bootstrap-tagsinput .tag [data-role="remove"]{
            float: right;
        }

        .upload-images{
            width: 100px;
            height: 100px;
        }
        .image-label{
            width: 100%;
            height: 100%;
            border: 1px dashed #0de0fe;
            text-align: center;
            line-height: 110px;
            cursor: pointer;
        }
        .upload-images img{
            height: 100%;
            width: 100%;
            position: absolute;
            top: 0;
            z-index: 9;
        }
        .image-input{
            visibility: hidden;
        }
        .image-label i{
            font-size: 20pt;
            color: #0de0fe;
        }
        .waitMe_content{
            margin-top: -110px!important;
        }
        .waitMe_content,.waitMe{
            height: 100%;
        }
    </style>
@endsection
@section('content')
    <div class="breadcrumb-bar">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-12 col-12">
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">خانه</a></li>
                            <li class="breadcrumb-item active" aria-current="page">تماس باما</li>
                        </ol>
                    </nav>
                    <h2 class="breadcrumb-title">تماس باما</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="container my-16" style="margin-top: 3%">
        @if(session('create-success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong> موفق! </strong>{{session('create-success')}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        @endif
        <div class="row">

            <div class="col-lg-6">

                <!--contact info-->
                <div class="card mb-16">

                    <!--card header-->
                    <div class="card-header card-header-right-border">
                        <div class="card-header_caption">
                            <div class="card-header_caption-title">
                                <div class="card-header_caption-text">اطلاعات تماس</div>
                            </div>
                        </div>
                    </div>
                    <!--card header end-->
                    <!--card body-->
                    <div class="card-body">

                        <ul class="list-unstyled m-0">
                            <li class="my-4">
                                <i class="ico ico-phone ico-flip-horizontal filter-secondary"></i>
                                <span class="font-weight-light">تلفن تماس:</span>
                                <span dir="ltr"><a class="text-dark" href="tel:{{setting()['tell']}}">{{setting()['tell']}}</a></span>
                            </li>
                            <li class="my-4">
                                <i class="ico ico-location-pin filter-secondary"></i>
                                <span class="font-weight-light">آدرس:</span>
                                <span>{{setting()['address']}}</span>
                            </li>
                            <li class="my-4">
                                <i class="ico ico-email  filter-secondary"></i>
                                <span class="font-weight-light">ایمیل:</span>
                                <span dir="ltr"><a class="text-dark" href="">{{setting()['email']}}</a></span>
                            </li>
                        </ul>

                    </div>
                    <!--card body end-->

                </div>
                <div class="card mb-16">

                    <!--card header-->
                    <div class="card-header card-header-right-border">
                        <div class="card-header_caption">
                            <div class="card-header_caption-title">
                                <div class="card-header_caption-text">آدرس از روی نقشه</div>
                            </div>
                        </div>
                    </div>
                    <!--card header end-->
                    <!--card body-->
                    <div class="card-body">
                        <div id="plussweb-map" style="overflow: hidden; position: relative;"><div class="mapp-container" data-locale="fa" style="display: block;"><div id="mapp-app" class="mapp-map leaflet-container leaflet-touch leaflet-fade-anim leaflet-grab leaflet-touch-drag leaflet-touch-zoom" tabindex="0" style="position: relative;"><div class="leaflet-pane leaflet-map-pane" style="transform: translate3d(1px, 0px, 0px);"><div class="leaflet-pane leaflet-tile-pane"><div class="leaflet-layer " style="z-index: 1; opacity: 1;"><div class="leaflet-tile-container leaflet-zoom-animated" style="z-index: 18; transform: translate3d(0px, 0px, 0px) scale(1);"><img class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(234px, -76px, 0px); opacity: 1;" src="blob:https://mersiz.com/de780a0d-0069-4758-b5d0-4ee8c0492f9c"><img class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(234px, 180px, 0px); opacity: 1;" src="blob:https://mersiz.com/06098fce-9052-4bd6-a6a1-d31caf2b96b1"><img class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(-22px, -76px, 0px); opacity: 1;" src="blob:https://mersiz.com/b1652e7e-4fd1-4687-b8c2-bbbb2f69ae01"><img class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(490px, -76px, 0px); opacity: 1;" src="blob:https://mersiz.com/922ee94f-0086-49e0-a2d3-fafa4a5958fe"><img class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(-22px, 180px, 0px); opacity: 1;" src="blob:https://mersiz.com/7e0feb88-722b-43af-8775-85ed9eb1c971"><img class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(490px, 180px, 0px); opacity: 1;" src="blob:https://mersiz.com/8c4c757a-d441-4134-9c28-7263fd906b03"></div></div></div><div class="leaflet-pane leaflet-shadow-pane"></div><div class="leaflet-pane leaflet-overlay-pane"></div><div class="leaflet-pane leaflet-marker-pane"><img src="https://mersiz.com/map/assets/images/marker-default-green.svg" class="leaflet-marker-icon leaflet-zoom-animated leaflet-interactive" tabindex="0" style="margin-left: -20px; margin-top: -40px; width: 40px; height: 40px; transform: translate3d(299px, 200px, 0px); z-index: 200;"></div><div class="leaflet-pane leaflet-tooltip-pane"></div><div class="leaflet-pane leaflet-popup-pane"><div class="leaflet-popup  leaflet-zoom-animated" style="opacity: 1; transform: translate3d(299px, 160px, 0px); bottom: -7px; left: 0px;"><div class="leaflet-popup-content-wrapper"><div class="leaflet-popup-content" style="width: 51px;"><div class="feature-popup " data-feature-group="features-marker" data-feature-id="reverse-geocode"><header class="popup-header" data-i18n="">اینجا کجاست؟</header><div class="popup-contents" data-i18n="">تهران، محله نیلوفر - شهید قندی، پاکستان، عباس ساوجی نیا، شرکت ساختارهای اطلاع رسانی نوین گستر</div><ul class="popup-toolbar small"><li data-i18n="[title]mapp-close" class="icon-close tooltip-bottom tooltipstered"></li><li data-i18n="[title]mapp-share" class="icon-share tooltip-bottom tooltipstered"></li><li data-i18n="[title]mapp-copy" class="icon-copy tooltip-bottom tooltipstered"></li></ul></div></div></div><div class="leaflet-popup-tip-container"><div class="leaflet-popup-tip"></div></div></div></div><div class="leaflet-proxy leaflet-zoom-animated" style="transform: translate3d(167488px, 102676px, 0px) scale(512);"></div></div><div class="leaflet-control-container"><div class="leaflet-top leaflet-left"></div><div class="leaflet-top leaflet-right"></div><div class="leaflet-bottom leaflet-left"></div><div class="leaflet-bottom leaflet-right"></div></div></div><div class="mapp-anchor top position-direct direct item-set horizontal"></div><div class="mapp-anchor top position-middle direct item-set horizontal"></div><div class="mapp-anchor top position-reverse reverse item-set horizontal"></div><div class="mapp-anchor center position-direct"></div><div class="mapp-anchor center position-middle"></div><div class="mapp-anchor center position-reverse"></div><div class="mapp-anchor bottom position-direct direct item-set horizontal"></div><div class="mapp-anchor bottom position-middle reverse item-set vertical"><a class="mapp-logo" href="http://corp.map.ir"></a></div><div class="mapp-anchor bottom position-reverse reverse item-set horizontal"></div><div class="mapp-footer"><div class="item-set vertical centered triggers right"></div><div class="item-set vertical centered triggers left"></div><div class="contents"></div></div><div class="mapp-overlay is-invisible"></div></div><div class="mapp-loader is-invisible"></div></div>
                    </div>
                    <!--card body end-->

                </div>
                <!--contact info-->

            </div>


            <div class="col-lg-6">

                <!--contact form-->
                <div class="card">

                    <!--card header-->
                    <div class="card-header card-header-right-border">
                        <div class="card-header_caption">
                            <div class="card-header_caption-title">
                                <div class="card-header_caption-text">با ما در ارتباط باشید</div>
                            </div>
                        </div>
                    </div>
                    <!--card header end-->
                    <!--card body-->
                    <div class="card-body">
                        <form class="form-row" method="post" enctype="multipart/form-data" action="/contact-store" novalidate="novalidate">
                            @csrf
                            <div class="form-group col-lg-12">
                                <label class="small text-secondary" for="CustomerName">نام و نام خانوادگی</label>
                                <div class="form-icon">
                                    <i class="ico ico-user"></i>
                                    <input type="text" class="form-control" placeholder="نام و نام خانوادگی خود را وارد نمایید" data-val="true"  id="name" maxlength="150" name="name" value="{{old('name')}}">
                                </div>
                                @error('name')
                                <span class="invalid-feedback" role="alert" style="margin: 10px 0;">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <span class="text-danger field-validation-valid" data-valmsg-for="name" data-valmsg-replace="true"></span>
                            </div>

                            <div class="form-group col-lg-6">
                                <label class="small text-secondary" for="Email">ایمیل</label>
                                <div class="form-icon">
                                    <i class="ico ico-email"></i>
                                    <input type="text" class="form-control" placeholder="example@address.com" id="Email" name="email" value="{{old('email')}}">
                                </div>
                                @error('email')
                                <span class="invalid-feedback" role="alert" style="margin: 10px 0;">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <span class="text-danger field-validation-valid" data-valmsg-for="email" data-valmsg-replace="true"></span>
                            </div>

                            <div class="form-group col-lg-6">
                                <label class="small text-secondary" for="Mobile">تلفن همراه</label>
                                <div class="form-icon">
                                    <i class="ico ico-mobile"></i>
                                    <input type="text" class="form-control" placeholder="09xxxxxxxxx" id="Mobile" name="mobile" value="{{old('mobile')}}">
                                </div>
                                <span class="text-danger field-validation-valid" data-valmsg-for="mobile" data-valmsg-replace="true"></span>
                                @error('mobile')
                                <span class="invalid-feedback" role="alert" style="margin: 10px 0;">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group col-lg-12">
                                <label class="small text-secondary" for="Message">پیام</label>
                                <textarea class="form-control" rows="5" placeholder="" id="Message" maxlength="150" name="message">{{old('message')}}</textarea>
                                <span class="text-danger field-validation-valid" data-valmsg-for="message" data-valmsg-replace="true"></span>
                                @error('message')
                                <span class="invalid-feedback" role="alert" style="margin: 10px 0;">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="d-flex align-items-center col-12">

                                <span id="fileName" class="text-muted mr-8" style="display: none"></span>
                                <button type="submit" class="btn btn-primary submit-btn">تایید و ارسال</button>

                            </div>

                        </form>
                    </div>
                    <!--card body end-->

                </div>
                <!--contact form-->

            </div>




        </div>
    </div>

    @php


              if (setting()['map']){
                $map=explode(',',setting()['map']);
                $lat=$map[0];
                $lng=$map[1];
            }else{
                $lat="32.66770276360022";
                $lng="51.668175302754435";
            }
    @endphp
@endsection

@section('script_map')
    <script src="{{asset('map/js/leaflet.js')}}"></script>
    <script src="{{asset('map/js/jquery-3.6.0.min.js')}}"></script>
    <script src="{{asset('map/js/leaflet.draw.js')}}"></script>
    <script src="{{asset('map/js/leaflet-routing-machine.js')}}"></script>
    <script>



        var map = L.map('plussweb-map', {zoomControl: false});
        var icon = L.icon({
            iconUrl: '{{asset('map/map_marker_pin_location_icon_142416.png')}}',
            iconSize: [40, 60],
            iconAnchor: [25, 72],
        });

        var marker = L.marker([{{$lat}}, {{$lng}}], {
            draggable: false,
            icon
        }).addTo(map);


        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPositionstart, showError);
        } else {
            alert('مرورگر شما از موقعیت یاب پشتیبانی نمی کند');
        }


        function showPositionstart(position) {
            map.setView([position.coords.latitude, position.coords.longitude], 18);
            // var tileUrl = 'https://tile.thunderforest.com/atlas/{z}/{x}/{y}.png?apikey=779bc115e1714327871a7fda4181a3d2';
            var tileUrl = 'https://tile.openstreetmap.org/{z}/{x}/{y}.png';
            L.tileLayer(
                tileUrl, {
                    // attribution: 'توسعه نقشه : <a target="_blank" href="https://plussweb.ir">پلاس وب</a> |',
                }).addTo(map);

        }

        function showError() {
            map.setView([{{$lat}}, {{$lng}}], 15);
            //var tileUrl = 'https://tile.thunderforest.com/atlas/{z}/{x}/{y}.png?apikey=779bc115e1714327871a7fda4181a3d2';
            var tileUrl = 'https://tile.openstreetmap.org/{z}/{x}/{y}.png';
            L.tileLayer(
                tileUrl, {
                    // attribution: 'توسعه نقشه : <a target="_blank" href="https://plussweb.ir">پلاس وب</a> |',
                }).addTo(map);

        }

        var logo = L.control({position: 'bottomleft'});
        logo.onAdd = function (map) {
            var div = L.DomUtil.create('div', 'myclass');
            div.innerHTML = "<a target='_blank' href='http://plussweb.ir/'><img id='logoplussweb' src='https://plussweb.ir/wp-content/uploads/2021/01/New-Project-3.png'/></a>";
            return div;
        }
        logo.addTo(map);


        var copyr = document.getElementsByClassName('leaflet-control-attribution');
        copyr[0].remove();
    </script>
@endsection

@section('script-link')
    <script src="{{asset(('assets/js/frotel/ostan.js'))}}"></script>
    <script src="{{asset('assets/js/frotel/city.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-tagsinput/js/bootstrap-tagsinput.js')}}"></script>
    <script src="{{asset('admin-panel/plugins/waitme/waitMe.js')}}"></script>
    <script src="{{asset('assets/plugins/fancybox/jquery.fancybox.min.js')}}"></script>
    <script src="{{asset('admin-panel/plugins/tinymce/tinymce.min.js')}}"></script>
@endsection

