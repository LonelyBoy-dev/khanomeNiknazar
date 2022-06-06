<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Front\SuspendedUser;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use function Illuminate\Support\Facades\File;

class RegisterUserController extends Controller
{
    public function index()
    {
        session()->forget('UserHairStylistInfo');
        $title = "ثبت نام آرایشگر | " . setting()['title'];
        $seo_title = "ثبت نام آرایشگر," . setting()['seo_title'];
        $seo_content = "ثبت نام آرایشگر," . setting()['seo_content'];
        return view('auth.registerHairStylist', compact(['title', 'seo_title', 'seo_content']));
    }

    public function register(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|string|max:255|min:3',
            'email' => 'nullable|email|string|unique:users',
            'mobile' => 'required|regex:/(09)[0-9]{9}/|digits:11|unique:users',
            'password' => 'required|min:6|confirmed',
        ],
            [
                'name.required' => 'نام و نام خانوادگی را وارد کنید',
                'name.min' => 'حدقل 3 کاراکتر',
                'mobile.required' => 'شماره موبایل را وارد کنید',
                'mobile.regex' => 'شماره موبایل نامعتبر است',
                'mobile.digits' => 'شماره موبایل نامعتبر است',
                'mobile.unique' => 'شماره موبایل از قبل موجود است',
                'email.required' => 'ایمیل را وارد کنید',
                'email.email' => 'ایمیل نامعتبر است',
                'email.unique' => 'ایمیل از قبل موجود است',
                'password.required' => 'پسورد را وارد کنید',
                'password.min' => 'حداقل پسورد 6 کاراکتر است',
                'password.confirmed' => ' رمز ورود و تکرار رمز ورود یکسان نیست',
            ]);
        $user = new SuspendedUser();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->password = Hash::make($request->password);
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
        $user->save();



        $data=['id' =>$user->id,'mobile'=>$request->mobile,'code' =>$user->verifire_code];
        session()->put('UserInfo', $data);
        return redirect('/register/ConfirmMobile');
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
        return view('auth.ConfirmMobileUser', compact(['title', 'seo_title', 'seo_content']));
    }

    public function checkCode(Request $request)
    {
        $SuspendedUser=SuspendedUser::where(['id'=>session('UserInfo')['id'],'mobile'=>session('UserInfo')['mobile']])->first();
        $code=str_replace(' ', '',$request->code);
        if ($SuspendedUser){
            if ($SuspendedUser->verifire_code==$code){
                $user=new User();
                $user->name = $SuspendedUser->name;
                $user->email = $SuspendedUser->email;
                $user->mobile = $SuspendedUser->mobile;
                $user->password = $SuspendedUser->password;
                $user->status = "ACTIVE";
                $user->verifire = "NO";
                $user->HairStylist = "NO";



                $user->save();

                $SetUsername=User::find($user->id);

                $all_user=User::all();
                $countUser=99999-count($all_user);
                $countUser=explode('9',$countUser);
                if ($countUser[0]!=""){
                    $count_user=$all_user;
                }elseif ($countUser[1]!=""){
                    $count_user="0".count($all_user);
                }elseif ($countUser[2]!=""){
                    $count_user="00".count($all_user);
                }elseif ($countUser[3]!=""){
                    $count_user="000".count($all_user);
                }else{
                    $count_user="0000".count($all_user);
                }
                $Type_hairdresser=3;
                $SetUsername->username=$Type_hairdresser.$count_user.$user->id;
                $SetUsername->save();

                $SuspendedUser->delete();
                Auth::login($user);
                session()->forget('checkCode');
                session()->forget('UserInfo');
                session()->put('create-success', 'حساب کاربری شما با موفقیت ایجاد شد');
                return redirect('/profile');
            }else{
                session()->put('checkCode', 'کد وارد شده صحیح نمی باشد');
                return redirect('/register/ConfirmMobile');
            }
        }else{
            return redirect('/register');
        }

    }
}
