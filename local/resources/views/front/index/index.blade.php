@extends('front.layout.app')
@section('style')
    <style>
        .limiter ul, li{
            margin-left: 10px;
        }
        .tab-content div.row{
            margin-right: 5%;
        }
        .pulsingButton {
            width: 220px;
            text-align: center;
            white-space: nowrap;
            display: block;
            padding: 10px;
            border-radius: 10px;
            -webkit-animation: pulsing 1.25s infinite cubic-bezier(0.66, 0, 0, 1);
            -moz-animation: pulsing 1.25s infinite cubic-bezier(0.66, 0, 0, 1);
            -ms-animation: pulsing 1.25s infinite cubic-bezier(0.66, 0, 0, 1);
            animation: pulsing 1.25s infinite cubic-bezier(0.66, 0, 0, 1);
            text-decoration: none !important;
            transition: all 300ms ease-in-out;
        }

        .show-level{
            background: #3b86c1;
            color: #fff;
            padding: 0 4px;
            font-size: 11px;
            border-radius: 4px;
            position: absolute;
            top: 7px;
            left: 20px;
            z-index: 2;
            font-weight: 700;
        }


        /* Comment-out to have the button continue to pulse on mouseover */

        a.pulsingButton:hover {
            -webkit-animation: none;
            -moz-animation: none;
            -ms-animation: none;
            animation: none;
            color: #ffffff;
        }


        /* Animation */

        @-webkit-keyframes pulsing {
            to {
                box-shadow: 0 0 0 30px rgba(232, 76, 61, 0);
            }
        }

        @-moz-keyframes pulsing {
            to {
                box-shadow: 0 0 0 30px rgba(232, 76, 61, 0);
            }
        }

        @-ms-keyframes pulsing {
            to {
                box-shadow: 0 0 0 30px rgba(232, 76, 61, 0);
            }
        }

        @keyframes pulsing {
            to {
                box-shadow: 0 0 0 30px rgba(232, 76, 61, 0);
            }
        }


        .dots-bars-4 {
            width: 40px;
            height: 20px;
            --c:radial-gradient(farthest-side,currentColor 93%,#0000);
            background:
                var(--c) 0    0,
                var(--c) 50%  0,
                var(--c) 100% 0;
            background-size:8px 8px;
            background-repeat: no-repeat;
            position: relative;
            animation: db4-0 1s linear infinite alternate;
        }
        .dots-bars-4:before {
            content: "";
            position: absolute;
            width: 8px;
            height: 12px;
            background:currentColor;
            left:0;
            top:0;
            animation:
                db4-1 1s  linear infinite alternate,
                db4-2 0.5s cubic-bezier(0,200,.8,200) infinite;
        }

        @keyframes db4-0 {
            0%      {background-position: 0  100%,50% 0   ,100% 0}
            8%,42%  {background-position: 0  0   ,50% 0   ,100% 0}
            50%     {background-position: 0  0   ,50% 100%,100% 0}
            58%,92% {background-position: 0  0   ,50% 0   ,100% 0}
            100%    {background-position: 0  0   ,50% 0   ,100% 100%}
        }

        @keyframes db4-1 {
            100% {left:calc(100% - 8px)}
        }

        @keyframes db4-2 {
            100% {top:-0.1px}
        }

    </style>
@endsection
@section('content')
    <div class="container p-t-30">
        <div class="row">
            <div class="col-lg-12 col-xlg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">انتخاب سطح
                            <button type="button" data-toggle="modal" data-target="#myModal" class="btn waves-effect waves-light btn-xs btn-info float-left">راهنمای سطوح</button>
                        </h4>
                        <form id="data" method="post" action="">
                            <h6 class="card-subtitle" onclick="sendData(this)">شما میتوانید با انتخاب سطح .... </h6>
                            <div class="form-group">
                                <div class="input-group">
                                    <ul id="category" class="icheck-list d-flex">
                                        @foreach($categories as $key=> $item)
                                        <li ><input type="radio" value="{{$item->id}}" class="check" name="category" data-radio="@if($key==0)iradio_line-purple @elseif($key==1)iradio_line-orange @elseif($key==2)iradio_line-green @elseif($key==3)iradio_line-red @elseif($key==4)iradio_line-blue @endif" data-label="{{$item->title}}"> </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>


                            @if(setting()['link_sath'])
                                <div class="m-t-40">
                                    <a class="btn waves-effect waves-light btn-secondary" style="color: red" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                        از سطح خود مطمعن نیستم!
                                    </a>
                                    <div class="collapse m-t-5" id="collapseExample">
                                        <div class="card card-body">
                                            شما برای تعیین سطح خود،باید در آزمون تعیین سطح شرکت کنید. <a href="{{setting()['link_sath']}}" class="waves-effect  waves-light btn" style="color: red"> شرکت در تعیین سطح آنلاین</a>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="m-t-40 ">
                                <h4 class="card-title">هدف من</h4>
                                <h6 class="card-subtitle">هدف شما برای یادگیری زبان .... </h6>
                                <div class="">

                                    <ul class="nav nav-pills m-t-30 m-b-30 p-r-10">
                                        <li class="nav-item"> <a href="#navpillsAzmon-1" class="nav-link btn waves-effect waves-light btn-primary" data-toggle="tab" aria-expanded="false" ondblclick="closeNavpills()" onclick="openNavpills()">آزمون دارم</a> </li>
<!--                                        <li class="nav-item"> <a href="#navpillsAzmon-2" class="nav-link btn waves-effect waves-light btn-danger" data-toggle="tab" aria-expanded="true">آزمون ندارم</a> </li>-->
                                        <li class="nav-item"> <a  style="background: #1815146b;border: 1px solid #9c9b9a;" class="nav-link btn waves-effect waves-light btn-danger">آزمون ندارم</a> </li>
                                    </ul>
                                    <div class="tab-content br-n pn">
                                        <div id="navpillsAzmon-1" class="tab-pane target-tab">
                                            <input name="target" value="1" type="hidden">
                                            <div class="card card-body">
                                                <ul class="nav nav-pills m-t-30 m-b-30">
                                                        @foreach($exams as $key=> $item)
                                                    <li class="nav-item"> <a href="#navpills-{{$item->id}}" class="nav-link btn waves-effect waves-light btn-outline-info" data-toggle="tab" aria-expanded="false" @if($key!=0)style="background: #1815146b;border: 1px solid #9c9b9a;" @endif>{{$item->title}}</a> </li>
                                                    @endforeach
                                                </ul>
                                                <div class="tab-content br-n pn">
                                                    @foreach($exams as $key=> $item)
                                                    <div id="navpills-{{$item->id}}" class="tab-pane exam-tap">
                                                        <div class="row">
                                                            @if($item->title=="آیلتس")
                                                                <input name="exam" type="hidden" value="{{$item->title=="آیلتس"}}" >
                                                            <div class="col-md-8">
                                                                <div class="form-group row">
                                                                    <label class="control-label text-right col-md-3" style="line-height: 40px;">نمره مورد نیاز من</label>
                                                                    <div class="col-md-9">
                                                                        <select class="form-control custom-select" name="score" >
                                                                            @foreach($scores as $key=>$item)
                                                                                <option value="{{$item->id}}">{{$item->title}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        <small class="form-control-feedback"> نمره ای که میخواهید کسب کنید را انتخاب کنید</small> </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <label class="control-label text-right col-md-3" style="line-height: 47px;">ماژول امتحان</label>
                                                                    <div class="col-md-9">
                                                                        <div class="input-group">
                                                                            <ul class="icheck-list d-flex">
                                                                                @foreach($modules as $key=>$item)
                                                                                <li><input type="radio" class="check" name="module" value="{{$item->id}}" data-radio="@if($key==0)iradio_line-blue @else iradio_line-blue @endif" data-label="{{$item->title}}"> </li>
                                                                                @endforeach
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                                <div class="w-100 text-left" style="padding-left: 15px">
                                                                    <button type="button" class="btn btn-success float-left pulsingButton"><i class="fa fa-check"></i> مشاهده پیشنهادات</button>
                                                                </div>
                                                                @endif

                                                        </div>

                                                    </div>

                                                    @endforeach

                                                </div>

                                            </div>

                                        </div>
                                        <div id="navpillsAzmon-2" class="tab-pane">
                                            <input name="target" value="2">
                                            <div class="card card-body">
                                                <div class="form-group">
                                                    <div class="input-group">

                                                        <ul class="icheck-list d-flex">
                                                            <li><input type="radio" class="check" name="line-radio" data-radio="iradio_line-orange " data-label="بیزنس"> </li>
                                                            <li><input type="radio" class="check" name="line-radio"  data-radio="iradio_line-orange" data-label="سفر"> </li>
                                                            <li><input type="radio" class="check" name="line-radio"  data-radio="iradio_line-orange " data-label="تحصیل"> </li>
                                                            <li><input type="radio" class="check" name="line-radio"  data-radio="iradio_line-orange " data-label="عالقه"> </li>

                                                        </ul>
                                                    </div><!--<button type="button" class="btn btn-success float-left pulsingButton"><i class="fa fa-check"></i> مشاهده پیشنهادات</button>-->
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </form>



                        <div class="card card-body main-card hide">
                            <div id="posts" class="row">
                                <div class="col-md-12 loader hide" style="padding: 50px 0;background: #f7f7f7;">
                                    <div class="dots-bars-4" style="margin: 0 auto"><p style="width: 170px; padding: 25px 0; margin-right: -30px;">در حال بارگذاری...</p></div>
                                </div>

                                <div class="col-md-12 text-center empty-post hide" style="padding: 50px 0;background: #f7f7f7;">
                                    طبق مشخصات شما چیزی یافت نشد!
                                </div>

                                <div class="col-md-6 posts-div hide">
                                    <div class="col-md-12">
                                        <h4 class="d-inline">کتاب های مناسب شما</h4>
                                        <hr>

                                        <!-- Row -->
                                        <div class="row" id="books">
<!--                                            <div class="col-md-6">
                                                &lt;!&ndash; Card &ndash;&gt;
                                                <div class="card">
                                                    <img class="card-img-top img-responsive" style="max-height: 220px;" src="{{asset('images/51GNpcsFxYL._SX359_BO1204203200_.jpg')}}" alt="Card image cap">
                                                    <div class="card-body">
                                                        <h4 class="card-title">کتاب‌های oxford word skills (آموزش لغت بطور اختصاصی)</h4>
                                                        <p class="card-text">سری کتاب‌های آموزش لغات انگلیسی آکسفورد که در سه سطح ارائه می‌شود و در آن کلمات، عبارات و دستور زبان‌های داخل متن مورد بررسی قرار می‌گیرند.</p>
                                                        <a href="#" class="btn btn-primary">مشاهده</a>
                                                    </div>
                                                </div>
                                                &lt;!&ndash; Card &ndash;&gt;
                                            </div>-->

                                        </div>
                                        <!-- Row -->
                                    </div>
                                </div>

                                <div class="col-md-6 more-div hide">
                                    <div class="col-md-12 m-b-20 sounds-div hide">
                                        <h4 class="d-inline">پادکست های پیشنهادی</h4>
                                        <hr>

                                        <!-- Row -->
                                        <div id="sounds" class="row">
<!--                                            <div class="col-md-12">
                                                &lt;!&ndash; Card &ndash;&gt;
                                                <a class="card-text">With supporting text below as a natural lead-in to additional content.</a>
                                            </div>-->
                                        </div>
                                        <!-- Row -->
                                    </div>

                                    <div class="col-md-12 links-div hide">
                                        <h4 class="d-inline">وبسایت ها</h4>
                                        <hr>

                                        <!-- Row -->
                                        <div id="links" class="row">

                                        </div>

                                    </div>
                                </div>



                            </div>


                            <div class="text-center m-t-40">
                            <span>
                                مشاوره تخصصی میخوام،
                            </span>
                                <a class="" style="color: #007bff;cursor: pointer" onMouseOver="this.style.color='red'" onMouseOut="this.style.color='#007bff'" data-toggle="modal" data-target="#myModalContact">
                                    ارسال پیام
                                </a>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">راهنمای سطوح</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <p><?= setting()['about'] ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="float-left btn btn-inverse" data-dismiss="modal">بستن</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div id="myModalContact" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">ارسال پیام</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form method="post" action="/contact-store" class="m-t-10" novalidate>
                        @csrf
                        <div class="form-group">
                            <h5>نام و نام خانوادگی <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="text" name="name" class="form-control" required data-validation-required-message="نام و نام خانوادگی را وارد کنید"> </div>
                        </div>
                        <div class="form-group">
                            <h5>شماره موبایل <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="text" name="mobile" class="form-control" required data-validation-required-message="شماره موبایل را وارد کنید"> </div>
                        </div>
                        <div class="form-group">
                            <h5>ایمیل </h5>
                            <div class="controls">
                                <input type="email" name="email" class="form-control" data-validation-email-message="ایمیل را بطور صحیح وارد کنید"> </div>
                        </div>
                        <div class="form-group">
                            <h5>متن پیام <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <textarea name="message" id="textarea" class="form-control" required  data-validation-required-message="متن پیام خود را وارد کنید" placeholder="متن پیام خود را وارد کنید"></textarea>
                            </div>
                        </div>
                        <div class="text-xs-right">
                            <button type="submit" class="btn btn-info">ارسال پیام</button>
                            <button type="reset" class="float-left btn btn-inverse" data-dismiss="modal" >بستن</button>
                        </div>
                    </form>
                </div>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <input name="asset" value="{{asset('')}}" type="hidden">
@endsection
@section('script')

    <script>


        function closeNavpills() {
            $('#navpillsAzmon-1').removeClass('active');
        }
        function openNavpills() {
            $('#navpillsAzmon-1').addClass('active');
        }



    </script>
    <script>

        $('.pulsingButton').on('click', function(){
            var asset=$('input[name=asset]').val();
            var send="true";
            var category = "";
            var selected = $("input[type='radio'][name='category']:checked");
            if (selected.length > 0) {
                category = selected.val();
            }
            var target=  $('.target-tab.active').find('input[name=target]').val();
            var score=  $('.exam-tap.active').find('select[name=score]').val();
            var exam=  $('.exam-tap.active').find('input[name=exam]').val();
            var module = "";
            var selected_module = $("input[type='radio'][name='module']:checked");
            if (selected_module.length > 0) {
                module = selected_module.val();
            }

            if (category==""){
                send="false";
                Lobibox.notify('error', {
                    size: 'mini',
                    showClass: 'Lobibox-custom-class hide-close-icon',
                    iconSource: "fontAwesome",
                    delay:5000,
                    soundPath: '{{asset('admin-panel/sounds/sounds/')}}',
                    position: 'left top', //or 'center bottom'
                    msg: 'سطح خود را انتخاب کنید',
                });
            }
            if (module==""){
                send="false";
                Lobibox.notify('error', {
                    size: 'mini',
                    showClass: 'Lobibox-custom-class hide-close-icon',
                    iconSource: "fontAwesome",
                    delay:5000,
                    soundPath: '{{asset('admin-panel/sounds/sounds/')}}',
                    position: 'left top', //or 'center bottom'
                    msg: 'ماژول امتحان را انتخاب کنید',
                });
            }

            if(send=="true"){
                $('.empty-post').addClass('hide');
                $('#books').empty();
                $('#sounds').empty();
                $('#links').empty();

                $('.main-card').removeClass('hide');
                $('.more-div').addClass('hide');
                $('.posts-div').addClass('hide');
                $('.loader').removeClass('hide');
                var CSRF_TOKEN="{{csrf_token()}}";
                var url = '/get_data';
                var data = {_token: CSRF_TOKEN, category:category,target:target,score:score,exam:exam,module:module};
                $.post(url, data, function (msg) {
                    if (msg.posts!=""){
                        //پست ها
                        $(msg.posts).each(function(index,value){
                            var btn_link="";
                            if(value.link){
                                btn_link='<a href="'+value.link+'" class="btn btn-primary">مشاهده</a>';
                            }
                            $('#books').append('<div class="col-md-6"><span class="show-level">'+value.level+'</span><div class="card"><img class="card-img-top img-responsive" style="max-height: 220px;" src="'+asset+value.image+'" alt="'+value.title+'"><div class="card-body"><h4 class="card-title">'+value.title+'</h4><p class="card-text">'+value.shortContent+'</p>'+btn_link+'</div></div></div>')
                            $('.posts-div').removeClass('hide');
                        });

                        //پادکدست ها
                        var sound_empty=true
                        $(msg.posts).each(function(index,value){
                            if (value.sounds!=""){
                                var sounds = value.sounds.split('||');
                                $(sounds).each(function(inx,vl){
                                    var int=parseInt(index)+parseInt(inx)+parseInt(1);
                                    $('#sounds').append('<div class="col-md-12"><a href="'+vl+'" target="_blank" class="card-text">مشاهده لینک '+int+'</a></div>')
                                });
                                $('.more-div').removeClass('hide');
                                $('.sounds-div').removeClass('hide');
                                sound_empty=false;
                            }else {
                                $('.sounds-div').addClass('hide');

                            }

                        });

                        //لینک ها
                        var link_empty=true
                        $(msg.posts).each(function(index,value){
                            if (value.links!=""){
                                var sounds = value.links.split('||');
                                $(sounds).each(function(inx,vl){
                                    var int=parseInt(index)+parseInt(inx)+parseInt(1);
                                    $('#links').append('<div class="col-md-12"><a href="'+vl+'" target="_blank" class="card-text">مشاهده سایت '+int+'</a></div>')
                                });
                                $('.links-div').removeClass('hide');
                                $('.more-div').removeClass('hide');
                                link_empty=false
                            }else {
                                $('.links-div').addClass('hide');
                            }

                        });
                        if(link_empty==true && sound_empty==true){
                            $('.posts-div').removeClass('col-md-6');
                            $('.posts-div').addClass('col-md-12');

                            $('#books > div').removeClass('col-md-6');
                            $('#books > div').addClass('col-md-3');
                        }else {
                            $('.posts-div').addClass('col-md-6');
                            $('.posts-div').removeClass('col-md-12');

                            $('#books > div').addClass('col-md-6');
                            $('#books > div').removeClass('col-md-3');
                        }


                        $('.loader').addClass('hide');
                    }else{
                        $('.empty-post').removeClass('hide');
                        $('.loader').addClass('hide');
                    }


                });
            }


        })

    </script>
@endsection
