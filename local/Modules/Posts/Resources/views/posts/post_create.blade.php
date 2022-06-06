@extends('admin.layout.app')
@section('style_href')
    <link href="{{asset('packages/barryvdh/elfinder/css/colorbox.css')}}" rel="stylesheet">
@endsection
@section('style')
    <style>
        input.sound::placeholder {
            text-align: right;
        }
        [type="radio"] + label:before, [type="radio"] + label:after{
            right: 0;
            left: auto;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            @include('errors.error_message')
            <form class="row" method="POST" action="{{route('posts.store')}}">
                @csrf
                <div class="col-lg-8 col-xs-12 col-sm-12 ali-margin-0">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-horizontal form-material">

                                <div class="form-group">
                                    <label class="col-md-12">عنوان</label>
                                    <div class="col-md-12">
                                        <input onkeyup="convertToSlug()" type="text" name="title" value="{{old('title')}}"
                                               placeholder="عنوان مطلب را وارد کنید"
                                               class="form-control form-control-line">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">برچسب سطح</label>
                                    <div class="col-md-12">
                                        <input type="text" name="level" value="{{old('level')}}"
                                               placeholder="برچسب سطح را وارد کنید"
                                               class="form-control form-control-line">
                                    </div>
                                </div>
                            <!--
                                                           <div class="form-group">
                                    <label class="col-md-12">نامک</label>
                                    <div class="col-md-12">
                                        <input type="text" name="slug" value="{{old('slug')}}" placeholder="نامک را وارد کنید"
                                               class="form-control form-control-line">
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="col-md-12">توضیحات</label>
                                    <div class="col-md-12">
                                        <textarea name="content" id="editor1" placeholder="توضیحات مطلب را وارد کنید"
                                                  class="form-control" rows="20">{{old('content')}}</textarea>
                                    </div>
                                </div>
-->
                                <div class="form-group">
                                    <label class="col-md-12">لینک</label>
                                    <div class="col-md-12">
                                        <input type="text" name="link" value="{{old('link')}}"
                                               placeholder="لینک مطلب را وارد کنید"
                                               class="form-control form-control-line sound" style="text-align: left !important;">
                                    </div>
                                </div>

                                <div class="form-group m-0">
                                    <label class="col-md-12">خلاصه </label>
                                    <div class="col-md-12">
                                        <textarea name="shortContent" placeholder="شرح مختصر (توضیحی کوتاه از نوشته) را وارد کنید"
                                                  class="form-control" rows="5">{{old('shortContent')}}</textarea>
                                    </div>
                                </div>

                            </div>


                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            فایل های صوتی
                            <button type="button" style="padding: 4px 5px 0;float: left" class="btn btn-info" onclick="add_sound(this)"><i class="fa fa-plus"></i> افزودن سطر </button>
                        </div>
                        <div class="card-body">
                            <div class="form-horizontal form-material">
                                <div id="sounds" class="form-group ">
<!--                                    <label class="col-md-12">فایل صوتی</label>-->
                                    @if(old('sounds'))
                                    @foreach(old('sounds') as $key=>$row)

                                        <div class="row satr">
                                            <div class="col-md-11">
                                                <input type="text" name="sounds[]" style="text-align: left !important;" value="{{$row}}" placeholder="لینک فایل صوتی را وارد کنید"
                                                       class="form-control form-control-line sound">
                                            </div>
                                            <div class="col-md-1 p-0 sounds-action">
                                                @if($key!=0)
                                                    <button type="button" style="padding: 4px 5px 0;margin-top: 5px;" class="btn btn-danger" onclick="remove_row(this)"><i class="fa fa-times"></i> </button>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                    @else
                                    <div class="row">
                                        <div class="col-md-11">
                                            <input type="text" name="sounds[]" style="text-align: left !important;" value="" placeholder="لینک فایل صوتی را وارد کنید"
                                                   class="form-control form-control-line sound">
                                        </div>
                                        <div class="col-md-1 p-0 sounds-action">

                                        </div>
                                    </div>
                                    @endif
                                </div>


                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            لینک های سایت
                            <button type="button" style="padding: 4px 5px 0;float: left" class="btn btn-info" onclick="add_link(this)"><i class="fa fa-plus"></i> افزودن سطر </button>
                        </div>
                        <div class="card-body">
                            <div class="form-horizontal form-material">
                                <div id="links" class="form-group ">
<!--                                    <label class="col-md-12">لینک سایت</label>-->
                                    @if(old('links'))
                                        @foreach(old('links') as $key=>$row)

                                        <div class="row satr">
                                            <div class="col-md-11">
                                                <input type="text" name="links[]" style="text-align: left !important;" value="{{$row}}" placeholder="لینک سایت را وارد کنید"
                                                       class="form-control form-control-line sound">
                                            </div>
                                            <div class="col-md-1 p-0 sounds-action">
                                                @if($key!=0)
                                                <button type="button" style="padding: 4px 5px 0;margin-top: 5px;" class="btn btn-danger" onclick="remove_row(this)"><i class="fa fa-times"></i> </button>
                                                    @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                    <div class="row">
                                        <div class="col-md-11">
                                            <input type="text" name="links[]" style="text-align: left !important;" value="" placeholder="لینک سایت را وارد کنید"
                                                   class="form-control form-control-line sound">
                                        </div>
                                        <div class="col-md-1 p-0 links-action">

                                        </div>
                                    </div>
                                     @endif

                                </div>




                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-xs-12 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-horizontal form-material">
                                <div class="form-group">
                                    <label class="control-label">وضعیت انتشار</label>
                                    <select class="form-control" name="status">
                                        <option @if(old('status')=="PUBLISHED") selected @endif value="PUBLISHED" >انتشار</option>
                                        <option @if(old('status')=="DRAFT") selected @endif value="DRAFT">پیش نویس</option>
                                    </select>
                                </div>

                                <div class="form-group m-0">
                                    <div class="col-md-12">
                                        <button type="submit" style="padding: 3px" class="btn waves-effect waves-light btn-block btn-info">
                                            ایجاد
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="form-horizontal form-material">
                                <div class="form-group">
                                    <label class="control-label m-b-10">دسته بندی</label>
                                    <div class="p-r-10">
                                        <div class="scroll-sidebar p-b-0" style="overflow: hidden; width: auto; max-height: 235px">
                                            @foreach($items as $category)
                                                <div style="margin-bottom: 2px">
                                                    <input <?php
                                                           if (!empty(old('category'))){
                                                               foreach (old('category') as $category_id){
                                                                   if ($category->id == $category_id){
                                                                       echo 'checked';
                                                                   }
                                                               }
                                                           }
                                                           ?>
                                                           type="checkbox" id="category{{$category->id}}" name="category[]" value="{{$category->id}}" class="filled-in chk-col-deep-purple">
                                                    <label for="category{{$category->id}}">{{$category->title}}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="card">
                        <div class="card-body">
                            <div class="form-horizontal form-material">
                                <div class="form-group">
                                    <label class="control-label m-b-10">هدف</label>
                                    <div class="p-r-10">
                                        <div class="scroll-sidebar p-b-0" style="overflow: hidden; width: auto; max-height: 235px">
                                                <div style="margin-bottom: 2px">
                                                    <input type="radio" id="target1" name="target" @if(old('target')=="1") checked @endif value="1" class="filled-in chk-col-deep-purple">
                                                    <label for="target1">آزمون دارم</label>
                                                </div>
                                                <div style="margin-bottom: 2px">
                                                    <input type="radio" id="target2" name="target" @if(old('target')=="2") checked @endif value="2" class="filled-in chk-col-deep-purple">
                                                    <label for="target2">آزمون ندارم</label>
                                                </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card examdiv @if(old('target')!="1") hide @endif ">
                        <div class="card-body">
                            <div class="form-horizontal form-material">
                                <div class="form-group">
                                    <label class="control-label m-b-10">آزمون ها</label>
                                    <div class="p-r-10">
                                        <div class="scroll-sidebar p-b-0" style="overflow: hidden; width: auto; max-height: 235px">
                                            @foreach($exams as $exam)
                                                <div style="margin-bottom: 2px">
                                                    <input <?php
                                                           if (!empty(old('exam'))){
                                                               foreach (old('exam') as $exam_id){
                                                                   if ($exam->id == $exam_id){
                                                                       echo 'checked';
                                                                   }
                                                               }
                                                           }
                                                           ?>
                                                           type="checkbox" id="exam{{$exam->id}}" name="exam[]" value="{{$exam->id}}" class="filled-in chk-col-deep-purple">
                                                    <label for="exam{{$exam->id}}">{{$exam->title}}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card notexamdiv @if(old('target')!="2") hide @endif">
                        <div class="card-body">
                            <div class="form-horizontal form-material">
                                <div class="form-group">
                                    <label class="control-label m-b-10">هدف های غیر آزمون</label>
                                    <div class="p-r-10">
                                        <div class="scroll-sidebar p-b-0" style="overflow: hidden; width: auto; max-height: 235px">
                                            @foreach($notexams as $notexam)
                                                <div style="margin-bottom: 2px">
                                                    <input <?php
                                                           if (!empty(old('notexam'))){
                                                               foreach (old('notexam') as $notexam_id){
                                                                   if ($notexam->id == $notexam_id){
                                                                       echo 'checked';
                                                                   }
                                                               }
                                                           }
                                                           ?>
                                                           type="checkbox" id="notexams{{$notexam->id}}" name="notexam[]" value="{{$notexam->id}}" class="filled-in chk-col-deep-purple">
                                                    <label for="notexams{{$notexam->id}}">{{$notexam->title}}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card examdiv @if(old('target')!="1") hide @endif">
                        <div class="card-body">
                            <div class="form-horizontal form-material">
                                <div class="form-group">
                                    <label class="control-label m-b-10">نمره مورد نیاز</label>
                                    <div class="p-r-10">
                                        <div class="scroll-sidebar p-b-0" style="overflow: hidden; width: auto; max-height: 235px">
                                            @foreach($scores as $score)
                                                <div style="margin-bottom: 2px">
                                                    <input <?php
                                                           if (!empty(old('score'))){
                                                               foreach (old('score') as $score_id){
                                                                   if ($score->id == $score_id){
                                                                       echo 'checked';
                                                                   }
                                                               }
                                                           }
                                                           ?>
                                                           type="checkbox" id="score{{$score->id}}" name="score[]" value="{{$score->id}}" class="filled-in chk-col-deep-purple">
                                                    <label for="score{{$score->id}}">{{$score->title}}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card examdiv @if(old('target')!="1") hide @endif">
                        <div class="card-body">
                            <div class="form-horizontal form-material">
                                <div class="form-group">
                                    <label class="control-label m-b-10">ماژول امتحان</label>
                                    <div class="p-r-10">
                                        <div class="scroll-sidebar p-b-0" style="overflow: hidden; width: auto; max-height: 235px">
                                            @foreach($modules as $module)
                                                <div style="margin-bottom: 2px">
                                                    <input <?php
                                                           if (!empty(old('module'))){
                                                               foreach (old('module') as $module_id){
                                                                   if ($module->id == $module_id){
                                                                       echo 'checked';
                                                                   }
                                                               }
                                                           }
                                                           ?>
                                                           type="checkbox" id="module{{$module->id}}" name="module[]" value="{{$module->id}}" class="filled-in chk-col-deep-purple">
                                                    <label for="module{{$module->id}}">{{$module->title}}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="form-horizontal form-material">
                                <div class="form-group">
                                    <label for="feature_image" class="control-label m-b-10">تصویر شاخص</label>
                                    <input type="hidden" id="feature_image" name="feature_image" value="{{old('feature_image')}}">
                                    <a data-inputid="feature_image" data-path="{{asset('')}}" class="popup_selector popup-selector-image-box feature_image">
                                        <p><span style="display: block;font-size: 35pt" class="mdi mdi-cloud-upload"></span>
                                            <span style="margin-top: -15px;display: block;">
                                                برای آپلود تصویر کلیک کنید
                                            </span>
                                        </p>
                                        @if(old('feature_image'))<div class="img-after-upload"><img class="card-img-top img-responsive " src="{{asset(old('feature_image'))}}" alt="Card image cap"></div> @endif
                                        <div id="remove-icon">
                                            @if(old('feature_image'))<span onclick="remove_image(this)" class="remove-img-after-upload"><i class="mdi mdi-close-circle"></i>حذف</span> @endif
                                        </div>
                                    </a>


                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection
@section('script_src')
    <script type="text/javascript" src="{{asset('packages/barryvdh/elfinder/js/jquery.colorbox-min.js')}}"></script>
    <script type="text/javascript" src="{{asset('packages/barryvdh/elfinder/js/standalonepopup.min.js')}}"></script>
    <script src="{{asset('admin-panel/plugins/tinymce/tinymce.min.js')}}"></script>
@endsection
@section('script')
    {{--    <script>
            CKEDITOR.replace('editor1', {
                language: 'fa',
                filebrowserBrowseUrl : '/admin/FileManager',

            });
            CKEDITOR.editorConfig = function( config ) {
                config.toolbar = [
                    { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
                    { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
                ];
            };
        </script>--}}
    <script>

        $('input[name=target]').on('click', function(){
            var item=this;
            isChecked = $(item).is(':checked')

            if(isChecked){
                if($(item).val()==1){
                    $('.examdiv').removeClass('hide');
                    $('.notexamdiv').addClass('hide');
                }else if($(item).val()==2){
                    $('.examdiv').addClass('hide');
                    $('.notexamdiv').removeClass('hide');
                }
            }
            else{
                $('.examdiv').addClass('hide');
                $('.notexamdiv').addClass('hide');
            }
        })

        function add_sound(item) {
            $('#sounds').append(`<div class="row satr">
                                        <div class="col-md-11">
                                            <input type="text" name="sounds[]" style="text-align: left !important;" value="" placeholder="لینک فایل صوتی را وارد کنید"
                                                   class="form-control form-control-line sound">
                                        </div>
                                        <div class="col-md-1 p-0 sounds-action">
                                            <button type="button" style="padding: 4px 5px 0;margin-top: 5px;" class="btn btn-danger" onclick="remove_row(this)"><i class="fa fa-times"></i> </button>
                                        </div>
                                    </div>`)
        }
        function add_link(item) {
            $('#links').append(`<div class="row satr">
                                        <div class="col-md-11">
                                            <input type="text" name="links[]" style="text-align: left !important;" value="" placeholder="لینک سایت را وارد کنید"
                                                   class="form-control form-control-line sound">
                                        </div>
                                        <div class="col-md-1 p-0 sounds-action">
                                            <button type="button" style="padding: 4px 5px 0;margin-top: 5px;" class="btn btn-danger" onclick="remove_row(this)"><i class="fa fa-times"></i> </button>
                                        </div>
                                    </div>`)
        }

        function remove_row(item) {
            $(item).parents('.satr').remove();
        }

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


            $('#cboxOverlay').click(function () {
                $('html').removeClass('scroll-bar-hide');
            });

        });



    </script>
@endsection
