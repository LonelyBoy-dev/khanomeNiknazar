@extends('front.layout.app')
@section('style-map')
    <link rel="stylesheet" href="{{asset('map/css/all.css')}}"/>
    <link rel="stylesheet" href="{{asset('map/css/leaflet.css')}}" />
    <link rel="stylesheet" href="{{asset('map/css/leaflet.draw.css')}}"/>
    <link rel="stylesheet" href="{{asset('map/css/leaflet-routing-machine.css')}}" />
    <link rel="stylesheet" href="{{asset('map/css/style.css')}}" />

@endsection
@section('style')
    <style>
        .invalid-feedback strong{
            margin: 2px 0 8px;
            float: right;
        }
        #plussweb-map{
            height: 400px;
            position: relative;
        }
        .modal-dialog{
            max-width: 90%;
        }
        @media only screen and (max-width: 650px) {
            .modal-dialog.modal-dialog-centered{
                max-width: 95%;
                display: -webkit-box;
            }
            #plussweb-map {
                height: 100%;
            }
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
                            <div class="col-md-12 col-lg-9 login-right">
                                @if(session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        {{session('error')}}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                @endif
                                <div class="login-header">
                                    <h3>ثبت نام آرایشگر  <a href="/register">آرایشگر نیستید؟</a></h3>
                                </div>

                                <!-- Register Form -->
                                <form method="POST" action="/register/hairStylist/register" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group form-focus">
                                                <input type="text" class="form-control floating @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                                <label class="focus-label">نام و نام خانوادگی</label>
                                                @error('name')
                                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group form-focus">
                                                <input style="direction: ltr" type="number" id="NationalCode" onkeyup="toEnglishNumber(this.value,'NationalCode')" class="form-control floating @error('NationalCode') is-invalid @enderror" name="NationalCode" value="{{ old('NationalCode') }}" required autocomplete="NationalCode" autofocus>
                                                <label class="focus-label">کدملی</label>
                                                @error('NationalCode')
                                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group form-focus">
                                                <input style="direction: ltr" type="number" id="mobile" onkeyup="toEnglishNumber(this.value,'mobile')" class="form-control floating @error('mobile') is-invalid @enderror" name="mobile" value="{{ old('mobile') }}" required autocomplete="mobile" autofocus>
                                                <label class="focus-label">شماره موبایل</label>
                                                @error('mobile')
                                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group form-focus">
                                                <input style="direction: ltr" type="text" class="form-control floating @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autocomplete="email" autofocus>
                                                <label class="focus-label">ایمیل</label>
                                                @error('email')
                                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group form-focus">
                                                <input type="text" class="form-control floating @error('nameShop') is-invalid @enderror" name="nameShop" value="{{ old('nameShop') }}" required autocomplete="nameShop" autofocus>
                                                <label class="focus-label">نام مغازه (محل کار)</label>
                                                @error('nameShop')
                                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group form-focus">
                                                <input style="direction: ltr" type="number" id="tell" onkeyup="toEnglishNumber(this.value,'tell')" class="form-control floating @error('tell') is-invalid @enderror" name="tell" value="{{ old('tell') }}"  autocomplete="tell" autofocus>
                                                <label class="focus-label">شماره ثابت</label>
                                                @error('tell')
                                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group form-focus">
                                                <input style="direction: ltr" type="number" id="accountNumber" onkeyup="toEnglishNumber(this.value,'accountNumber')" class="form-control floating @error('accountNumber') is-invalid @enderror" name="accountNumber" value="{{ old('accountNumber') }}" required autocomplete="accountNumber" autofocus>
                                                <label class="focus-label">شماره حساب</label>
                                                @error('accountNumber')
                                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group form-focus">
                                                <input style="direction: ltr" type="number" id="shabaNumbershabaNumber" onkeyup="toEnglishNumber(this.value,'shabaNumbershabaNumber')" class="form-control floating @error('shabaNumber') is-invalid @enderror" name="shabaNumber" value="{{ old('shabaNumber') }}" required autocomplete="shabaNumber" autofocus>
                                                <label class="focus-label">شماره شبا (بدون IR)</label>
                                                @error('shabaNumber')
                                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 col-xs-12">
                                        <div class="form-group row">
                                            <label class="col-form-label col-md-3">انتخاب نوع آرایشگاه</label>
                                            <div class="col-md-9">
                                                <select class="form-control" name="Type_hairdresser">
                                                    <option value="">-- انتخاب--</option>
                                                    <option @if(old('Type_hairdresser')=="M")selected @endif value="M">آرایشگاه آقایان</option>
                                                    <option @if(old('Type_hairdresser')=="F")selected @endif value="F">آرایشگاه بانوان</option>
                                                </select>
                                                @error('Type_hairdresser')
                                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        </div>
                                    </div>

                                    <span class="badge badge-pill bg-warning-light">حجم تصویرها باید کمتر از 3.5 مگابایت باشد. فرمت تصویر باید jpg,png باشد</span>
                                    <div class="row">
                                        <div class="col-md-12 col-xs-12">
                                            <div class="form-group row">
                                                <label class="col-form-label col-md-4">عکس کارت ملی</label>
                                                <div class="col-md-8">
                                                    <input class="form-control @error('NationalCardPhoto') is-invalid @enderror" name="NationalCardPhoto" type="file">
                                                    @error('NationalCardPhoto')
                                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 col-xs-12">
                                            <div class="form-group row">
                                                <label class="col-form-label col-md-4">عکس گواهی مهارت از سازمان فنی و حرفه ای</label>
                                                <div class="col-md-8">
                                                    <input class="form-control @error('Businesslicense') is-invalid @enderror" name="Businesslicense" type="file">
                                                    @error('Businesslicense')
                                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 col-xs-12">
                                            <div class="form-group row">
                                                <label class="col-form-label col-md-4">سایر مدارک حرفه ای</label>
                                                <div class="col-md-8">
                                                    <input class="form-control @error('ShopPhotos') is-invalid @enderror" type="file" name="ShopPhotos">
                                                    @error('ShopPhotos')
                                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 col-xs-12">
                                            <div class="form-group form-focus">
                                                <input type="number" class="form-control floating " name="guild_id" value="{{old('guild_id')}}" autocomplete="guild_id" autofocus="">
                                                <label class="focus-label">شماره شناسه صنفی</label>
                                            </div>
                                            @error('guild_id')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group ">
                                                <label for="city" class="focus-label">انتخاب استان</label>
                                                <select id="ostan" class="form-control selectpicker" name="ostan_id" onchange="empty_ostan_city()">
                                                    <option>-- انتخاب--</option>
                                                </select>
                                                @error('ostan')
                                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                                @enderror
                                            </div>

                                        </div>

                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group ">
                                                <label for="city" class="focus-label">انتخاب شهر</label>
                                                <select id="city" class="form-control selectpicker city" name="city_id" onchange="set_state_name()">
                                                    <option>-- انتخاب--</option>
                                                </select>
                                                @error('city')
                                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                                @enderror
                                            </div>

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-9 col-xs-12">
                                            <div class="form-group form-focus">
                                                <input type="text" class="form-control floating @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}" required autocomplete="address">
                                                <label class="focus-label">آدرس دقیق محل کار </label>
                                                @error('address')
                                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                                @enderror

                                            </div>
                                        </div>

                                        <div class="col-md-3 col-xs-12">
                                            <div class="form-group form-focus">
                                                <button id="addLocation" type="button" style="padding: 12px 0!important;" class="btn btn-block btn-outline-info active @error('address') is-invalid @enderror" data-toggle="modal" data-target="#location"><i class="fas fa-map-marker-alt"></i> ثبت موقعیت </button>
                                                <input type="hidden" class="form-control floating @error('location') is-invalid @enderror" name="location" value="{{ old('location') }}"  autocomplete="location">
                                            </div>
                                            @error('location')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group form-focus">
                                                <input type="password" id="password" onkeyup="toEnglishNumber(this.value,'password')" class="form-control floating @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                                <label class="focus-label"> رمزعبور </label>

                                                @error('password')
                                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                                @enderror
                                            </div>

                                        </div>

                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group form-focus">
                                                <input type="password" id="password_confirmation" class="form-control floating " onkeyup="toEnglishNumber(this.value,'password_confirmation')" name="password_confirmation" required autocomplete="new-password">
                                                <label class="focus-label">تکرار رمزعبور </label>

                                            </div>
                                        </div>
                                    </div>


                                    <div class="text-right">
                                        <a class="forgot-link" href="/login">اکانت دارید؟</a>
                                    </div>
                                    <input type="hidden" name="ostan" value="{{old('ostan')}}">
                                    <input type="hidden" name="city" value="{{old('city')}}">
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
@section('end-content')
    <div class="modal fade" id="location" tabindex="-1" role="dialog" aria-labelledby="location" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ثبت موقعیت روی نقشه</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" >
                    <div id="plussweb-map"></div>
                    <input id="map_location" type="hidden" name="map_location" >
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">بستن</button>
                    <button class="btn btn-primary" id="set_location" type="button" data-dismiss="modal"> ثبت موقعیت</button>
                </div>
            </div>
        </div>
    </div>
    @php
        $map=explode(',',old('location'));
        if (!old('location')){
            $lat="32.66770276360022";
            $lng="51.668175302754435";
        }else{
            $lat=$map[0];
            $lng=$map[1];
        }
    @endphp
@endsection

@section('script_map')
    <script src="{{asset('map/js/leaflet.js')}}"></script>
    <script src="{{asset('map/js/jquery-3.6.0.min.js')}}"></script>
    <script src="{{asset('map/js/leaflet.draw.js')}}"></script>
    <script src="{{asset('map/js/leaflet-routing-machine.js')}}"></script>
    <script>
        $('#set_location').click(function () {
            var location=$('#map_location').val()
            $('input[name=location]').val(location)
        })
        $('#addLocation').click(function () {
            setTimeout(function(){
                var map = L.map('plussweb-map', {zoomControl: true});
                var icon = L.icon({
                    iconUrl: '{{asset('map/map_marker_pin_location_icon_142416.png')}}',
                    iconSize: [40, 60],
                    iconAnchor: [25, 72],
                });

                var marker = L.marker([{{$lat}}, {{$lng}}], {
                    draggable: true,
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


                    var newLatLng = new L.LatLng(position.coords.latitude, position.coords.longitude);
                    marker.setLatLng(newLatLng);
                    marker.on('dragend', function (e) {
                        // console.log(marker.getLatLng().lat, marker.getLatLng().lng);
                    });
                    map.on('click', function (e) {
                        alert('dd')
                        marker.setLatLng(e.latlng);
                        // console.log(marker.getLatLng().lat, marker.getLatLng().lng);
                    });
                    map.on("move", function (e) {

                        marker.setLatLng(map.getCenter());
                        let myarray = {};
                        let l = marker.getLatLng().lat + "," + marker.getLatLng().lng;

                        var x = marker.getLatLng().lat, y = marker.getLatLng().lng;

                        $('#map-id').val(l)
                        var location = marker.getLatLng().lat + ',' + marker.getLatLng().lng;
                        $('#map_location').val(location)
                    });

                    map.on('click', function (e) {
                        var location = marker.getLatLng().lat + ',' + marker.getLatLng().lng;
                        $('#map_location').val(location)
                    });
                }

                function showError() {
                    map.setView([{{$lat}}, {{$lng}}], 15);
                    //var tileUrl = 'https://tile.thunderforest.com/atlas/{z}/{x}/{y}.png?apikey=779bc115e1714327871a7fda4181a3d2';
                    var tileUrl = 'https://tile.openstreetmap.org/{z}/{x}/{y}.png';
                    L.tileLayer(
                        tileUrl, {
                            // attribution: 'توسعه نقشه : <a target="_blank" href="https://plussweb.ir">پلاس وب</a> |',
                        }).addTo(map);


                    //var newLatLng = new L.LatLng(36.28302832042549, 50.00633239746094);
                    var newLatLng = new L.LatLng({{$lat}}, {{$lng}});
                    marker.setLatLng(newLatLng);

                    marker.on('dragend', function (e) {
                        var location = marker.getLatLng().lat + ',' + marker.getLatLng().lng;
                        $('#map_location').val(location)
                    });
                    map.on('click', function (e) {
                        marker.setLatLng(e.latlng);
                        var location = marker.getLatLng().lat + ',' + marker.getLatLng().lng;
                        $('#map_location').val(location)
                        // console.log(marker.getLatLng().lat, marker.getLatLng().lng);
                    });

                }


                function getLocation() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(showPosition);
                    } else {
                        alert("دستگاه شما از Gps پشتیبانی نمی کند");
                    }
                }

                function showPosition(position) {
                    map.setView([position.coords.latitude, position.coords.longitude], 18);
                    var newLatLng = new L.LatLng(position.coords.latitude, position.coords.longitude);
                    marker.setLatLng(newLatLng);
                    chooseAddr(position.coords.latitude, position.coords.longitude)

                }


                var logo = L.control({position: 'bottomleft'});
                logo.onAdd = function (map) {
                    var div = L.DomUtil.create('div', 'myclass');
                    div.innerHTML = "<a target='_blank' href='http://plussweb.ir/'><img id='logoplussweb' src='https://plussweb.ir/wp-content/uploads/2021/01/New-Project-3.png'/></a>";
                    return div;
                }
                logo.addTo(map);


                function addr_search() {
                    var inp = document.getElementById("addr");
                    var xmlhttp = new XMLHttpRequest();
                    var url = "https://nominatim.openstreetmap.org/search?format=json&limit=3&q=" + inp.value;
                    xmlhttp.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                            var myArr = JSON.parse(this.responseText);
                            myFunction(myArr);
                        }
                    };
                    xmlhttp.open("GET", url, true);
                    xmlhttp.send();
                }

                var copyr = document.getElementsByClassName('leaflet-control-attribution');
                copyr[0].remove();


                function myFunction(arr) {
                    var out = "";
                    var i;

                    if (arr.length > 0) {
                        for (i = 0; i < arr.length; i++) {
                            out += "<div class='address' title='نمایش روی نشه' onclick='chooseAddr(" + arr[i].lat + ", " + arr[i].lon + ");return false;'>" + arr[i].display_name + "</div>";
                        }
                        document.getElementById('results').innerHTML = out;
                    } else {
                        document.getElementById('results').innerHTML = "متاسفانه نتیجه ای یافت نشد...";
                    }

                }

                function chooseAddr(lat1, lng1) {
                    marker.closePopup();
                    map.setView([lat1, lng1], 18);
                    marker.setLatLng([lat1, lng1]);
                    //  lat = lat1.toFixed(8);
                    //  lon = lng1.toFixed(8);
                    //  $('lat').val(lat);
                    //  $('lon').val(lon);
                    //  marker.bindPopup("Lat " + lat + "<br />Lon " + lon).openPopup();
                    document.getElementById('results').innerHTML = '';
                    document.getElementById('addr').value = '';
                }

            },2000);
        });
    </script>
