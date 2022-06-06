@extends('admin.layout.app')
@section('style_map')
    <!--    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css"/>-->
    <link rel="stylesheet" href="{{asset('map/css/all.css')}}"/>
    <link rel="stylesheet" href="{{asset('map/css/leaflet.css')}}"/>
    <link rel="stylesheet" href="{{asset('map/css/leaflet.draw.css')}}"/>
    <link rel="stylesheet" href="{{asset('map/css/leaflet-routing-machine.css')}}"/>
    <link rel="stylesheet" href="{{asset('map/css/style.css')}}"/>

@endsection
@section('style_href')
    <link href="{{asset('packages/barryvdh/elfinder/css/colorbox.css')}}" rel="stylesheet">
@endsection
@section('style')
    <style>


        .btn {
            position: fixed;
            left: 53px;
            z-index: 999;
            color: #fff;
            padding: 10px 30px;
            background-color: #196bbf !important;
            box-shadow: 0 0 10px #196bbf !important;
        }
    </style>
    <style>

        #plussweb-map {
            height: 400px;
            position: relative;
        }
    </style>
@endsection

@section('content')


    <div class="row">
        <div class="col-sm-12">
            <div class="card card-body">
                <form class="form-horizontal m-t-40 row" method="POST" action="{{route('settings.store')}}"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <button type="submit" style="float:left;padding: 10px 30px !important;z-index: 1009;" class="btn waves-effect">
                            بروزرسانی
                        </button>
                    </div>

                @foreach($settings as $setting)
                    @php $class="col-md-12"; @endphp
                    @if($setting->class!="")
                            @php $class=$setting->class; @endphp
                        @endif
                        @if($setting->type=="string")
                           <div class="form-group {{$class}}">
                                <label>{{$setting->title}}</label>
                                <input type="text" name="{{$setting->setting}}" class="form-control"
                                       value="{{$setting->value}}">
                            </div>
                        @endif
                        @if($setting->type=="number")
                           <div class="form-group {{$class}}">
                                <label>{{$setting->title}}</label>
                                <input type="number" name="{{$setting->setting}}" class="form-control"
                                       value="{{$setting->value}}">
                            </div>
                        @endif
                            @if($setting->type=="textarea")
                            <div class="form-group col-md-12">
                                <label>{{$setting->title}}</label>
                                <textarea name="{{$setting->setting}}" class="form-control" rows="3">{{$setting->value}}</textarea>
                            </div>
                            @endif
                            @if($setting->type=="text_editor")
                            <div class="form-group col-md-12">
                                <label>{{$setting->title}}</label>
                                <textarea name="{{$setting->setting}}" id="editor1" placeholder="توضیحات مطلب را وارد کنید"
                                          class="form-control" rows="20">{{$setting->value}}</textarea>
                            </div>
                            @endif
                            @if($setting->type=="SwitchButton")
                            <div class="form-group {{$class}}">

                            <div class="switch row" align="center">
                                <div class="col-md-3" style="text-align: right">
                                    <span style="margin-bottom: 10px">{{$setting->title}}</span>
                                </div>
                                <div class="col-md-2">
                                    <label><span style="float: left">غیر فعال</span>
                                        <input id="active_user" name="{{$setting->setting}}" value="INACTIVE" type="hidden">
                                        <input id="active_user" name="{{$setting->setting}}" value="ACTIVE" type="checkbox" @if($setting->value=="ACTIVE")checked @endif>
                                        <span class="lever switch-col-green"></span><span style="float: right">فعال</span></label>
                                </div>

                            </div>
                            </div>
                            @endif
                            @if($setting->type=="image")
                            <div class="form-group col-md-3">
                                <label for="feature_image" class="control-label m-b-10">{{$setting->title}}</label>
                                <input type="hidden" id="feature_image{{$setting->id}}" name="{{$setting->setting}}" value="@if(old($setting->setting)){{old($setting->setting)}}@else{{$setting->value}}@endif">
                                <a  data-inputid="feature_image{{$setting->id}}" data-path="{{asset('')}}" class="popup_selector popup-selector-image-box feature_image{{$setting->id}}">
                                    <p><span style="display: block;font-size: 35pt" class="mdi mdi-cloud-upload"></span>
                                        <span style="margin-top: -15px;display: block;">
                                                برای آپلود تصویر کلیک کنید
                                            </span>
                                    </p>
                                    @if(old($setting->setting) or $setting->value)<div class="img-after-upload "><img class="card-img-top img-responsive " src="@if(old($setting->setting)){{asset(old($setting->setting))}}@else{{asset($setting->value)}}@endif" alt="Card image cap"></div> @endif
                                    <div id="remove-icon">
                                        @if(old($setting->setting) or $setting->value)<span onclick="remove_image(this)" class="remove-img-after-upload"><i class="mdi mdi-close-circle"></i>حذف</span> @endif
                                    </div>
                                </a>


                            </div>
                            @endif
                    @endforeach

                </form>
            </div>
        </div>
    </div>
    @php
        $map=explode(',',setting()['map']);
        if (setting()['map']==""){
            $lat="35.679051851352995";
            $lng="51.38614654541016";
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

            });

            map.on('click', function (e) {
                alert('dd')
                marker.setLatLng(e.latlng);
                console.log(marker.getLatLng().lat, marker.getLatLng().lng);
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
                $('#map-id').val(location)
            });
            map.on('click', function (e) {
                marker.setLatLng(e.latlng);
                var location = marker.getLatLng().lat + ',' + marker.getLatLng().lng;
                $('#map-id').val(location)
                // console.log(marker.getLatLng().lat, marker.getLatLng().lng);
            });

        }

        /*function showError() {
            map.setView([{{$lat}}, {{$lng}}], 15);
                //var tileUrl = 'https://tile.thunderforest.com/atlas/{z}/{x}/{y}.png?apikey=779bc115e1714327871a7fda4181a3d2';
                var tileUrl = 'https://tile.openstreetmap.org/{z}/{x}/{y}.png';
                L.tileLayer(
                    tileUrl, {
                    }).addTo(map);

                map.on("move", function (e) {
                    marker.setLatLng(map.getCenter());
                    let myarray = {};
                    let l = marker.getLatLng().lat + "," + marker.getLatLng().lng;

                    var x = marker.getLatLng().lat, y = marker.getLatLng().lng;

                  $('#map-id').val(l)

                });


            }*/

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


    </script>
