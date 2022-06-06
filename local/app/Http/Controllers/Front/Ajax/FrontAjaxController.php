<?php

namespace App\Http\Controllers\Front\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\BlockList;
use App\Models\Category;
use App\Models\CategoryUser;
use App\Models\Comment;
use App\Models\Favorite;
use App\Models\Gallery;
use App\Models\Package;
use App\Models\Pay;
use App\Models\Report;
use App\Models\Reserve;
use App\Models\Timing;
use App\Models\TimingsDay;
use App\Models\User;
use App\Models\TimingsUser;
use App\Models\DesksService;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use Modules\Posts\Entities\Post;
use Nette\Utils\DateTime;
use function Matrix\add;

class FrontAjaxController extends Controller
{
    public function set_view_post(Request $request)
    {
        $view = Post::find($request->id);
        $view->view = $view->view + 1;
        $view->save();
    }

    public function get_timings_hairdresser(Request $request)
    {
        $Timings = \App\Models\Timing::where(['user_id' => Auth::id(), 'timings_interval_id' => $request->id])->orderby('id', 'asc')->get();
        foreach ($Timings as $Timing) {
            ?>
            <div class="row form-row hours-cont">
                <div class="col-12 col-md-10">
                    <div class="row form-row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label>زمان شروع</label>
                                <div class="input-group clockpicker " data-placement="bottom" data-align="top"
                                     data-autoclose="true">
                                    <input name="startTime[]" type="text" class="form-control"
                                           value="<?= $Timing->startTime ?>" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label>زمان پایان</label>
                                <div class="input-group clockpicker " data-placement="bottom" data-align="top"
                                     data-autoclose="true">
                                    <input name="endTime[]" type="text" class="form-control"
                                           value="<?= $Timing->endTime ?>" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input name="Timings_id[]" value="<?= $Timing->id ?>" type="hidden">
                        <input name="timings_interval_id" value="" type="hidden">
                    </div>
                </div>
                <div class="col-12 col-md-2"><label class="d-md-block d-sm-none d-none">&nbsp;</label><a
                            onclick="removeTiming(this,'<?= $Timing->id ?>','<?= csrf_token() ?>')"
                            class="btn btn-danger trash"><i class="far fa-trash-alt"></i></a></div>
            </div>
        <?php }
    }

    public function timings_hairdresser_remove(Request $request)
    {
        Timing::where('id', $request->id)->delete();
    }

    public function add_remove_favorite(Request $request)
    {
        $item = Favorite::where(['user_id' => Auth::id(), 'hairstylist_id' => $request->id])->first();
        if ($item != "") {
            $item->delete();
            echo 'remove';
        } else {
            $item = new Favorite();
            $item->user_id = Auth::id();
            $item->hairstylist_id = $request->id;
            $item->save();
            echo 'add';
        }
    }

    public function uploadImageUser(Request $request)
    {
        $gallery = new Gallery();
        $file = $request->file('file');

        $image = Image::make($file);

        //save image
        $name = time() . rand() . $file->getClientOriginalName();
        /*$image->resize(150, 150, function ($constraint) {
            $constraint->aspectRatio();
        });*/
        if (!is_dir('FileUploader')) {
            mkdir("FileUploader");
        }
        if (!is_dir('FileUploader/users')) {
            mkdir("FileUploader/users");
        }
        if (!is_dir('FileUploader/users/' . Auth::id())) {
            mkdir("FileUploader/users/" . Auth::id());
        }
        if (!is_dir('FileUploader/users/' . Auth::id() . '/galleries')) {
            mkdir("FileUploader/users/" . Auth::id() . '/galleries');
        }

        $image->save('FileUploader/users/' . Auth::id() . '/galleries/' . $name);
        $gallery->imagePath = 'FileUploader/users/' . Auth::id() . '/galleries/' . $name;
        $gallery->imageName = $file->getClientOriginalName();
        $gallery->user_id = Auth::id();
        $gallery->save();

        $galleries = Gallery::where('user_id', Auth::id())->get();
        $csrf_token = csrf_token();
        return response()->json([
            'id' => $gallery->id,
            'csrf_token' => "'$csrf_token'",
            'src' => asset($gallery->imagePath),
            'count' => count($galleries)
        ]);

    }

    public function remove_gallery(Request $request)
    {
        $gallery = Gallery::where(['user_id' => Auth::id(), 'id' => $request->id])->first();
        if (file_exists(public_path() . '/' . $gallery->imagePath)) {
            unlink(public_path() . '/' . $gallery->imagePath);
        }
        $gallery->delete();
        $galleries = Gallery::where('user_id', Auth::id())->get();
        return response()->json([
            'count' => count($galleries)
        ]);
    }

    public function get_comments(Request $request)
    {
        $item = Comment::with('user')->where('id', $request->id)->first();
        return response([
            'msg' => $item
        ]);
    }