@endsection
@section('script-link')
    <script src="{{asset(('assets/js/frotel/ostan.js'))}}"></script>
    <script src="{{asset('assets/js/frotel/city.js')}}"></script>
@endsection

@section('script')
    <script>
        loadOstan('ostan');

        $("#ostan").change(function () {
            var i = $(this).find('option:selected').val();
            ldMenu(i, 'city');
            $('.selectpicker').selectpicker('refresh');
        });

        function set_state_name() {
            var ostan_name = $('#ostan option:selected').text();
            var city_name = $('#city option:selected').text();
            $('input[name=city]').val(city_name);
            $('input[name=ostan]').val(ostan_name);
        }

        function empty_ostan_city() {
            $('input[name=city]').val('');
            $('input[name=ostan]').val('');
        }

        $('#ostan option').each(function (index) {

            var value_ostan = $(this).val();
            var state = '{{old('ostan_id')}}';
            if (value_ostan == state) {
                $(this).attr('selected', 'selected');
                ldMenu(value_ostan, 'city');

            }


        });

        $('.city option').each(function (index) {
            var city = '{{old('city_id')}}';
            var city_value = $(this).val();
            if (city_value == city) {
                $(this).attr('selected', 'selected');
                $('.selectpicker').selectpicker('refresh');
            }
        });


    </script>
@endsection