@endsection

@section('script_src')
    <script type="text/javascript" src="{{asset('packages/barryvdh/elfinder/js/jquery.colorbox-min.js')}}"></script>
    <script type="text/javascript" src="{{asset('packages/barryvdh/elfinder/js/standalonepopup.min.js')}}"></script>
    <script src="{{asset('admin-panel/plugins/tinymce/tinymce.min.js')}}"></script>
@endsection
@section('script')

    <script>
        @if(session('change_setting'))
        $.notify({
            // options
            message: '<i style="float: right;margin-top: -3px;margin-left: 10px" class="material-icons">warning</i> <span style="float: right"> {{session('change_setting')}}</span>',
            icon: '',
        }, {
            // settings
            type: 'success',
            allow_dismiss: false,
            placement: {
                from: "top",
                align: "left"
            },
            animate: {
                enter: 'animated fadeIn',
                exit: 'animated fadeOut'
            }
        });
        @endif
    </script>
    <script>
        $(document).ready(function() {

            if ($("#editor1").length > 0) {
                tinymce.init({
                    selector: "textarea#editor1",
                    file_browser_callback : elFinderBrowser,
                    theme: "modern",
                    la: "modern",
                    language : "fa_IR",
                    directionality : 'rtl',
                    height: 300,
                    plugins: [
                        "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                        "save table contextmenu directionality emoticons template paste textcolor"
                    ],
                    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",

                });
            }
            function elFinderBrowser (field_name, url, type, win) {
                tinymce.activeEditor.windowManager.open({
                    file: '<?= route('elfinder.tinymce4') ?>',// use an absolute path!
                    title: 'مدیریت فایل',
                    width: 900,
                    height: 450,
                    resizable: 'yes'
                }, {
                    setUrl: function (url) {
                        win.document.getElementById(field_name).value = url;
                    }
                });
                return false;
            }


            $('.dropify').dropify({
                messages: {
                    default: 'برای آپلود کلیک کنید',
                    replace: 'برای جایگزینی کلیک کنید',
                    remove: 'حذف',
                    error: 'Désolé, le fichier trop volumineux'
                }
            });

            $('#popup_selector').click(function () {
                $('html,body').animate({scrollTop: 0}, 'slow');
                $('html').addClass('scroll-bar-hide')
            })
            $('#cboxOverlay').click(function () {
                $('html').removeClass('scroll-bar-hide')
            })

        });



    </script>
@endsection
<?php
Session::forget('change_setting');
?>

