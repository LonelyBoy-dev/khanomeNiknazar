<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Front\SuspendedUser;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Timing;
use App\Models\TimingsUser;
use App\Models\DesksService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use function Illuminate\Support\Facades\File;

class RegisterHairStylistController extends Controller
{
    public function index()
    {
        if (!Auth::user()){
            session()->forget('UserHairStylistInfo');
            $title = "ثبت نام آرایشگر | " . setting()['title'];
            $seo_title = "ثبت نام آرایشگر," . setting()['seo_title'];
            $seo_content = "ثبت نام آرایشگر," . setting()['seo_content'];
            return view('auth.registerHairStylist', compact(['title', 'seo_title', 'seo_content']));
        }else{
            return redirect('/profile');
        }
    }

    public function register(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|string|max:255|min:3',
            'NationalCode' => 'required|digits:10|unique:users',
            'email' => 'nullable|email|string|unique:users',
            'nameShop' => 'required|max:255|min:3|string',
            'mobile' => 'required|regex:/(09)[0-9]{9}/|digits:11|unique:users',
            'tell' => 'nullable|digits:11',
            'password' => 'required|min:6|confirmed',
            'Type_hairdresser' => 'required',
            'NationalCardPhoto' => 'required|image|mimes:jpeg,png,jpg|max:3584',
            'Businesslicense' => 'nullable|image|mimes:jpeg,png,jpg|max:3584',
            'ShopPhotos' => 'nullable|image|mimes:jpeg,png,jpg|max:3584',
            'shabaNumber' => 'required|numeric',
            'accountNumber' => 'required|numeric',
            'ostan' => 'required',
            'city' => 'required',
            'address' => 'required|min:10',
        ],
            [
                'name.required' => 'نام و نام خانوادگی را وارد کنید',
                'name.min' => 'حدقل 3 کاراکتر',
                'NationalCode.required' => 'کد ملی را وارد کنید',
                'NationalCode.unique' => 'کد ملی متعلق به شخص دیگری است',
                'NationalCode.digits' => 'کد ملی صحیح نمی باشد',
                'mobile.required' => 'شماره موبایل را وارد کنید',
                'mobile.regex' => 'شماره موبایل نامعتبر است',
                'mobile.digits' => 'شماره موبایل نامعتبر است',
                'mobile.unique' => 'شماره موبایل از قبل موجود است',
                'national_code.min' => 'کد ملی نامعتبر است',
                'national_code.max' => 'کد ملی نامعتبر است',

                'NationalCardPhoto.required' => 'تصویر کارت ملی خود را آپلود کنید',
                'NationalCardPhoto.image' => 'تصویر شما باید فرمت jpg,png باشد',
                'NationalCardPhoto.mimes' => 'تصویر شما باید فرمت jpg,png باشد',
                'NationalCardPhoto.max' => 'حجم تصویر بیشتر از 3.5 مگ می باشد',

                'Businesslicense.required' => 'تصویر گواهی مهارت خود را آپلود کنید',
                'Businesslicense.image' => 'تصویر شما باید فرمت jpg,png باشد',
                'Businesslicense.mimes' => 'تصویر شما باید فرمت jpg,png باشد',
                'Businesslicense.max' => 'حجم تصویر بیشتر از 3.5 مگ می باشد',

                'ShopPhotos.required' => 'تصویر نمایی از مغازه خود را آپلود کنید',
                'ShopPhotos.image' => 'تصویر شما باید فرمت jpg,png باشد',
                'ShopPhotos.mimes' => 'تصویر شما باید فرمت jpg,png باشد',
                'ShopPhotos.max' => 'حجم تصویر بیشتر از 3.5 مگ می باشد',

                'location.required' => 'موقعیت مکانی خود را انتخاب کنید',
                'address.required' => 'آدرس را وارد کنید',
                'address.min' => 'حدقل 10 کاراکتر',
                'nameShop.required' => 'نام مغازه را وارد کنید',
                'nameShop.min' => 'حدقل 3 کاراکتر',
                'tell.digits' => 'شماره ثابت 11 رقم می باشد',
                'accountNumber.required' => 'شماره حساب را وارد کنید',
                'accountNumber.numeric' => 'فقط از اعداد استفاده کنید',
                'shabaNumber.required' => 'شماره شباء را وارد کنید',
                'shabaNumber.numeric' => 'فقط از اعداد استفاده کنید',
                'email.required' => 'ایمیل را وارد کنید',
                'email.email' => 'ایمیل نامعتبر است',
                'email.unique' => 'ایمیل از قبل موجود است',

                'ostan.required' => 'استان خود را انتخاب کنید',
                'city.required' => 'شهر خود را انتخاب کنید',

                'Type_hairdresser.required' => 'نوع آرایشگاه را انتخاب کنید',


                'guild_id.required' => 'شماره شناسه صنفی را وارد کنید',

                'password.required' => 'پسورد را وارد کنید',
                'password.min' => 'حداقل پسورد 6 کاراکتر است',
                'password.confirmed' => ' رمز ورود و تکرار رمز ورود یکسان نیست',
            ]);
        $user = new SuspendedUser();
        $user->name = $request->name;
        $user->NationalCode = $request->NationalCode;
        $user->email = $request->email;
        $user->nameShop = $request->nameShop;
        $user->mobile = $request->mobile;
        $user->tell = $request->tell;
        $user->password = Hash::make($request->password);
        $user->accountNumber = $request->accountNumber;
        $user->shabaNumber = $request->shabaNumber;
        $user->Type_hairdresser = $request->Type_hairdresser;
        $user->address = $request->address;
        $user->ostan_id = $request->ostan_id;
        $user->city_id = $request->city_id;
        $user->ostan = $request->ostan;
        $user->city = $request->city;
        $user->location = $request->location;
        $user->guild_id = $request->guild_id;
        $code = rand(10000, 99999);
        $user->verifire_code = $code;
        $username = trim(setting()['username_sms']);
        $password = trim(setting()['password_sms']);
        $from = "+983000505";
        $pattern_code = "ybnldnpo73";
        $to = array($request->mobile);
        $input_data = array("name" => $request->name, "verification-code" => $code);
        $url = "https://ippanel.com/patterns/pattern?username=" . $username . "&password=" . urlencode($password) . "&from=$from&to=" . json_encode($to) . "&input_data=" . urlencode(json_encode($input_data)) . "&pattern_code=$pattern_code";
        $handler = curl_init($url);
        curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($handler, CURLOPT_POSTFIELDS, $input_data);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($handler);
        if ($response) {


            //=====================image 1 ===============
            $NationalCardPhoto = $request->file('NationalCardPhoto');
            $NationalCardPhotoName = rand(1, 99999) . time() . '_' . $NationalCardPhoto->getClientOriginalName();
            $image = Image::make($NationalCardPhoto);
            if (!is_dir('FileUploader/SuspendedUser/')) {
                mkdir("FileUploader/SuspendedUser/");
            }
            $image->save('FileUploader/SuspendedUser/' . $NationalCardPhotoName);
            $user->NationalCardPhoto_name = $NationalCardPhoto->getClientOriginalName();
            $user->NationalCardPhoto = "FileUploader/SuspendedUser/" . $NationalCardPhotoName;

            //=====================image 2 ===============

            $Businesslicense = $request->file('Businesslicense');
            if ($Businesslicense){
                $BusinesslicenseName = rand(1, 99999) . time() . '_' . $Businesslicense->getClientOriginalName();
                $image = Image::make($Businesslicense);
                if (!is_dir('FileUploader/SuspendedUser/')) {
                    mkdir("FileUploader/SuspendedUser/");
                }
                $image->save('FileUploader/SuspendedUser/' . $BusinesslicenseName);
                $user->Businesslicense_name = $Businesslicense->getClientOriginalName();
                $user->Businesslicense = "FileUploader/SuspendedUser/" . $BusinesslicenseName;
            }

            //=====================image 3 ===============
            $ShopPhotos = $request->file('ShopPhotos');
            if ($ShopPhotos){
                $ShopPhotosName = rand(1, 99999) . time() . '_' . $ShopPhotos->getClientOriginalName();
                $image = Image::make($ShopPhotos);
                if (!is_dir('FileUploader/SuspendedUser/')) {
                    mkdir("FileUploader/SuspendedUser/");
                }
                $image->save('FileUploader/SuspendedUser/' . $ShopPhotosName);
                $user->ShopPhotos_name = $ShopPhotos->getClientOriginalName();
                $user->ShopPhotos = "FileUploader/SuspendedUser/" . $ShopPhotosName;
            }

            $user->save();


            $data = ['id' => $user->id, 'mobile' => $request->mobile, 'code' => $user->verifire_code];
            session()->put('UserHairStylistInfo', $data);
            return redirect('/register/hairStylist/ConfirmMobile');
        } else {
            session()->put('error', 'مشکل در ارسال پیامک');
            return redirect()->back();
        }
    }

    public function ConfirmMobile()
    {
        $title = "تائید شماره موبایل | " . setting()['title'];
        $seo_title = "تائید شماره موبایل," . setting()['seo_title'];
        $seo_content = "تائید شماره موبایل," . setting()['seo_content'];
        return view('auth.ConfirmMobile', compact(['title', 'seo_title', 'seo_content']));
    }

    public function checkCode(Request $request)
    {
        $SuspendedUser = SuspendedUser::where(['id' => session('UserHairStylistInfo')['id'], 'mobile' => session('UserHairStylistInfo')['mobile']])->first();
        $code = str_replace(' ', '', $request->code);
        if ($SuspendedUser) {
            if ($SuspendedUser->verifire_code == $code) {
                $user = new User();
                $user->name = $SuspendedUser->name;
                $user->NationalCode = $SuspendedUser->NationalCode;
                $user->email = $SuspendedUser->email;
                $user->nameShop = $SuspendedUser->nameShop;
                $user->mobile = $SuspendedUser->mobile;
                $user->tell = $SuspendedUser->tell;
                $user->password = $SuspendedUser->password;
                $user->accountNumber = $SuspendedUser->accountNumber;
                $user->shabaNumber = $SuspendedUser->shabaNumber;
                $user->address = $SuspendedUser->address;
                $user->location = $SuspendedUser->location;
                $user->ostan_id = $SuspendedUser->ostan_id;
                $user->city_id = $SuspendedUser->city_id;
                $user->ostan = $SuspendedUser->ostan;
                $user->city = $SuspendedUser->city;
                $user->guild_id = $SuspendedUser->guild_id;
                $user->Type_hairdresser = $SuspendedUser->Type_hairdresser;
                $user->status = "ACTIVE";
                $user->verifire = "NO";
                $user->HairStylist = "YES";


                $user->save();


                $SetUsername=User::find($user->id);
                $all_user = User::all();
                $countUser = 99999 - count($all_user);
                $countUser = explode('9', $countUser);
                if ($countUser[0] != "") {
                    $count_user = $all_user;
                } elseif ($countUser[1] != "") {
                    $count_user = "0" . count($all_user);
                } elseif ($countUser[2] != "") {
                    $count_user = "00" . count($all_user);
                } elseif ($countUser[3] != "") {
                    $count_user = "000" . count($all_user);
                } else {
                    $count_user = "0000" . count($all_user);
                }
                if ($SetUsername->Type_hairdresser == "M") {
                    $Type_hairdresser = 1;
                } else {
                    $Type_hairdresser = 2;
                }
                $SetUsername->username = $Type_hairdresser . $SetUsername->ostan_id . $count_user.$user->id;
                $SetUsername->save();

                $user2 = User::find($user->id);
                if (!is_dir('FileUploader/users')) {
                    mkdir("FileUploader/users");
                }
                if (!is_dir('FileUploader/users/' . $user->id)) {
                    mkdir("FileUploader/users/" . $user->id);
                }
                if (!is_dir('FileUploader/users/' . $user->id . '/documents')) {
                    mkdir("FileUploader/users/" . $user->id . '/documents');
                }
                $user2->NationalCardPhoto = "FileUploader/users/" . $user->id . '/documents/' . $SuspendedUser->NationalCardPhoto_name;
                rename($SuspendedUser->NationalCardPhoto, "FileUploader/users/" . $user->id . '/documents/' . $SuspendedUser->NationalCardPhoto_name);

                if ($user2->Businesslicense){
                    $user2->Businesslicense = "FileUploader/users/" . $user->id . '/documents/' . $SuspendedUser->Businesslicense_name;
                    rename($SuspendedUser->Businesslicense, "FileUploader/users/" . $user->id . '/documents/' . $SuspendedUser->Businesslicense_name);
                }

                if ($user2->ShopPhotos){
                    $user2->ShopPhotos = "FileUploader/users/" . $user->id . '/documents/' . $SuspendedUser->ShopPhotos_name;
                    rename($SuspendedUser->ShopPhotos, "FileUploader/users/" . $user->id . '/documents/' . $SuspendedUser->ShopPhotos_name);
                }

                $user2->save();
                //================= Add Ticket
                $item = new Ticket();
                $item->title = "درخواست آرایشگر جدید";
                $Type_hairdresser = "آرایشگاه آقایان";
                if ($user->Type_hairdresser == "F") {
                    $Type_hairdresser = "آرایشگاه بانوان";
                }
                $item->message = "
                <p>نام و نام خانوادگی : " . $user->name . "
                <br>کد ملی : " . $user->NationalCode . "
                <br>شماره موبایل : " . $user->mobile . "
                <br>ایمیل : " . $user->email . "
                <br>نام مغازه (محل کار) : " . $user->nameShop . "
                <br>شماره ثابت : " . $user->tell . "
                <br>شماره حساب : " . $user->accountNumber . "
                <br>شماره شبا : " . $user->shabaNumber . "
                <br>نوع آرایشگاه : " . $Type_hairdresser . "
                <br>شماره شناسه صنفی : " . $user->guild_id . "
                <br>استان : " . $user->ostan . "
                <br>شهر : " . $user->city . "
                <br>آدرس : " . $user->address . "
                </p>
                ";
                $item->file = $user2->NationalCardPhoto;
                $item->file2 = $user2->Businesslicense;
                $item->file3 = $user2->ShopPhotos;
                $item->user_id = $user->id;
                $item->new_hairdresser = "YES";
                $item->save();


                /*=================================Timing==============================*/
                $DesksService=new DesksService();
                $DesksService->title="میز شماره یک";
                $DesksService->description="";
                $DesksService->user_id=$user->id;
                $DesksService->save();

                $timings=Timing::all();
                $timings_id=[];
                foreach ($timings as $timing){
                    $timings_id[]=$timing->id;
                }
                $timings_id=implode(',',$timings_id);
                $timings_user=new TimingsUser();
                $timings_user->desks_services_id=$DesksService->id;
                $timings_user->user_id=$user->id;
                $timings_user->timings_id=$timings_id;
                $timings_user->day_name='شنبه';
                $timings_user->save();


                $timings_user=new TimingsUser();
                $timings_user->user_id=$user->id;
                $timings_user->desks_services_id=$DesksService->id;
                $timings_user->timings_id=$timings_id;
                $timings_user->day_name='یکشنبه';
                $timings_user->save();


                $timings_user=new TimingsUser();
                $timings_user->user_id=$user->id;
                $timings_user->desks_services_id=$DesksService->id;
                $timings_user->timings_id=$timings_id;
                $timings_user->day_name='دوشنبه';
                $timings_user->save();


                $timings_user=new TimingsUser();
                $timings_user->user_id=$user->id;
                $timings_user->desks_services_id=$DesksService->id;
                $timings_user->timings_id=$timings_id;
                $timings_user->day_name='سه شنبه';
                $timings_user->save();


                $timings_user=new TimingsUser();
                $timings_user->user_id=$user->id;
                $timings_user->desks_services_id=$DesksService->id;
                $timings_user->timings_id=$timings_id;
                $timings_user->day_name='چهارشنبه';
                $timings_user->save();


                $timings_user=new TimingsUser();
                $timings_user->user_id=$user->id;
                $timings_user->desks_services_id=$DesksService->id;
                $timings_user->timings_id=$timings_id;
                $timings_user->day_name='پنج شنبه';
                $timings_user->save();


                $timings_user=new TimingsUser();
                $timings_user->user_id=$user->id;
                $timings_user->desks_services_id=$DesksService->id;
                $timings_user->timings_id=$timings_id;
                $timings_user->day_name='جمعه';
                $timings_user->save();


                /*=================================End Timing==============================*/


                $SuspendedUser->delete();
                Auth::login($user);

                $username = trim(setting()['username_sms']);
                $password = trim(setting()['password_sms']);
                $from = "+983000505";
                $pattern_code = "t48hhcwaq9";
                $to = array($user->mobile);
                $input_data = array("name" => $user->name);
                $url = "https://ippanel.com/patterns/pattern?username=" . $username . "&password=" . urlencode($password) . "&from=$from&to=" . json_encode($to) . "&input_data=" . urlencode(json_encode($input_data)) . "&pattern_code=$pattern_code";
                $handler = curl_init($url);
                curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($handler, CURLOPT_POSTFIELDS, $input_data);
                curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($handler);
                session()->forget('checkCode');
                session()->forget('UserHairStylistInfo');
                session()->put('create-success', 'حساب کاربری شما با موفقیت ایجاد شد.و پس از برسی پنل شما فعال می شود.');
                return redirect('/profile');
            } else {
                session()->put('checkCode', 'کد وارد شده صحیح نمی باشد');
                return redirect('/register/hairStylist/ConfirmMobile');
            }
        } else {
            return redirect('/register/hairStylist');
        }

    }


    public function password_reset(Request $request)
    {
        $this->validate($request, [
        'mobile' => 'required|regex:/(09)[0-9]{9}/|digits:11',
    ],[
        'mobile.regex' => 'شماره موبایل نامعتبر است',
    ]);
        session()->put('ConfirmMobileResetPassMobile',$request->mobile);
        $user=User::where('mobile',session('ConfirmMobileResetPassMobile'))->first();
        if ($user){
            $code = rand(10000, 99999);
            $user->verifire_code = $code;
            $username = trim(setting()['username_sms']);
            $password = trim(setting()['password_sms']);
            $from = "+983000505";
            $pattern_code = "ybnldnpo73";
            $to = array($request->mobile);
            $input_data = array("name" => $request->name, "verification-code" => $code);
            $url = "https://ippanel.com/patterns/pattern?username=" . $username . "&password=" . urlencode($password) . "&from=$from&to=" . json_encode($to) . "&input_data=" . urlencode(json_encode($input_data)) . "&pattern_code=$pattern_code";
            $handler = curl_init($url);
            curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($handler, CURLOPT_POSTFIELDS, $input_data);
            curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($handler);
            $user->save();
            return redirect('/password/remember/ConfirmMobileResetPass');
        }else{
            session()->put('error', 'شماره موبایل پیدا نشد');
            return redirect()->back();
        }

    }

    public function ConfirmMobileResetPass(Request $request)
    {
        $title = "تائید شماره موبایل | " . setting()['title'];
        $seo_title = "تائید شماره موبایل," . setting()['seo_title'];
        $seo_content = "تائید شماره موبایل," . setting()['seo_content'];
        $user=User::where('mobile',session('ConfirmMobileResetPassMobile'))->first();
        if ($user){

            $data = ['id' => $user->id, 'mobile' => session('ConfirmMobileResetPassMobile'), 'code' => $user->verifire_code];
            session()->put('UserHairStylistInfoPass', $data);
            return view('auth.ConfirmMobileResetPass', compact(['title', 'seo_title', 'seo_content']));
        }else{
            session()->put('error', 'شماره موبایل پیدا نشد');
            return redirect()->back();
        }


    }

    public function checkCodeResetPass(Request $request)
    {
        if (session('UserHairStylistInfoPass')){
            $SuspendedUser = User::where(['id' => session('UserHairStylistInfoPass')['id'], 'mobile' => session('UserHairStylistInfoPass')['mobile']])->first();
            $code = str_replace(' ', '', $request->code);
            if ($SuspendedUser) {
                if ($SuspendedUser->verifire_code == $code) {
                    $data = ['id' => session('UserHairStylistInfoPass')['id'], 'mobile' => session('UserHairStylistInfoPass')['mobile']];
                    session()->put('NewPass',$data);
                    return redirect('/password/remember/NewPass');
                } else {
                    session()->put('checkCode', 'کد وارد شده صحیح نمی باشد');
                    return redirect('/password/remember/ConfirmMobileResetPass');
                }
            } else {
                return redirect('/password/reset');
            }
        }else {
            return redirect('/password/reset');
        }


    }

    public function NewPass()
    {
        if (session('NewPass')){
            session()->forget('ConfirmMobileResetPassMobile');
            session()->forget('UserHairStylistInfo');
            $title = "رمز عبور جدید | " . setting()['title'];
            $seo_title = "رمز عبور جدید," . setting()['seo_title'];
            $seo_content = "رمز عبور جدید," . setting()['seo_content'];
            return view('auth.passwords.NewPass', compact(['title', 'seo_title', 'seo_content']));
        }else {
            return redirect('/password/reset');
        }
    }

    public function NewPass_store(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|min:6|confirmed',
        ],[
            'password.required' => 'پسورد را وارد کنید',
            'password.min' => 'حداقل پسورد 6 کاراکتر است',
            'password.confirmed' => ' رمز ورود و تکرار رمز ورود یکسان نیست',
        ]);
        $user = User::where(['id' => session('NewPass')['id'], 'mobile' => session('NewPass')['mobile']])->first();
        $user->password = Hash::make($request->password);
        $user->save();
        Auth::login($user);
        session()->forget('NewPass');
        session()->put('success-alert','رمز عبور با موفقیت تغییر کرد');
        return redirect('/profile');
    }

}