    public function delete_service(Request $request)
    {
        CategoryUser::where('id', $request->id)->delete();
    }

    public function get_service_profile(Request $request)
    {
        $CategoryUser = CategoryUser::find($request->id);
        return response([
            'msg' => $CategoryUser,
        ]);
    }

    public function get_desks_service(Request $request)
    {
        $item =DesksService::find($request->id);
        return response([
            'msg' => $item,
        ]);
    }

    public function desks_service_remove(Request $request)
    {
        $items =DesksService::where('user_id',Auth::id())->get();
        if (count($items)>1){
            TimingsUser::where(['user_id'=> Auth::id(),'desks_services_id'=>$request->id])->delete();
            CategoryUser::where(['user_id'=> Auth::id(),'desks_services_id'=>$request->id])->delete();
            DesksService::where('id', $request->id)->delete();
            echo 'delete';
        }else{
            echo 'no-count';
        }
    }

    public function report_comment(Request $request)
    {
        Comment::where('id', $request->id)->update(['report' => "YES"]);
    }

    public function get_service_reserve(Request $request)
    {

        if ($request->service) {
            $price = [];
            $time = [];
            foreach ($request->service as $service) {
                $item = CategoryUser::where(['category_id' => $service, 'user_id' => $request->user_id,'desks_services_id'=>$request->DesksService])->first();
                $price[] = $item->price;
                $time[] = $item->time;
            }
            $TotalPrice = array_sum($price);
            $TotalTime = array_sum($time);
        } else {
            $TotalPrice = 0;
            $TotalTime = 0;
        }

        return response([
            'TotalPrice' => number_format($TotalPrice),
            'TotalTime' => $TotalTime
        ]);
    }

    public function get_day_reserve(Request $request)
    {

        $Timing_users=TimingsUser::where(['user_id'=> $request->user_id,'day_name'=>$request->day,'desks_services_id'=>$request->DesksService])->first();
        $timings_id=explode(',',$Timing_users->timings_id);
        $timings_closed_id=explode(',',$Timing_users->closed);
        $Timings = Timing::whereIn('id', $timings_id)->get();
        $show=[];
        $i=1;

        $user=User::find($request->user_id);
        $closed=explode(',',$user->closed);

        if (!in_array($request->day,$closed)) {
            foreach ($Timings as $Timing) {
                $hourMaxNOW = (verta()->format('H') * 60) + verta()->format('i');
                $startTime = explode(':', $Timing->startTime);
                $startTime = $startTime[0] * 60;

                $DayMonthYear = explode('-', $request->DayMonthYear);
                $year = $DayMonthYear['1'];
                $month = $DayMonthYear['2'];
                $day = $DayMonthYear['3'];

                $Reserves = Reserve::where(['hairstylist_id' => $request->user_id, 'year' => $year, 'month' => $month, 'day' => $day, 'dayTitle' => $request->day])->where('status', '!=', 'Cancel')->get();
                $isSet="no";

                if (count($Reserves)) {
                    foreach ($Reserves as $Reserve) {
                        $times = explode(',', $Reserve->times);
                            if (in_array( $Timing->id,$times)) {
                                if ($Reserve->user_id!=Auth::id()){
                                    if ($Reserve->desks_services_id!=$request->DesksService){
                                        $isSet="no";
                                    }else{
                                        $isSet="yes";
                                    }

                                }else{
                                    $isSet="yes";
                                }

                            }

                    }
                }
                $background_color="";
                if ($isSet=="yes" or in_array($Timing->id,$timings_closed_id)){
                    $background_color="background-color: #8787874d;";
                }
                    if (verta()->format('d') == $request->Data_Day_id) {
                        if ($startTime > $hourMaxNOW) {
                            $show1='<label for="Morning' . $Timing->id . '-' . $i . '" style="'.$background_color.'" class="doc-slot-list Morning' . $Timing->id . '-' . $i . ' "><span class="time-right">' . $Timing->startTime . '</span> تا <span class="time-left">' . $Timing->endTime . '</span> </label><input type="checkbox" value="' . $Timing->id . '" id="Morning' . $Timing->id . '-' . $i . '" class="doc-slot-list-input" name="time" style="display: none;" data-id="' . $i . '">';
                            $show2='';
                            if (!in_array($Timing->id,$timings_closed_id)){
                                if ($isSet=="no") {
                                    $show[] = $show1 . $show2;

                                }else{
                                    $show[] = $show1;
                                }
                            }else{
                                $show[] = $show1;
                            }
                            $i++;

                        }
                    } else {
                        $show1= '<label for="Morning' . $Timing->id . '-' . $i . '" style="'.$background_color.'" class="doc-slot-list Morning' . $Timing->id . '-' . $i . ' "><span class="time-right">' . $Timing->startTime . '</span> تا <span class="time-left">' . $Timing->endTime . '</span> </label><input type="checkbox" value="' . $Timing->id . '" id="Morning' . $Timing->id . '-' . $i . '" class="doc-slot-list-input" name="time" style="display: none;" data-id="' . $i . '">';
                        $show2='';
                        if (!in_array($Timing->id,$timings_closed_id)){
                            if ($isSet=="no") {
                                $show[] = $show1 . $show2;

                            }else{
                                $show[] = $show1;
                            }
                        }else{
                            $show[] = $show1;
                        }
                        $i++;
                    }
            }

            return response([
                'msg' => $show,
                'closed' => "OFF"
            ]);
        }else{
            return response([
                'closed'=>"ON"
            ]);
        }

    }

