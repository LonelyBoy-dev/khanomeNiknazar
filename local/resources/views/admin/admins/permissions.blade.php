@extends('admin.layout.app')

@section('style_href')
    <link href="{{asset('admin-panel/plugins/bootstrap-switch/bootstrap-switch.min.css')}}" rel="stylesheet">
    <link href="{{asset('admin-panel/plugins/waitme/waitMe.css')}}" rel="stylesheet">
@endsection
@section('style')
    <style>
        .hr-span{
            position: relative;
            margin: 40px 0 40px;
        }
        .hr-span span{
            position: absolute;
            top: -14px;
            right: 14px;
            border: 1px dashed #61c579;
            padding: 3px 9px;
            background: #fff;
            border-radius: 5px;
            font-size: 12px;

        }

        .addresses li{
            position: relative;
        }
        .addresses [type="radio"] + label:before,.addresses [type="radio"] + label:after{
            opacity: 0;
        }
        .addresses .address-nowrap{
            width: 80px;
            position: absolute;
            top: 5px;
            left: 0;
            z-index: 1;
        }
        .addresses .address-nowrap button{
            padding: 3px 7px 0;
            border: 1px solid rgba(120, 130, 140, 0.13);
            box-shadow: none;
        }
        .addresses .address-nowrap button:first-child{
            padding: 3px 8px 0 9px;
        }

        .comment-click-status{
            text-align: left;
        }
        .comment-click-status button{
            padding: 3px 7px 0;background: #fbeaea;
        }
        .comment-click-status button:last-child{
            background: #edf7ed;
        }
        .comment-click-status button:last-child:hover{
            background:#5a6268;
        }
    </style>
@endsection
@section('content')
    <!-- Column -->
    <div class="row">
        <div class="col-lg-12 col-xlg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="demo-checkbox p-r-20">
                        <input type="checkbox" name="" value="" id="check_All" class="filled-in chk-col-purple">
                        <label class="m-b-0" for="check_All">مدیر با دسترسی کامل</label>

                        <input type="checkbox" name="" value="" id="wither" class="filled-in chk-col-purple">
                        <label class="m-b-0" for="wither">نویسنده</label>




                        <form action="/admin/admins/permissions/store/{{$id}}" method="post">
                            <button style="float: left;position: fixed;left: 58px;" TYPE="submit" class="btn btn-success">ویرایش دسترسی ها</button>
                            @csrf
                            <?php $role_id=\App\Models\Role::where('admin_id',@$id)->first();?>
                        @foreach($items as $item)

                                <?php $permissions=\App\Models\Permission::where(['parent'=>$item->id])->get(); ?>

                                    <h4 class="card-title m-t-40" style="margin-right: -15px;font-weight: 700">{{$item->title}}</h4>
                                    @foreach($permissions as $permission)
                                        <?php $check=\App\Models\RoleHasPermission::where(['role_id'=>$role_id->id,'permission_id'=>$permission->id])->first(); ?>
                                    <input type="checkbox" name="permission[]" value="{{$permission->id}}" id="md_checkbox_{{$permission->id}}" class="filled-in chk-col-purple" @if($check)checked @endif>
                                    <label for="md_checkbox_{{$permission->id}}">{{$permission->title}}</label>
                                    @endforeach
                            @endforeach


                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('script_src')
    <script src="{{asset('admin-panel/plugins/bootstrap-switch/bootstrap-switch.min.js')}}"></script>
    <script src="{{asset('admin-panel/plugins/waitme/waitMe.js')}}"></script>
@endsection
@section('script')
    <script>
        $(".bt-switch input[type='checkbox'], .bt-switch input[type='radio']").bootstrapSwitch();
        var radioswitch = function() {
            var bt = function() {
                $(".radio-switch").on("switch-change", function() {
                    $(".radio-switch").bootstrapSwitch("toggleRadioState")
                }), $(".radio-switch").on("switch-change", function() {
                    $(".radio-switch").bootstrapSwitch("toggleRadioStateAllowUncheck")
                }), $(".radio-switch").on("switch-change", function() {
                    $(".radio-switch").bootstrapSwitch("toggleRadioStateAllowUncheck", !1)
                })
            };
            return {
                init: function() {
                    bt()
                }
            }
        }();
        $(document).ready(function() {
            radioswitch.init()
        });
    </script>

    <script>
        function uploadImageUserNew() {
            $('.profile-body .wimgpf').waitMe({
                effect: 'pulse',
                text: 'در حال بارگذاری ...',
                maxSize: '',
                waitTime: 1,
                textPos: 'vertical',
                fontSize: '10',
                source: '',
            });
            var formData = new FormData();
            formData.append("file", $('#image_profile')[0].files[0]);
            $.ajax({
                type: "post",
                url: "{{route('uploadimageuser-new')}}",
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                },
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    $('.wimgpf').slideDown(300);
                    $('.waitMe').fadeOut();
                    $('.profile-body #imgpf').attr('src', data.status);
                },
                error: function (err) {
                    if (err.status == 422) {
                        $('#error_user').slideDown(150);
                        $.each(err.responseJSON.errors, function (i, error) {
                            $('#error_item').append($('<span style="color: #fff;font-size: 12px">' + error[
                                    0] +
                                '</span><br>'));
                        });
                    }
                }
            });

        }
    </script>

    <script>
        $('#active_user').on('change', function () {
            var status = "INACTIVE";
            if ($(this).is(':checked')) {
                status = "ACTIVE";
            }
            Change_status_user('NO',status,'{{$user->id}}','{{ csrf_token() }}')
        });

        $('.SelectAddress').click(function () {
            SelectAddress(this,'{{ csrf_token() }}')
        });

        $('.comment-click-status button').click(function () {
            comment_click_status(this,'{{ csrf_token() }}')
        });

    </script>




@endsection