    public function get_time_service(Request $request)
    {
        $services=CategoryUser::whereIn('category_id',$request->service)->where(['user_id'=>$request->user_id,'desks_services_id'=>$request->DesksService])->get();
        $Time=[];
        foreach ($services as $service){
            $Time[]=$service->time;
        }
        return response([
            'msg' => array_sum($Time),
        ]);
    }

    public function Accept_Reserve(Request $request)
    {
        Reserve::where(['id' => $request->id, 'hairstylist_id' => Auth::id()])->update(['status' => 'Accept']);
        $csrf_token = csrf_token();
        return response([
            'token' => "'$csrf_token'"
        ]);
    }


    public function Cancel_Reserve(Request $request)
    {
        $nowUser = User::find(Auth::id());
        if ($nowUser->HairStylist == "YES") {
            $Reserve = Reserve::where(['id' => $request->id, 'hairstylist_id' => Auth::id()])->first();
            $shams = Verta::getGregorian($Reserve->year, $Reserve->month, $Reserve->day);
            $DataNow = verta();
            $DataReserve = verta($shams[0].'-'.$shams[1].'-'.$shams[2].' '.$Reserve->hour_min.':'.$Reserve->minute_min);
            $DiffDays = $DataNow->diffDays($DataReserve);
            $DiffMinutes = $DataNow->diffMinutes($DataReserve);

            if ($DiffDays >= 0 and $DiffMinutes >= -15) {

                $pay = Pay::where(['reserve_id' => $Reserve->id, 'user_id' => $Reserve->user_id, 'hairstylist_id' => Auth::id()])->first();
                $hairstylist = User::find(Auth::id());
                $user = User::find($Reserve->user_id);


                if ($hairstylist->wallet >= $pay->pay_price) {
                    $user->wallet = $user->wallet + $pay->pay_price;
                    $user->save();
                    /* ===================== report=======================*/
                    $report = new Report();
                    $v = new Verta();
                    $factor = 'P-' . $v->year . $v->month . $v->day . $v->second . rand(100, 999) . Auth::id();
                    $report->factor_number = $factor;
                    $report->hairstylist_id = Auth::id();
                    $report->user_id = $Reserve->user_id;
                    $report->price = $pay->pay_price;
                    $report->pay_price = $pay->pay_price;
                    $report->remaining_price = 0;
                    $report->pay_status = "OK";
                    $report->pay_method = "wallet";
                    $report->type = "Refunds";
                    $report->save();
                    /* =====================End report=======================*/
                    $pay->pay_price = $report->pay_price;
                    $pay->pay_status = "Refunds";
                    $pay->save();

                    $hairstylist->wallet = $hairstylist->wallet - $report->pay_price;
                    $hairstylist->save();


                    /*==============SMS================*/
                    /*
                     * عزیز رزرو نوبت، توسط آرایشگاه کنسل شد و پول پرداخت شده، به کیف پول شما واریز شد.
                     * */
                    $username = trim(setting()['username_sms']);
                    $password = trim(setting()['password_sms']);
                    $from = "+983000505";
                    $pattern_code = "2qvzdal2xp";
                    $to = array($user->mobile);
                    $input_data = array("name" => $user->name, "hairStylist" => $hairstylist->nameShop);
                    $url = "https://ippanel.com/patterns/pattern?username=" . $username . "&password=" . urlencode($password) . "&from=$from&to=" . json_encode($to) . "&input_data=" . urlencode(json_encode($input_data)) . "&pattern_code=$pattern_code";
                    $handler = curl_init($url);
                    curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
                    curl_setopt($handler, CURLOPT_POSTFIELDS, $input_data);
                    curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
                    $response = curl_exec($handler);
                    /*==============SMS=============*/


                    Reserve::where(['id' => $request->id, 'hairstylist_id' => Auth::id()])->update(['status' => 'Cancel']);
                    $csrf_token = csrf_token();
                    return response([
                        'token' => "'$csrf_token'"
                    ]);
                } else {
                    echo 'no-wallet';
                }
            } else {
                Reserve::where(['id' => $request->id, 'hairstylist_id' => Auth::id()])->update(['status' => 'Cancel']);
                $hairstylist = User::find(Auth::id());
                $user = User::find($Reserve->user_id);
                /*==============SMS================*/
                /*
                 * عزیز رزرو نوبت شما توسط آرایشگاه کنسل شد.
                 * */
                $username = trim(setting()['username_sms']);
                $password = trim(setting()['password_sms']);
                $from = "+983000505";
                $pattern_code = "uy6trdjy9f";
                $to = array($user->mobile);
                $input_data = array("name" => $user->name, "hairStylist" => $hairstylist->nameShop);
                $url = "https://ippanel.com/patterns/pattern?username=" . $username . "&password=" . urlencode($password) . "&from=$from&to=" . json_encode($to) . "&input_data=" . urlencode(json_encode($input_data)) . "&pattern_code=$pattern_code";
                $handler = curl_init($url);
                curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($handler, CURLOPT_POSTFIELDS, $input_data);
                curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($handler);
                /*==============SMS=============*/

                $csrf_token = csrf_token();
                return response([
                    'token' => "'$csrf_token'"
                ]);
            }
        } elseif ($nowUser->HairStylist == "NO") {
            $Reserve = Reserve::where(['id' => $request->id, 'user_id' => Auth::id()])->first();
            $timing = Timing::find($Reserve->timings_id);
            $shams = Verta::getGregorian($Reserve->year, $Reserve->month, $Reserve->day);
            $DataNow = verta();
            $DataReserve = verta($shams[0].'-'.$shams[1].'-'.$shams[2].' '.$Reserve->hour_min.':'.$Reserve->minute_min);
            $DiffDays = $DataNow->diffDays($DataReserve);
            $DiffMinutes = $DataNow->diffMinutes($DataReserve);

            if ($DiffDays >= 0 and $DiffMinutes >= 120) {

                /*==============دارای زمان 2 ساعت به بالا=============*/
                $pay = Pay::where(['reserve_id' => $Reserve->id, 'user_id' => Auth::id(), 'hairstylist_id' => $Reserve->hairstylist_id])->first();
                $hairstylist = User::find($Reserve->hairstylist_id);
                $user = User::find(Auth::id());

                if ($hairstylist->wallet >= $pay->pay_price) {
                    $user->wallet = $user->wallet + $pay->pay_price;
                    $user->save();
                    /* ===================== report=======================*/
                    $report = new Report();
                    $v = new Verta();
                    $factor = 'P-' . $v->year . $v->month . $v->day . $v->second . rand(100, 999) . Auth::id();
                    $report->factor_number = $factor;
                    $report->hairstylist_id = $Reserve->hairstylist_id;
                    $report->user_id = Auth::id();
                    $report->price = $pay->pay_price;
                    $report->pay_price = $pay->pay_price;
                    $report->remaining_price = 0;
                    $report->pay_status = "OK";
                    $report->pay_method = "wallet";
                    $report->type = "Refunds";
                    $report->save();
                    /* =====================End report=======================*/
                    $pay->pay_price = $report->pay_price;
                    $pay->pay_status = "Refunds";
                    $pay->save();

                    $hairstylist->wallet = $hairstylist->wallet - $report->pay_price;
                    $hairstylist->save();

                    /*==============SMS================*/
                    /*
                     * عزیز رزرو نوبت توسط شما کنسل شد و پول پرداخت شده، به کیف پول شما واریز شد.
                     * */
                    $username = trim(setting()['username_sms']);
                    $password = trim(setting()['password_sms']);
                    $from = "+983000505";
                    $pattern_code = "utk8z9ipbc";
                    $to = array($user->mobile);
                    $input_data = array("name" => $user->name);
                    $url = "https://ippanel.com/patterns/pattern?username=" . $username . "&password=" . urlencode($password) . "&from=$from&to=" . json_encode($to) . "&input_data=" . urlencode(json_encode($input_data)) . "&pattern_code=$pattern_code";
                    $handler = curl_init($url);
                    curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
                    curl_setopt($handler, CURLOPT_POSTFIELDS, $input_data);
                    curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
                    $response = curl_exec($handler);
                    /*==============SMS=============*/

                    Reserve::where(['id' => $request->id, 'hairstylist_id' => $Reserve->hairstylist_id])->update(['status' => 'Cancel']);
                    $csrf_token = csrf_token();
                    return response([
                        'token' => "'$csrf_token'"
                    ]);
                } else {
                    echo 'no-cancel';
                }
            } else {
                /*==============نداشتن زمان 2 ساعت به بالا=============*/

                $pay = Pay::where(['reserve_id' => $Reserve->id, 'user_id' => Auth::id(), 'hairstylist_id' => $Reserve->hairstylist_id])->first();
                $hairstylist = User::find($Reserve->hairstylist_id);
                $user = User::find(Auth::id());


                //$user->wallet = $user->wallet + $pay->pay_price;
                //$user->save();
                /* ===================== report=======================*/
                $report = new Report();
                $v = new Verta();
                $factor = 'P-' . $v->year . $v->month . $v->day . $v->second . rand(100, 999) . Auth::id();
                $report->factor_number = $factor;
                $report->hairstylist_id = $Reserve->hairstylist_id;
                $report->user_id = Auth::id();
                $report->price = $pay->pay_price;
                $report->pay_price = 0;
                $report->remaining_price = 0;
                $report->pay_status = "Refunds";
                $report->pay_method = "wallet";
                $report->type = "Refunds";
                $report->save();
                /* =====================End report=======================*/

                $pay->pay_status = "Refunds";
                $pay->save();


                /*==============SMS================*/
                /*
                 * عزیز رزرو نوبت، توسط شما کنسل شد.
                 * */
                $username = trim(setting()['username_sms']);
                $password = trim(setting()['password_sms']);
                $from = "+983000505";
                $pattern_code = "7l4yyw9ohh";
                $to = array($user->mobile);
                $input_data = array("name" => $user->name);
                $url = "https://ippanel.com/patterns/pattern?username=" . $username . "&password=" . urlencode($password) . "&from=$from&to=" . json_encode($to) . "&input_data=" . urlencode(json_encode($input_data)) . "&pattern_code=$pattern_code";
                $handler = curl_init($url);
                curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($handler, CURLOPT_POSTFIELDS, $input_data);
                curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($handler);
                /*==============SMS=============*/

                //$hairstylist->wallet = $hairstylist->wallet - $report->pay_price;
                //$hairstylist->save();
                Reserve::where(['id' => $request->id, 'hairstylist_id' => $Reserve->hairstylist_id])->update(['status' => 'Cancel']);
                $csrf_token = csrf_token();
                return response([
                    'token' => "'$csrf_token'"
                ]);

            }

        }


    }

    public function Delete_Reserve(Request $request)
    {
        $Reserve = Reserve::where(['id' => $request->id, 'hairstylist_id' => Auth::id()])->first();
        if ($Reserve->status == "Waiting") {
            $pay = Pay::where(['reserve_id' => $Reserve->id, 'user_id' => $Reserve->user_id, 'hairstylist_id' => Auth::id()])->first();
            $hairstylist = User::find(Auth::id());
            $user = User::find($Reserve->user_id);
            if ($hairstylist->wallet >= $pay->pay_price) {
                $user->wallet = $user->wallet + $pay->pay_price;
                $user->save();

                /* ===================== report=======================*/
                $report = new Report();
                $v = new Verta();
                $factor = 'P-' . $v->year . $v->month . $v->day . $v->second . rand(100, 999) . Auth::id();
                $report->factor_number = $factor;
                $report->hairstylist_id = Auth::id();
                $report->user_id = $Reserve->user_id;
                $report->price = $pay->pay_price;
                $report->pay_price = $pay->pay_price;
                $report->remaining_price = 0;
                $report->pay_status = "OK";
                $report->pay_method = "wallet";
                $report->type = "Refunds";
                $report->save();
                /* =====================End report=======================*/
                $pay->pay_price = $report->pay_price;
                $pay->pay_status = "Refunds";
                $pay->save();

                $hairstylist->wallet = $hairstylist->wallet - $report->pay_price;
                $hairstylist->save();


                /*==============SMS================*/
                /*
                 * عزیز رزرو نوبت، توسط آرایشگاه  کنسل شد و پول پرداخت شده، به کیف پول شما واریز شد.
                 * */
                $username = trim(setting()['username_sms']);
                $password = trim(setting()['password_sms']);
                $from = "+983000505";
                $pattern_code = "2qvzdal2xp";
                $to = array($user->mobile);
                $input_data = array("name" => $user->name, "hairStylist" => $hairstylist->nameShop);
                $url = "https://ippanel.com/patterns/pattern?username=" . $username . "&password=" . urlencode($password) . "&from=$from&to=" . json_encode($to) . "&input_data=" . urlencode(json_encode($input_data)) . "&pattern_code=$pattern_code";
                $handler = curl_init($url);
                curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($handler, CURLOPT_POSTFIELDS, $input_data);
                curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($handler);
                /*==============SMS=============*/

                $Reserve->delete();
                $csrf_token = csrf_token();
                return response([
                    'token' => "'$csrf_token'"
                ]);
            } else {
                echo 'no-wallet';
            }
        } elseif ($Reserve->status == "Accept") {
           // $timing = Timing::where('user_id',$Reserve->hairstylist_id)->first();
            $shams = Verta::getGregorian($Reserve->year, $Reserve->month, $Reserve->day);
            $DataNow = verta();
            $DataReserve = verta($shams[0].'-'.$shams[1].'-'.$shams[2].' '.$Reserve->hour_min.':'.$Reserve->minute_min);
            $DiffDays = $DataNow->diffDays($DataReserve);
            $DiffMinutes = $DataNow->diffMinutes($DataReserve);

            if ($DiffDays >= 0 and $DiffMinutes >= -15) {
                $pay = Pay::where(['reserve_id' => $Reserve->id, 'user_id' => $Reserve->user_id, 'hairstylist_id' => Auth::id()])->first();
                $hairstylist = User::find(Auth::id());
                $user = User::find($Reserve->user_id);
                if ($hairstylist->wallet >= $pay->pay_price) {
                    $user->wallet = $user->wallet + $pay->pay_price;
                    $user->save();

                    /* ===================== report=======================*/
                    $report = new Report();
                    $v = new Verta();
                    $factor = 'P-' . $v->year . $v->month . $v->day . $v->second . rand(100, 999) . Auth::id();
                    $report->factor_number = $factor;
                    $report->hairstylist_id = Auth::id();
                    $report->user_id = $Reserve->user_id;
                    $report->price = $pay->pay_price;
                    $report->pay_price = $pay->pay_price;
                    $report->remaining_price = 0;
                    $report->pay_status = "OK";
                    $report->pay_method = "wallet";
                    $report->type = "Refunds";
                    $report->save();
                    /* =====================End report=======================*/
                    $pay->pay_price = $report->pay_price;
                    $pay->pay_status = "Refunds";
                    $pay->save();

                    $hairstylist->wallet = $hairstylist->wallet - $report->pay_price;
                    $hairstylist->save();


                    /*==============SMS================*/
                    /*
                     * عزیز رزرو نوبت، توسط آرایشگاه کنسل شد و پول پرداخت شده، به کیف پول شما واریز شد.
                     * */
                    $username = trim(setting()['username_sms']);
                    $password = trim(setting()['password_sms']);
                    $from = "+983000505";
                    $pattern_code = "2qvzdal2xp";
                    $to = array($user->mobile);
                    $input_data = array("name" => $user->name, "hairStylist" => $hairstylist->nameShop);
                    $url = "https://ippanel.com/patterns/pattern?username=" . $username . "&password=" . urlencode($password) . "&from=$from&to=" . json_encode($to) . "&input_data=" . urlencode(json_encode($input_data)) . "&pattern_code=$pattern_code";
                    $handler = curl_init($url);
                    curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
                    curl_setopt($handler, CURLOPT_POSTFIELDS, $input_data);
                    curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
                    $response = curl_exec($handler);
                    /*==============SMS=============*/


                    $Reserve->delete();
                    $csrf_token = csrf_token();
                    return response([
                        'token' => "'$csrf_token'"
                    ]);
                } else {
                    echo 'no-wallet';
                }
            }
        } else {
            $Reserve->delete();
            $csrf_token = csrf_token();
            return response([
                'token' => "'$csrf_token'"
            ]);
        }

    }

    public function get_wallet_hairstylist(Request $request)
    {
        $user = User::find(Auth::id());
        if ($user->wallet < $request->value) {
            echo 'no-wallet';
        }
    }

    public function block_Unblock_user(Request $request)
    {
        $block = BlockList::where(['hairstylist_id' => Auth::id(), 'user_id' => $request->user_id])->first();
        if ($request->block == "block") {
            if (!$block) {
                $block = new BlockList();
                $block->user_id = $request->user_id;
                $block->hairstylist_id = Auth::id();
                $block->save();
            }
            echo 'block';
        } elseif ($request->block == "Unblock") {
            if ($block) {
                $block->delete();
                echo 'Unblock';
            } else {
                echo 'no-Unblock';
            }

        }
    }

    public function get_package(Request $request)
    {
        return response([
            'item'=>Package::find($request->id)
        ]);
    }

    public function get_desks_services_reserve(Request $request)
    {
        $items=CategoryUser::with('category')->where(['user_id'=>$request->user_id,'desks_services_id'=>$request->value])->get();
       if (count($items)){
           foreach ($items as $item){?>
               <tr>
                   <td>
                       <label class="custom_check">
                           <input type="checkbox" onchange="get_price_service()" name="service[]" class="service" id="check_<?= $item->id ?>" value="<?= $item->category->id ?>">
                           <span class="checkmark"></span>
                       </label>
                   </td>
                   <td><?= $item->category->title ?></td>
                   <td><?= $item->time ?> دقیقه </td>
                   <td><?= number_format($item->price) ?> تومان  </td>
                   <td></td>

               </tr>
           <?php }
       }else{?>
           <tr>
               <td colspan="5" style="text-align: center;font-size: 20px;">
                   <span class="badge badge-pill bg-warning-light"> سرویسی یافت نشد! </span>
               </td>
           </tr>
       <?php }

    }

    public function get_wallet_reserve(Request $request)
    {
        $user=User::find(Auth::id());
        $wallet=number_format($user->wallet);
        $countReserve = Reserve::where(['hairstylist_id' => Auth::id(), 'status' => 'Accept'])->get();
        $countReserveToday = Reserve::where(['hairstylist_id' => Auth::id(), 'status' => 'Accept'])->whereDate('created_at', '=', date('Y-m-d'))->get();

        if ($request->dashboard){
            $items = Reserve::with('user', 'timings')->where('hairstylist_id', Auth::id())->where('status', '!=', 'NOK')->orderBy('id', 'desc')->take(10)->get();
            $row=1;
            foreach($items as $item){
                if($item->user){
                    $pay=Pay::where(['reserve_id'=>$item->id,'user_id'=>$item->user_id,'hairstylist_id'=>Auth::id()])->first();
                    $blocked=BlockList::with('hairstylist', 'user')->where(['hairstylist_id'=> Auth::id(),'user_id'=>@$item->user->id])->first();
                    ?>
                    <tr class="item item<?= $item->user->id?>">
                        <td>
                            <h2 class="table-avatar">
                                <a class="avatar avatar-sm mr-2">
                                    <?php if($item->user->avatar==""){?>
                                        <img style="width: 45px;" class="avatar-img rounded-circle" src="<?=asset('assets/profile.png')?>" alt="<?=$item->user->name?>">
                                    <?php }else{ ?>
                                        <img  class="avatar-img rounded-circle" src="<?=asset($item->user->avatar)?>" alt="<?=$item->user->name?>">
                                    <?php } ?>
                                </a>
                                <a><?=$item->user->name?> <span>کدکاربری: <span><?= $item->user->username?></span> </span></a>
                            </h2>
                        </td>
                        <td><?=$item->year.'/'.$item->month.'/'.$item->day ?> <span class="d-block text-info"><?=$item->hour_min.':'.$item->minute_min.' تا '.$item->hour_max.':'.$item->minute_max?></span></td>
                        <?php $services=explode(',',$item->service); ?>
                        <td>
                            <div class="clinic-services">
                                <?php foreach($services as $service){
                                    $service_item=\App\Models\Category::find($service); ?>
                                    <span><?=$service_item->title ?></span>
                                <?php } ?>
                            </div>
                        </td>
                        <td class="text-center"> <?=number_format($item->price)?> تومان </td>
                        <td class="text-center"> <?=number_format(@$pay->pay_price)?> تومان </td>
                        <td class="text-center"> <?=number_format(@$pay->remaining_price)?> تومان </td>
                        <td class="text-center status">
                            <?php if($item->status=="Accept"){?>
                                <span class="badge badge-pill bg-success-light"> تأیید </span>
                            <?php }elseif($item->status=="Cancel"){?>
                                <span class="badge badge-pill bg-danger-light">کنسل</span>
                            <?php }elseif($item->status=="Waiting"){?>
                                <span class="badge badge-pill bg-warning-light"> در انتظار </span>
                            <?php } ?>
                            <?php if($blocked){?>
                                <span STYLE="display: block" class="badge badge-pill bg-danger-light">کاربر مسدود</span>
                            <?php } ?>
                        </td>
                        <td class="text-center">
                            <div class="table-action">
                                <?php
                                $timing=Timing::find($item->timings_id);
                                $shams=Verta::getGregorian($item->year,$item->month,$item->day);
                                $DataNow=verta();
                                $DataReserve = verta($shams[0].'-'.$shams[1].'-'.$shams[2].' '.$item->hour_min.':'.$item->minute_min);
                                $DiffDays=$DataNow->diffDays($DataReserve);
                                $DiffMinutes=$DataNow->diffMinutes($DataReserve);
                                ?>
                                <?php if($DiffDays>=0 and $DiffMinutes>=-15){?>
                                    <div class="AcceptCancel" style="display: inline-block">
                                        <?php if($item->status=="Waiting"){?>
                                            <a href="javascript:void(0);" onclick="AcceptReserve(this,<?=$item->id?>,'<?= csrf_token() ?>')" class="btn btn-sm bg-success-light Waiting">
                                                <i class="fas fa-check"></i> قبول
                                            </a>
                                            <a href="javascript:void(0);" onclick="CancelReserve(this,<?=$item->id?>,'<?= csrf_token() ?>')"  class="btn btn-sm bg-danger-light Waiting">
                                                <i class="fas fa-times"></i> رد
                                            </a>
                                        <?php } ?>
                                        <?php if($item->status=="Accept"){?>
                                            <a href="javascript:void(0);" onclick="CancelReserve(this,<?=$item->id?>,'<?= csrf_token() ?>')"  class="btn btn-sm bg-danger-light">
                                                <i class="fas fa-times"></i> کنسل
                                            </a>
                                        <?php }?>

                                    </div>
                                <?php } ?>
                                <?php if(!$blocked){?>
                                    <a href="javascript:void(0);" onclick="BlockUser(this,<?=$item->user->id?>,'block','<?= csrf_token() ?>')" class="btn btn-sm bg-danger-light block">
                                        <i class="fas fa-user-lock"></i> مسدود
                                    </a>
                                <?php } ?>
                                <a href="javascript:void(0);" onclick="DeleteReserve(this,<?=$item->id?>,'<?= csrf_token() ?>')" class="btn btn-sm bg-danger-light">
                                    <i class="far fa-trash-alt"></i> حذف
                                </a>

                            </div>

                        </td>

                    </tr>
                    <?php
                }
                if ($row==1){?>
                    <tr style="display:none;">
                        <td colspan="8">
                            <input name="wallet" value="<?= $wallet ?>">
                            <input name="countReserve" value="<?= count($countReserve) ?>">
                            <input name="countReserveToday" value="<?= count($countReserveToday) ?>">

                        </td>
                    </tr>
                <?php }
                $row++;
            }
        }else{
            return response([
                'wallet'=>$wallet
            ]);
        }


    }

    public function get_day_reserved(Request $request)
    {

        $Timing_users=TimingsUser::where(['user_id'=> Auth::id(),'day_name'=>$request->day,'desks_services_id'=>$request->DesksService])->first();
        $timings_id=explode(',',$Timing_users->timings_id);
        $timings_closed_id=explode(',',$Timing_users->closed);
        $Timings = Timing::whereIn('id', $timings_id)->get();
        $show=[];
        $i=1;

        $user=User::find(Auth::id());
        $closed=explode(',',$user->closed);

        if (!in_array($request->day,$closed)) {
            foreach ($Timings as $Timing) {
                $hourMaxNOW = (verta()->format('H') * 60) + verta()->format('i');
                $startTime = explode(':', $Timing->startTime);
                $startTime = $startTime[0] * 60;

                $DayMonthYear = explode('-', $request->DayMonthYear);
                $year = $DayMonthYear['1'];
                $month = $DayMonthYear['2'];
                $day = $DayMonthYear['3'];

                $Reserves = Reserve::where(['hairstylist_id' => Auth::id(), 'year' => $year, 'month' => $month, 'day' => $day, 'dayTitle' => $request->day])->where('status', '!=', 'Cancel')->get();
                $isSet="no";

                if (count($Reserves)) {
                    foreach ($Reserves as $Reserve) {
                        $times = explode(',', $Reserve->times);
                        if (in_array( $Timing->id,$times)) {
                            if ($Reserve->user_id!=Auth::id()){
                                if ($Reserve->desks_services_id!=$request->DesksService){
                                    $isSet="no";
                                }else{
                                    $isSet="yes";
                                }

                            }else{
                                $isSet="yes";
                            }

                        }

                    }
                }
                $background_color="";
                if ($isSet=="yes" or in_array($Timing->id,$timings_closed_id)){
                    $background_color="background-color: #8787874d;";
                }
                if (verta()->format('d') == $request->Data_Day_id) {
                    if ($startTime > $hourMaxNOW) {
                        $show1='<label for="Morning' . $Timing->id . '-' . $i . '" style="'.$background_color.'" class="doc-slot-list Morning' . $Timing->id . '-' . $i . ' "><span class="time-right">' . $Timing->startTime . '</span> تا <span class="time-left">' . $Timing->endTime . '</span> </label><input type="checkbox" value="' . $Timing->id . '" id="Morning' . $Timing->id . '-' . $i . '" class="doc-slot-list-input" name="time" style="display: none;" data-id="' . $i . '">';
                        $show2='';
                        if (!in_array($Timing->id,$timings_closed_id)){
                            if ($isSet=="no") {
                                $show[] = $show1 . $show2;

                            }else{
                                $show[] = $show1;
                            }
                        }else{
                            $show[] = $show1;
                        }
                        $i++;

                    }
                } else {
                    $show1= '<label for="Morning' . $Timing->id . '-' . $i . '" style="'.$background_color.'" class="doc-slot-list Morning' . $Timing->id . '-' . $i . ' "><span class="time-right">' . $Timing->startTime . '</span> تا <span class="time-left">' . $Timing->endTime . '</span> </label><input type="checkbox" value="' . $Timing->id . '" id="Morning' . $Timing->id . '-' . $i . '" class="doc-slot-list-input" name="time" style="display: none;" data-id="' . $i . '">';
                    $show2='';
                    if (!in_array($Timing->id,$timings_closed_id)){
                        if ($isSet=="no") {
                            $show[] = $show1 . $show2;

                        }else{
                            $show[] = $show1;
                        }
                    }else{
                        $show[] = $show1;
                    }
                    $i++;
                }
            }

            return response([
                'msg' => $show,
                'closed' => "OFF"
            ]);
        }else{
            return response([
                'closed'=>"ON"
            ]);
        }
    }
}
