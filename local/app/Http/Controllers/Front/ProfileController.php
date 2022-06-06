<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\BlockList;
use App\Models\Category;
use App\Models\CategoryUser;
use App\Models\Comment;
use App\Models\DepositRequest;
use App\Models\Favorite;
use App\Models\Gallery;
use App\Models\Package;
use App\Models\Pay;
use App\Models\Payment;
use App\Models\Report;
use App\Models\Reserve;
use App\Models\Ticket;
use App\Models\Timing;
use App\Models\TimingsDay;
use App\Models\TimingsHairdresser;
use App\Models\TimingsInterval;
use App\Models\TimingsIntervalVal;
use App\Models\User;
use App\Models\TimingsUser;
use App\Models\DesksService;
use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Nette\Utils\DateTime;

class ProfileController extends Controller
{

    public function index()
    {

        if (Auth::user()->HairStylist == "YES") {
            $items = Reserve::with('user', 'timings')->where('hairstylist_id', Auth::id())->where('status', '!=', 'NOK')->orderBy('id', 'desc')->paginate(10);
        } else {
            $items = Reserve::with('hairstylist', 'timings')->where('user_id', Auth::id())->where('status', '!=', 'NOK')->orderBy('id', 'desc')->paginate(20);
        }
        $countReserve = Reserve::where(['hairstylist_id' => Auth::id(), 'status' => 'Accept'])->get();
        $countReserveToday = Reserve::where(['hairstylist_id' => Auth::id(), 'status' => 'Accept'])->whereDate('created_at', '=', date('Y-m-d'))->get();
        $packages=Package::all();
        $Active = "dashboard";
        $title = "پروفایل | " . setting()['title'];
        $seo_title = "پروفایل," . setting()['seo_title'];
        $seo_content = "پروفایل," . setting()['seo_content'];
        return view('front.profile.dashboard.index', compact('title', 'seo_title', 'seo_content', 'Active', 'items', 'countReserve', 'countReserveToday','packages'));
    }

    public function setting()
    {
        $user = User::find(Auth::id());
        $galleries = Gallery::where('user_id', Auth::id())->get();
        $services = Category::where('status', 'ACTIVE')->get();
        $services_user = CategoryUser::with('category')->where('user_id', Auth::id())->get();
        $Active = "setting";
        $title = "پروفایل | تنضیمات | " . setting()['title'];
        $seo_title = "پروفایل , تنضیمات," . setting()['seo_title'];
        $seo_content = "پروفایل , تنضیمات," . setting()['seo_content'];
        return view('front.profile.setting.index', compact('title', 'seo_title', 'seo_content', 'user', 'Active', 'galleries', 'services', 'services_user'));

    }

    public function setting_store(Request $request)
    {
        session()->put('error', 'مقادیر ستاره دار، نمی توانند خالی باشند!');
        if (Auth::user()->HairStylist == "YES") {
            $this->validate($request, [
                'name' => 'required|string|max:255|min:3',
                'nameShop' => 'required|max:255|min:3|string',
                'email' => 'nullable|email|unique:users,email,'.Auth::id(),
                'tell' => 'nullable|digits:11',
                'Type_hairdresser' => 'required',
                'shabaNumber' => 'required|numeric',
                'accountNumber' => 'required|numeric',
                'ostan' => 'required',
                'city' => 'required',
                'address' => 'required|min:10',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
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


                    'Businesslicense.required' => 'تصویر پروانه کسب خود را آپلود کنید',
                    'Businesslicense.image' => 'تصویر شما باید فرمت jpg,png باشد',
                    'Businesslicense.max' => 'حجم تصویر بیشتر از 2 مگ می باشد',
                    'avatar.required' => 'تصویر نمایی از مغازه خود را آپلود کنید',
                    'avatar.image' => 'تصویر شما باید فرمت jpg,png باشد',
                    'avatar.max' => 'حجم تصویر بیشتر از 2 مگ می باشد',
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
                    'email.unique' => 'ایمیل از قبل موجود می باشد',

                    'ostan.required' => 'استان خود را انتخاب کنید',
                    'city.required' => 'شهر خود را انتخاب کنید',

                    'Type_hairdresser.required' => 'نوع آرایشگاه را انتخاب کنید',


                    'password.required' => 'پسورد را وارد کنید',
                    'password.min' => 'حداقل پسورد 6 کاراکتر است',
                    'password.confirmed' => ' رمز ورود و تکرار رمز ورود یکسان نیست',
                ]);
            session()->forget('error');
            $user = User::find(Auth::id());
            $user->name = $request->name;
            $user->NationalCode = $request->NationalCode;
            $user->email = $request->email;
            $user->nameShop = $request->nameShop;
            //$user->mobile = $request->mobile;
            $user->tell = $request->tell;
            $user->accountNumber = $request->accountNumber;
            $user->shabaNumber = $request->shabaNumber;
            $user->Type_hairdresser = $request->Type_hairdresser;
            $user->address = $request->address;
            $user->ostan_id = $request->ostan_id;
            $user->city_id = $request->city_id;
            $user->ostan = $request->ostan;
            $user->city = $request->city;
            $user->workTime = $request->workTime;
            $user->approximate_price = $request->approximate_price;
            $user->location = $request->location;
            $user->services = $request->services;
            $user->specialist = $request->specialist;
            $user->Biography = $request->Biography;
            $user->description = $request->description;
            $user->pishPay = $request->pishPay;

            $user->instagram = $request->instagram;
            $user->telegram = $request->telegram;
            $user->whatsapp = $request->whatsapp;
            $user->facebook = $request->facebook;

            if ($request->pishPay == "YES") {
                if ($request->pishPay_val < setting()['pishPay']) {
                    session()->put('error', 'حداقل مقدار پیش پرداخت ' . number_format(setting()['pishPay']) . ' تومان می باشد!');
                    return redirect()->back();
                } else {
                    $user->pishPay_val = $request->pishPay_val;
                }
            }


            //=====================image 1 ===============
            $avatar = $request->file('avatar');
            if ($avatar) {
                $avatar_name = rand(1, 99999) . time() . '_' . $avatar->getClientOriginalName();
                $image = Image::make($avatar);

                if (!is_dir('FileUploader/users')) {
                    mkdir("FileUploader/users");
                }
                if (!is_dir('FileUploader/users/' . $user->id)) {
                    mkdir("FileUploader/users/" . $user->id);
                }
                if (!is_dir('FileUploader/users/' . $user->id . '/avatar')) {
                    mkdir("FileUploader/users/" . $user->id . '/avatar');
                }
                $image->save("FileUploader/users/" . $user->id . '/avatar' . $avatar_name);
                $user->avatar = "FileUploader/users/" . $user->id . '/avatar' . $avatar_name;
            }
            $user->save();
        } else {
            $this->validate($request, [
                'name' => 'required|string|max:255|min:3',
                'email' => 'nullable|email|unique:users,email,'.Auth::id(),
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ],
                [
                    'name.required' => 'نام و نام خانوادگی را وارد کنید',
                    'name.min' => 'حدقل 3 کاراکتر',
                    'avatar.required' => 'تصویر نمایی از مغازه خود را آپلود کنید',
                    'avatar.image' => 'تصویر شما باید فرمت jpg,png باشد',
                    'avatar.max' => 'حجم تصویر بیشتر از 2 مگ می باشد',
                    'email.email' => 'ایمیل نامعتبر است',
                    'email.unique' => 'ایمیل از قبل موجود می باشد',
                ]);
            session()->forget('error');
            $user = User::find(Auth::id());
            $user->name = $request->name;
            $user->email = $request->email;
            $user->Biography = $request->Biography;
            $avatar = $request->file('avatar');
            if ($avatar) {
                $avatar_name = rand(1, 99999) . time() . '_' . $avatar->getClientOriginalName();
                $image = Image::make($avatar);

                if (!is_dir('FileUploader/users')) {
                    mkdir("FileUploader/users");
                }
                if (!is_dir('FileUploader/users/' . $user->id)) {
                    mkdir("FileUploader/users/" . $user->id);
                }
                if (!is_dir('FileUploader/users/' . $user->id . '/avatar')) {
                    mkdir("FileUploader/users/" . $user->id . '/avatar');
                }
                $image->save("FileUploader/users/" . $user->id . '/avatar' . $avatar_name);
                $user->avatar = "FileUploader/users/" . $user->id . '/avatar' . $avatar_name;
            }
            $user->save();
        }
        session()->put('create-success', 'تغییرات با موفقیت ذخیره شد');
        return redirect('/profile/setting');
    }


    public function change_password()
    {

        $Active = "change-password";
        $title = "پروفایل | تغییر پسورد | " . setting()['title'];
        $seo_title = "پروفایل , تغییر پسورد," . setting()['seo_title'];
        $seo_content = "پروفایل , تغییر پسورد," . setting()['seo_content'];
        return view('front.profile.change-password.index', compact('title', 'seo_title', 'seo_content', 'Active'));

    }

    public function change_password_change(Request $request)
    {
        $this->validate($request, [
            'previous_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ],
            [
                'previous_password.required' => 'پسور قبلی را وارد کنید',
                'password.required' => 'پسور جدید را وارد کنید',
                'password.min' => 'پسورد حداقل باید 6 کراکتر باشد',
                'password.confirmed' => 'رمز ورود و تکرار رمز ورود یکسان نیست',
            ]);
        $user = User::find(Auth::id());
        if (Hash::check($request->previous_password, $user->password)) {
            $user->password = Hash::make($request->password);
            $user->save();
            session()->put('create-success', 'رمز ورود شما با موفقیت تغییر کرد');
        } else {
            session()->put('warning', 'رمز ورود قبلی با هم مطابقت ندارد');
        }
        return redirect('/profile/change-password');
    }


    public function tickets()
    {
        if (Auth::user()->HairStylist == "YES") {
            $Active = "tickets";
            $title = "پروفایل | تیکت ها | " . setting()['title'];
            $seo_title = "پروفایل," . setting()['seo_title'];
            $seo_content = "پروفایل," . setting()['seo_content'];
            $items = Ticket::where(['user_id' => Auth::id(), 'parent' => '0'])->orderby('updated_at', 'desc')->paginate(10);
            return view('front.profile.ticket.index', compact('title', 'seo_title', 'seo_content', 'Active', 'items'));
        } else {
            return redirect('/profile');
        }
    }

    public function ticket_create()
    {
        if (Auth::user()->HairStylist == "YES") {
            $Active = "tickets";
            $title = "پروفایل | تیکت ها | افزودن تیکت | " . setting()['title'];
            $seo_title = "پروفایل," . setting()['seo_title'];
            $seo_content = "پروفایل," . setting()['seo_content'];
            return view('front.profile.ticket.create', compact('title', 'seo_title', 'seo_content', 'Active'));
        } else {
            return redirect('/profile');
        }
    }

    public function ticket_store(Request $request)
    {
        if ($request->type) {
            $this->validate($request, [
                'message' => 'required|min:6',
                'file' => 'nullable|max:2048',
            ],
                [
                    'title.required' => 'عنوان ضروری است',
                    'message.required' => 'پیام ضروری است',
                    'file.max' => 'حجم فایل شما بیشتر از 2 مگ می باشد',
                ]);
        } else {
            $this->validate($request, [
                'title' => 'required',
                'message' => 'required|min:6',
                'file' => 'nullable|max:2048',
            ],
                [
                    'title.required' => 'عنوان ضروری است',
                    'message.required' => 'پیام ضروری است',
                    'file.max' => 'حجم فایل شما بیشتر از 2 مگ می باشد',
                ]);
        }

        $item = new Ticket();
        $item->message = $request->message;
        $item->user_id = Auth::id();
        $file = $request->file('file');
        if ($file) {
            $name = time() . $file->getClientOriginalName();
            if (!is_dir('FileUploader/users')) {
                mkdir("FileUploader/users");
            }
            if (!is_dir('FileUploader/users/' . Auth::id())) {
                mkdir("FileUploader/users/" . Auth::id());
            }
            if (!is_dir('FileUploader/users/' . Auth::id() . '/ticket')) {
                mkdir("FileUploader/users/" . Auth::id() . '/ticket');
            }
            $file->move('FileUploader/users/' . Auth::id() . '/ticket', $name);
            $item->file = 'FileUploader/users/' . Auth::id() . '/ticket/'.$name;
        }
        if ($request->type) {
            $item->parent = $request->parent;
            Ticket::where('id', $request->parent)->orwhere('parent', $request->parent)->update(['status' => 'waiting']);
            $item->save();
            session()->put('create-success', 'پاسخ شما با موفقیت ثبت شد');
        } else {
            $item->title = $request->title;
            $item->save();
            session()->put('create-success', 'تیکت شما با موفقیت ثبت شد');
        }

        return redirect('/profile/tickets');
    }

    public function ticket_show($id = null)
    {
        if (Auth::user()->HairStylist == "YES") {
            $Active = "tickets";
            $title = "پروفایل | تیکت ها | " . setting()['title'];
            $seo_title = "پروفایل," . setting()['seo_title'];
            $seo_content = "پروفایل," . setting()['seo_content'];
            Ticket::where(['user_id' => Auth::id(), 'id' => $id])->update(['new'=>'NO']);
            Ticket::where(['user_id' => Auth::id(), 'parent' => $id])->update(['new'=>'NO']);
            $item = Ticket::where(['user_id' => Auth::id(), 'id' => $id])->orderby('id', 'desc')->first();
            return view('front.profile.ticket.show', compact('title', 'seo_title', 'seo_content', 'Active', 'item'));
        } else {
            return redirect('/profile');
        }
    }


    public function timings_hairdresser($id=null)
    {
        if ($id){
            $Timing_users=TimingsUser::where(['user_id'=> Auth::id(),'desks_services_id'=>$id])->get();
            if (count($Timing_users)){
                if (Auth::user()->HairStylist == "YES") {
                    if (Auth::user()->verifire == "YES") {
                        $DesksServices= DesksService::where(['user_id'=>Auth::id()])->get();

                        $Active = "timings_hairdresser";
                        $title = "پروفایل | زمان بندی | " . setting()['title'];
                        $seo_title = "پروفایل," . setting()['seo_title'];
                        $seo_content = "پروفایل," . setting()['seo_content'];
                        $Timing = Timing::all();
                        $TimingsDay = TimingsDay::orderby('id', 'asc')->get();
                        if (setting()['Payment_membership']=="ACTIVE"){
                            if (Auth::user()->membership_status == "OK"){
                                return view('front.profile.timings.H-index', compact('title', 'seo_title', 'seo_content', 'Active', 'Timing', 'TimingsDay','DesksServices','id'));
                            }elseif(Auth::user()->membership_status == "END"){
                                session()->put('error-alert', 'زمان اشتراک شما به پایان رسیده است');
                                return redirect('/profile');
                            }else{
                                session()->put('error-alert', 'برای دستری به بخش های پنل، اشتراک تهیه کنید');
                                return redirect('/profile');
                            }
                        }else{
                            return view('front.profile.timings.H-index', compact('title', 'seo_title', 'seo_content', 'Active', 'Timing', 'TimingsDay','DesksServices','id'));
                        }
                    } else {
                        session()->put('error-alert','پنل شما هنوز فعال نشده است');
                        return redirect('/profile');
                    }
                } else {
                    return redirect('/profile');
                }
            }else{
                abort(404);
            }
        }else{
            if (Auth::user()->HairStylist == "YES") {
                if (Auth::user()->verifire == "YES") {
                    $DesksServices= DesksService::where(['user_id'=>Auth::id()])->get();

                    $Active = "timings_hairdresser";
                    $title = "پروفایل | زمان بندی | " . setting()['title'];
                    $seo_title = "پروفایل," . setting()['seo_title'];
                    $seo_content = "پروفایل," . setting()['seo_content'];
                    $Timing = Timing::all();
                    $TimingsDay = TimingsDay::orderby('id', 'asc')->get();
                    if (setting()['Payment_membership']=="ACTIVE"){
                        if (Auth::user()->membership_status == "OK"){
                            return view('front.profile.timings.H-index', compact('title', 'seo_title', 'seo_content', 'Active', 'Timing', 'TimingsDay','DesksServices','id'));
                        }elseif(Auth::user()->membership_status == "END"){
                            session()->put('error-alert', 'زمان اشتراک شما به پایان رسیده است');
                            return redirect('/profile');
                        }else{
                            session()->put('error-alert', 'برای دستری به بخش های پنل، اشتراک تهیه کنید');
                            return redirect('/profile');
                        }
                    }else{
                        return view('front.profile.timings.H-index', compact('title', 'seo_title', 'seo_content', 'Active', 'Timing', 'TimingsDay','DesksServices','id'));
                    }
                } else {
                    session()->put('error-alert','پنل شما هنوز فعال نشده است');
                    return redirect('/profile');
                }
            } else {
                return redirect('/profile');
            }
        }


    }

    public function timings_hairdresser_store_time(Request $request)
    {
        $this->validate($request, [
            'day_name' => 'required',
            'desks_services_id' => 'required',
        ]);
        $timings_closed_id="";
        if($request->time){
            $timings_closed_id=implode(',',$request->time);
        }


        $timings=Timing::all();
        $timings_id=[];
        foreach ($timings as $timing){
            $timings_id[]=$timing->id;
        }
        $timings_id=implode(',',$timings_id);

        $timings_user=TimingsUser::where(['day_name'=>$request->day_name,'user_id'=>Auth::id(),'desks_services_id'=>$request->desks_services_id])->first();
        $timings_user->closed=$timings_closed_id;
        $timings_user->timings_id=$timings_id;
        $timings_user->save();

        session()->put('success-alert', 'زمان بندی با موقعیت ثبت شد');
        return redirect('/profile/H-timings/'.$request->desks_services_id);
    }

    public function timings_hairdresser_store(Request $request)
    {
        $closed="";
        if ($request->closed){
            $closed=implode(',',$request->closed);
        }
        $user=User::find(Auth::id());
        $user->closed=$closed;
        $user->save();
        session()->put('success-alert', 'روزهای غیر کاری با موقعیت ثبت شد');
        return redirect('/profile/H-timings');
    }

    public function timings_hairdresser_update(Request $request)
    {

        $row = 0;
        if ($request->startTime) {
            foreach ($request->startTime as $foreach) {
                $is = Timing::where(['user_id' => Auth::id(), 'id' => @$request->Timings_id[$row], 'timings_interval_id' => $request->timings_interval_id])->orderby('id', 'asc')->first();
                if ($is == "") {
                    $timings = new Timing();
                    $timings->startTime = $request->startTime[$row];
                    $timings->endTime = $request->endTime[$row];
                    $timings->user_id = Auth::id();
                    $timings->timings_interval_id = $request->timings_interval_id;
                    $timings->save();
                } else {
                    $is->startTime = $request->startTime[$row];
                    $is->endTime = $request->endTime[$row];
                    $is->user_id = Auth::id();
                    $is->timings_interval_id = $request->timings_interval_id;
                    $is->save();
                }

                $row++;

            }
        }

        session()->put('success-alert', 'زمان بندی با موقعیت ویرایش شد');
        return redirect('/profile/H-timings');
    }


    public function desks_services()
    {
        if (Auth::user()->HairStylist == "YES") {
            if (Auth::user()->verifire == "YES") {
                $Active = "desks_service";
                $title = "پروفایل | میزها و خدمات | " . setting()['title'];
                $seo_title = "پروفایل," . setting()['seo_title'];
                $seo_content = "پروفایل," . setting()['seo_content'];
                $items= DesksService::where(['user_id'=>Auth::id()])->get();
                if (setting()['Payment_membership']=="ACTIVE"){
                    if (Auth::user()->membership_status == "OK"){
                        return view('front.profile.desks_service.index', compact('title', 'seo_title', 'seo_content', 'Active', 'items'));
                    }elseif(Auth::user()->membership_status == "END"){
                        session()->put('error-alert', 'زمان اشتراک شما به پایان رسیده است');
                        return redirect('/profile');
                    }else{
                        session()->put('error-alert', 'برای دستری به بخش های پنل، اشتراک تهیه کنید');
                        return redirect('/profile');
                    }
                }else{
                    return view('front.profile.desks_service.index', compact('title', 'seo_title', 'seo_content', 'Active', 'items'));
                }
            } else {
                session()->put('error-alert','پنل شما هنوز فعال نشده است');
                return redirect('/profile');
            }
        } else {
            return redirect('/profile');
        }
    }

    public function desks_service(Request $request,$id)
    {
        if (Auth::user()->HairStylist == "YES") {
            if (Auth::user()->verifire == "YES") {
                $Active = "desks_service";
                $title = "پروفایل | میزها و خدمات | " . setting()['title'];
                $seo_title = "پروفایل," . setting()['seo_title'];
                $seo_content = "پروفایل," . setting()['seo_content'];
                $item= DesksService::where(['id'=>$id,'user_id'=>Auth::id()])->first();
                $services = Category::where('status', 'ACTIVE')->get();
                $services_user = CategoryUser::with('category')->where(['user_id'=> Auth::id(),'desks_services_id'=>$id])->get();
                if (setting()['Payment_membership']=="ACTIVE"){
                    if (Auth::user()->membership_status == "OK"){
                        return view('front.profile.timings.H-index', compact('title', 'seo_title', 'seo_content', 'Active', 'item', 'services', 'services_user'));
                    }elseif(Auth::user()->membership_status == "END"){
                        session()->put('error-alert', 'زمان اشتراک شما به پایان رسیده است');
                        return redirect('/profile');
                    }else{
                        session()->put('error-alert', 'برای دستری به بخش های پنل، اشتراک تهیه کنید');
                        return redirect('/profile');
                    }
                }else{
                    return view('front.profile.desks_service.show', compact('title', 'seo_title', 'seo_content', 'Active', 'item', 'services', 'services_user'));
                }
            } else {
                session()->put('error-alert','پنل شما هنوز فعال نشده است');
                return redirect('/profile');
            }
        } else {
            return redirect('/profile');
        }
    }

    public function desks_service_store(Request $request)
    {
        $item=new DesksService();
        $item->title=$request->title;
        $item->description=$request->description;
        $item->user_id=Auth::id();
        $item->save();

        $timings=Timing::all();
        $timings_id=[];
        foreach ($timings as $timing){
            $timings_id[]=$timing->id;
        }
        $timings_id=implode(',',$timings_id);
        $timings_user=new TimingsUser();
        $timings_user->desks_services_id=$item->id;
        $timings_user->user_id=Auth::id();
        $timings_user->timings_id=$timings_id;
        $timings_user->day_name='شنبه';
        $timings_user->save();


        $timings_user=new TimingsUser();
        $timings_user->user_id=Auth::id();
        $timings_user->desks_services_id=$item->id;
        $timings_user->timings_id=$timings_id;
        $timings_user->day_name='یکشنبه';
        $timings_user->save();


        $timings_user=new TimingsUser();
        $timings_user->user_id=Auth::id();
        $timings_user->desks_services_id=$item->id;
        $timings_user->timings_id=$timings_id;
        $timings_user->day_name='دوشنبه';
        $timings_user->save();


        $timings_user=new TimingsUser();
        $timings_user->user_id=Auth::id();
        $timings_user->desks_services_id=$item->id;
        $timings_user->timings_id=$timings_id;
        $timings_user->day_name='سه شنبه';
        $timings_user->save();


        $timings_user=new TimingsUser();
        $timings_user->user_id=Auth::id();
        $timings_user->desks_services_id=$item->id;
        $timings_user->timings_id=$timings_id;
        $timings_user->day_name='چهارشنبه';
        $timings_user->save();


        $timings_user=new TimingsUser();
        $timings_user->user_id=Auth::id();
        $timings_user->desks_services_id=$item->id;
        $timings_user->timings_id=$timings_id;
        $timings_user->day_name='پنج شنبه';
        $timings_user->save();


        $timings_user=new TimingsUser();
        $timings_user->user_id=Auth::id();
        $timings_user->desks_services_id=$item->id;
        $timings_user->timings_id=$timings_id;
        $timings_user->day_name='جمعه';
        $timings_user->save();


        session()->put('success-alert', 'میزکار با موفقیت اضافه شد');
        return redirect('/profile/desks-services');
    }

    public function desks_service_update(Request $request)
    {

        $item= DesksService::find($request->id);
        $item->title=$request->title;
        $item->description=$request->description;
        $item->save();

        session()->put('success-alert', 'میزکار با موفقیت ویرایش شد');
        return redirect('/profile/desks-services');
    }

    public function service_store(Request $request)
    {
        $item = new CategoryUser();
        $item->category_id = $request->service;
        $item->user_id = Auth::id();
        $item->desks_services_id = $request->desks_id;
        $item->price = $request->price;
        $item->time = $request->time;
        $item->description = $request->description;
        $item->save();
        session()->put('success-alert', 'سرویس شما با موفقیت اضافه شد');
        return redirect('/profile/desks-service/'.$request->desks_id);
    }

    public function service_edit(Request $request)
    {
        $item = CategoryUser::find($request->id);
        $item->category_id = $request->service;
        $item->user_id = Auth::id();
        $item->desks_services_id = $request->desks_id;
        $item->price = $request->price;
        $item->time = $request->time;
        $item->description = $request->description;
        $item->save();
        session()->put('success-alert', 'سرویس شما با موفقیت ویرایش شد');
        return redirect('/profile/desks-service/'.$request->desks_id);
    }


    public function favorites()
    {
        if (Auth::user()->HairStylist == "NO") {
            $Active = "favourites";
            $title = "پروفایل | ‌علاقه‌مندیها | " . setting()['title'];
            $seo_title = "پروفایل," . setting()['seo_title'];
            $seo_content = "پروفایل," . setting()['seo_content'];
            $items = Favorite::where(['user_id' => Auth::id()])->orderby('id', 'desc')->paginate(10);
            return view('front.profile.favorites.index', compact('title', 'seo_title', 'seo_content', 'Active', 'items'));
        } else {
            return redirect('/profile');
        }
    }


    public function comments()
    {
        if (Auth::user()->verifire == "YES") {
        $Active = "comments";
        $title = "پروفایل | نظرات | " . setting()['title'];
        $seo_title = "پروفایل," . setting()['seo_title'];
        $seo_content = "پروفایل," . setting()['seo_content'];
        $comments = Comment::where(['post_id' => Auth::id(), 'parent' => '0', 'status' => 'SEEN', 'type' => 'product'])->orderby('updated_at', 'desc')->paginate(10);
        if (setting()['Payment_membership']=="ACTIVE") {
            if (Auth::user()->membership_status == "OK") {
                return view('front.profile.comments.index', compact('title', 'seo_title', 'seo_content', 'Active', 'comments'));
            }elseif(Auth::user()->membership_status == "END"){
                session()->put('error-alert', 'زمان اشتراک شما به پایان رسیده است');
                return redirect('/profile');
            }else{
                session()->put('error-alert', 'برای دستری به بخش های پنل، اشتراک تهیه کنید');
                return redirect('/profile');
            }
        }else{
            return view('front.profile.comments.index', compact('title', 'seo_title', 'seo_content', 'Active', 'comments'));
        }
        } else {
            session()->put('error-alert', 'پنل شما هنوز فعال نشده است');
            return redirect('/profile');
        }
    }

    public function store_answer(Request $request)
    {
        $this->validate($request, [
            'comment' => 'required',
            'comment_id' => 'required',
        ]);
        $item = new Comment();
        $item->hairstylist_id = Auth::id();
        $item->parent = $request->comment_id;
        $item->comment = $request->comment;
        $item->post_id = Auth::id();
        $item->type = "product";
        $item->save();
        session()->put('success-alert', 'پاسخ شما با موفقیت ذخیره شد');
        return redirect()->back();
    }



    public function appointments()
    {
        if (Auth::user()->HairStylist == "YES") {
            if (Auth::user()->verifire == "YES") {
                if (Auth::user()->HairStylist == "YES") {
                    $items = Reserve::with('user', 'timings')->where(['hairstylist_id' => Auth::id()])->orderBy('id', 'desc')->paginate(10);
                } else {
                    $items = Reserve::with('hairstylist', 'timings')->where('user_id', Auth::id())->orderBy('id', 'desc')->paginate(20);
                }
                $Active = "appointments";
                $title = "نوبت دهی | " . setting()['title'];
                $seo_title = "نوبت دهی," . setting()['seo_title'];
                $seo_content = "نوبت دهی," . setting()['seo_content'];
                if (setting()['Payment_membership']=="ACTIVE") {
                    if (Auth::user()->membership_status == "OK") {
                        return view('front.profile.appointments.index', compact('title', 'seo_title', 'seo_content', 'Active', 'items'));

                    }elseif(Auth::user()->membership_status == "END"){
                        session()->put('error-alert', 'زمان اشتراک شما به پایان رسیده است');
                        return redirect('/profile');
                    }else{
                        session()->put('error-alert', 'برای دستری به بخش های پنل، اشتراک تهیه کنید');
                        return redirect('/profile');
                    }
                }else{
                    return view('front.profile.appointments.index', compact('title', 'seo_title', 'seo_content', 'Active', 'items'));

                }
            } else {
                session()->put('error-alert', 'پنل شما هنوز فعال نشده است');
                return redirect('/profile');
            }
        } else {
            return redirect('/profile');
        }
    }


    public function my_patients()
    {
        if (Auth::user()->HairStylist == "YES") {
            if (Auth::user()->verifire == "YES") {
                $items = Reserve::with('user', 'timings')->where('hairstylist_id', Auth::id())->select('user_id', 'hairstylist_id')->distinct()->paginate(20);
                $Active = "my_patients";
                $title = "نوبت دهی | " . setting()['title'];
                $seo_title = "نوبت دهی," . setting()['seo_title'];
                $seo_content = "نوبت دهی," . setting()['seo_content'];
                if (setting()['Payment_membership']=="ACTIVE"){
                    if (Auth::user()->membership_status == "OK"){
                        return view('front.profile.my-patients.index', compact('title', 'seo_title', 'seo_content', 'Active', 'items'));
                    }elseif(Auth::user()->membership_status == "END"){
                        session()->put('error-alert', 'زمان اشتراک شما به پایان رسیده است');
                        return redirect('/profile');
                    }else{
                        session()->put('error-alert', 'برای دستری به بخش های پنل، اشتراک تهیه کنید');
                        return redirect('/profile');
                    }
                }else{
                    return view('front.profile.my-patients.index', compact('title', 'seo_title', 'seo_content', 'Active', 'items'));
                }
            } else {
                session()->put('error-alert', 'پنل شما هنوز فعال نشده است');
                return redirect('/profile');
            }
        } else {
            return redirect('/profile');
        }
    }

    public function reserve_success(Request $request)
    {
        if (session('reserve-success')) {
            $Pay = Pay::find(session('reserve-success'));
            $item = Reserve::where('id', $Pay->reserve_id)->with('hairstylist')->first();
            $title = "رزرو موفق | " . setting()['title'];
            $seo_title = "رزرو موفق," . setting()['seo_title'];
            $seo_content = "رزرو موفق," . setting()['seo_content'];
            return view('front.hairstylist.reserve-success', compact('title', 'seo_title', 'seo_content', 'item', 'Pay'));
        }
        return redirect('/profile');
    }

    public function reserve_danger(Request $request)
    {
        if (session('reserve-danger')) {
            $Pay = Pay::find(session('reserve-danger'));
            $item = Reserve::where('id', $Pay->reserve_id)->with('hairstylist')->first();
            $hairstylist=User::find($Pay->hairstylist_id);
            $title = "رزرو ناموفق | " . setting()['title'];
            $seo_title = "رزرو ناموفق," . setting()['seo_title'];
            $seo_content = "رزرو ناموفق," . setting()['seo_content'];
            return view('front.hairstylist.reserve-danger', compact('title', 'seo_title', 'seo_content', 'item', 'Pay','hairstylist'));

        }
        return redirect('/profile');
    }

    public function wallet(Request $request)
    {
        if (Auth::user()->HairStylist == "YES") {
            if (Auth::user()->verifire == "YES") {
                $items = DepositRequest::with('hairstylist')->where('hairstylist_id', Auth::id())->orderBy('id', 'desc')->paginate(15);
                $Active = "wallet";
                $title = "کیف پول | " . setting()['title'];
                $seo_title = "کیف پول," . setting()['seo_title'];
                $seo_content = "کیف پول," . setting()['seo_content'];
                if (setting()['Payment_membership']=="ACTIVE"){
                    if (Auth::user()->membership_status == "OK"){
                        return view('front.profile.wallet.index', compact('Active', 'title', 'seo_title', 'seo_content', 'items'));
                    }elseif(Auth::user()->membership_status == "END"){
                        session()->put('error-alert', 'زمان اشتراک شما به پایان رسیده است');
                        return redirect('/profile');
                    }else{
                        session()->put('error-alert', 'برای دستری به بخش های پنل، اشتراک تهیه کنید');
                        return redirect('/profile');
                    }
                }else{
                    return view('front.profile.wallet.index', compact('Active', 'title', 'seo_title', 'seo_content', 'items'));
                }
            } else {
                session()->put('error-alert', 'پنل شما هنوز فعال نشده است');
                return redirect('/profile');
            }
        } else {
            return redirect('/profile');
        }
    }

    public function store_request_wallet(Request $request)
    {
        $this->validate($request, [
            'value' => 'required',
        ]);
        $user=User::find(Auth::id());
        if ($user->wallet>=$request->value){
            $user->wallet=$user->wallet-$request->value;
            $user->save();
           $DepositRequest=new DepositRequest();
            $DepositRequest->hairstylist_id=Auth::id();
            $DepositRequest->price=$request->value;
            $DepositRequest->account_balance=$user->wallet;
            $DepositRequest->status="Waiting";
            $DepositRequest->save();

            session()->put('success-alert','درخواست شما با موفقیت ثبت شد');
            return redirect()->back();
        }else{
            session()->put('error-alert','خطا در ثبت درخواست');
            return redirect()->back();
        }
    }

    public function reports(Request $request)
    {
        if (Auth::user()->HairStylist == "YES") {
            if (Auth::user()->verifire == "YES") {
                $items = Report::with('hairstylist', 'user')->where('hairstylist_id', Auth::id())->orderBy('id', 'desc')->paginate(15);
                $Active = "reports";
                $title = "گزارشات | " . setting()['title'];
                $seo_title = "گزارشات," . setting()['seo_title'];
                $seo_content = "گزارشات," . setting()['seo_content'];
                if (setting()['Payment_membership']=="ACTIVE"){
                    if (Auth::user()->membership_status == "OK"){
                        return view('front.profile.reports.index', compact('Active', 'title', 'seo_title', 'seo_content', 'items'));
                    }elseif(Auth::user()->membership_status == "END"){
                        session()->put('error-alert', 'زمان اشتراک شما به پایان رسیده است');
                        return redirect('/profile');
                    }else{
                        session()->put('error-alert', 'برای دستری به بخش های پنل، اشتراک تهیه کنید');
                        return redirect('/profile');
                    }
                }else{
                    return view('front.profile.reports.index', compact('Active', 'title', 'seo_title', 'seo_content', 'items'));
                }
            } else {
                session()->put('error-alert', 'پنل شما هنوز فعال نشده است');
                return redirect('/profile');
            }
        } else {
            $items = Report::with('hairstylist', 'user')->where('user_id', Auth::id())->orderBy('id', 'desc')->paginate(15);
            $Active = "reports";
            $title = "گزارشات | " . setting()['title'];
            $seo_title = "گزارشات," . setting()['seo_title'];
            $seo_content = "گزارشات," . setting()['seo_content'];
            return view('front.profile.reports.index', compact('Active', 'title', 'seo_title', 'seo_content', 'items'));
        }

    }

    public function blockList()
    {
        if (Auth::user()->HairStylist == "YES") {
            if (Auth::user()->verifire == "YES") {
            $items = BlockList::with('hairstylist', 'user')->where('hairstylist_id', Auth::id())->orderBy('id', 'desc')->paginate(15);
            $Active = "blockList";
            $title = "گزارشات | " . setting()['title'];
            $seo_title = "گزارشات," . setting()['seo_title'];
            $seo_content = "گزارشات," . setting()['seo_content'];
            if (setting()['Payment_membership']=="ACTIVE"){
                if (Auth::user()->membership_status == "OK"){
                    return view('front.profile.blockList.index', compact('Active', 'title', 'seo_title', 'seo_content', 'items'));

                }elseif(Auth::user()->membership_status == "END"){
                    session()->put('error-alert', 'زمان اشتراک شما به پایان رسیده است');
                    return redirect('/profile');
                }else{
                    session()->put('error-alert', 'برای دستری به بخش های پنل، اشتراک تهیه کنید');
                    return redirect('/profile');
                }
            }else{
                return view('front.profile.blockList.index', compact('Active', 'title', 'seo_title', 'seo_content', 'items'));

            }
            } else {
                session()->put('error-alert', 'پنل شما هنوز فعال نشده است');
                return redirect('/profile');
            }
        }else {
            return redirect('/profile');
        }
    }

    public function packages(Request $request)
    {
        if (Auth::user()->HairStylist == "YES") {
            if (setting()['Payment_membership'] == "ACTIVE") {
                $packages=Package::all();
                $Active = "package";
                $title = "پکیج ها | " . setting()['title'];
                $seo_title = "پکیج ها," . setting()['seo_title'];
                $seo_content = "پکیج ها," . setting()['seo_content'];
                return view('front.profile.package.index', compact('title', 'seo_title', 'seo_content', 'Active','packages'));

            }else {
                return redirect('/profile');
            }
        }else {
            return redirect('/profile');
        }
    }

    public function buy_package(Request $request)
    {
        $package=Package::find($request->id_package);
        if ($package){
            $user=User::find(Auth::id());
            if ($request->pay_method=="wallet"){
                if ($package->price<=$user->wallet){
                    $user->wallet=$user->wallet-$package->price;
                    if ($user->membership_status=="NOK"){
                        $user->membership_day=$package->day;
                        $user->membership_dateBuy=Carbon::now()->format('Y-m-d H:i:s');
                    }elseif ($user->membership_status=="END"){
                        $user->membership_day=$package->day;
                        $user->membership_dateBuy=Carbon::now()->format('Y-m-d H:i:s');
                    }elseif ($user->membership_status=="OK"){
                        $current_date = Carbon::now();
                        $dt = Carbon::parse($user->membership_dateBuy);
                        $dt2 = $dt->diffInDays($current_date);

                        $membership_day=$user->membership_day-$dt2;
                        if ($dt2 < $user->membership_day) {
                            $user->membership_day=$membership_day+$package->day;
                        }
                    }

                    $user->membership_status="OK";
                    $user->save();

                    /* ===================== report=======================*/
                    $report = new Report();
                    $v = new Verta();
                    $factor = 'P-' . $v->year . $v->month . $v->day . $v->second . rand(100, 999) . Auth::id();
                    $report->factor_number = $factor;
                    $report->hairstylist_id = $user->id;
                    $report->user_id = $user->id;
                    $report->price = $package->price;
                    $report->pay_price = $package->price;
                    $report->remaining_price = 0;
                    $report->pay_status = "OK";
                    $report->pay_method = $request->pay_method;
                    $report->type = "package";
                    $report->description = " خرید پکیج ".$package->title;
                    $report->save();
                    /* =====================End report=======================*/

                    $username = trim(setting()['username_sms']);
                    $password = trim(setting()['password_sms']);
                    $from = "+983000505";
                    $pattern_code = "l87pia7ubh";
                    $to = array($user->mobile);
                    $input_data = array("name" => $user->name);
                    $url = "https://ippanel.com/patterns/pattern?username=" . $username . "&password=" . urlencode($password) . "&from=$from&to=" . json_encode($to) . "&input_data=" . urlencode(json_encode($input_data)) . "&pattern_code=$pattern_code";
                    $handler = curl_init($url);
                    curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
                    curl_setopt($handler, CURLOPT_POSTFIELDS, $input_data);
                    curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
                    $response = curl_exec($handler);


                    session()->put('success-alert', 'پرداخت شما با موفقیت انجام شد و اشتراک شما تمدید شد');
                    return redirect()->back();
                }else{
                    session()->put('error-alert', 'موجودی کیف پول شما کافی نمی باشد');
                    return redirect()->back();
                }

                /* ===================== online pay=======================*/
            }elseif ($request->pay_method=="online"){
                $payment = new Payment('profile/package/buy-package-verify', $package->price, null, $package->id, Auth::id());
                $result = $payment->doPayment();
                $res = $result->return . ',test';
                $res = explode(',', $res);
                if ($res[0] == "0") {
                    return view('mellat')->with(['tokenId' => $res[1]]);
                } else {
                    echo $this->MellatErrors($result->return);
                }

            }
        }else{
            return redirect()->back();
        }
    }

    public function buy_package_verify(Request $request)
    {
        $package=Package::find($request->SaleOrderId);
        $user=User::find($request->data);
        $payment = new Payment(null, $request->data, null, $request->SaleOrderId, $request->SaleReferenceId);
        $result = $payment->verifyPayment();
        if ($result->return == "0") {
            $result = $payment->settleRequest();
            if ($result->return == "0") {
                if ($user->membership_status == "NOK") {
                    $user->membership_day = $package->day;
                    $user->membership_dateBuy = Carbon::now()->format('Y-m-d H:i:s');
                } elseif ($user->membership_status == "END") {
                    $user->membership_day = $package->day;
                    $user->membership_dateBuy = Carbon::now()->format('Y-m-d H:i:s');
                } elseif ($user->membership_status == "OK") {
                    $current_date = Carbon::now();
                    $dt = Carbon::parse($user->membership_dateBuy);
                    $dt2 = $dt->diffInDays($current_date);

                    $membership_day = $user->membership_day - $dt2;
                    if ($dt2 < $user->membership_day) {
                        $user->membership_day = $membership_day + $package->day;
                    }
                }

                $user->membership_status = "OK";
                $user->save();


                /* ===================== report=======================*/
                $report = new Report();
                $v = new Verta();
                $factor = 'P-' . $v->year . $v->month . $v->day . $v->second . rand(100, 999) . Auth::id();
                $report->factor_number = $factor;
                $report->hairstylist_id = $user->id;
                $report->user_id = $user->id;
                $report->price = $package->price;
                $report->pay_price = $package->price;
                $report->remaining_price = 0;
                $report->authority = $request->SaleReferenceId;
                $report->RefId = $request->RefId;
                $report->pay_status = "OK";
                $report->pay_method = "online";
                $report->type = "package";
                $report->description = " خرید پکیج " . $package->title;
                $report->save();
                /* =====================End report=======================*/
                /*==============SMS================*/
                /*
                 *عزیز پرداخت شما انجام شد و اشتراک شما تمدید شد..
                 * */
                $username = trim(setting()['username_sms']);
                $password = trim(setting()['password_sms']);
                $from = "+983000505";
                $pattern_code = "l87pia7ubh";
                $to = array($user->mobile);
                $input_data = array("name" => $user->name);
                $url = "https://ippanel.com/patterns/pattern?username=" . $username . "&password=" . urlencode($password) . "&from=$from&to=" . json_encode($to) . "&input_data=" . urlencode(json_encode($input_data)) . "&pattern_code=$pattern_code";
                $handler = curl_init($url);
                curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($handler, CURLOPT_POSTFIELDS, $input_data);
                curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($handler);
                /*==============SMS=============*/

                Auth::login($user);
                session()->put('reserve-success', 'پرداخت شما با موفقیت انجام شد');
                session()->put('success-alert', 'پرداخت شما با موفقیت انجام شد');
                return redirect('/profile/buy-package/success');
            }else{
                /* ===================== report=======================*/
                $report = new Report();
                $v = new Verta();
                $factor = 'P-' . $v->year . $v->month . $v->day . $v->second . rand(100, 999) . Auth::id();
                $report->factor_number = $factor;
                $report->hairstylist_id = $user->id;
                $report->user_id = $user->id;
                $report->price = $package->price;
                $report->pay_price = $package->price;
                $report->remaining_price = 0;
                $report->authority = $request->SaleReferenceId;
                $report->RefId = $request->RefId;
                $report->pay_status = "NOK";
                $report->pay_method = "online";
                $report->type = "deposit";
                $report->description = " خرید پکیج " . $package->title;
                $report->save();
                /* =====================End report=======================*/
                /*==============SMS================*/
                /*
                 *عزیز رزرو نوبت انجام نشد.پرداخت شما با شکست مواجه شد.
                 * */
                $username = trim(setting()['username_sms']);
                $password = trim(setting()['password_sms']);
                $from = "+983000505";
                $pattern_code = "6owczd0t5v";
                $to = array($user->mobile);
                $input_data = array("name" => $user->name);
                $url = "https://ippanel.com/patterns/pattern?username=" . $username . "&password=" . urlencode($password) . "&from=$from&to=" . json_encode($to) . "&input_data=" . urlencode(json_encode($input_data)) . "&pattern_code=$pattern_code";
                $handler = curl_init($url);
                curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($handler, CURLOPT_POSTFIELDS, $input_data);
                curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($handler);
                /*==============SMS=============*/

                session()->put('reserve-danger','پرداخت انجام نشد');
                Auth::login($user);
                session()->put('danger-alert', 'پرداخت انجام نشد');
                return redirect('/profile/buy-package/danger');
            }
        }else{

            /* ===================== report=======================*/
            $report = new Report();
            $v = new Verta();
            $factor = 'P-' . $v->year . $v->month . $v->day . $v->second . rand(100, 999) . Auth::id();
            $report->factor_number = $factor;
            $report->hairstylist_id = $user->id;
            $report->user_id = $user->id;
            $report->price = $package->price;
            $report->pay_price = $package->price;
            $report->remaining_price = 0;
            $report->authority = $request->SaleReferenceId;
            $report->RefId = $request->RefId;
            $report->pay_status = "NOK";
            $report->pay_method = "online";
            $report->type = "deposit";
            $report->description = " خرید پکیج " . $package->title;
            $report->save();
            /* =====================End report=======================*/
            /*==============SMS================*/
            /*
             *عزیز رزرو نوبت انجام نشد.پرداخت شما با شکست مواجه شد.
             * */
            $username = trim(setting()['username_sms']);
            $password = trim(setting()['password_sms']);
            $from = "+983000505";
            $pattern_code = "6owczd0t5v";
            $to = array($user->mobile);
            $input_data = array("name" => $user->name);
            $url = "https://ippanel.com/patterns/pattern?username=" . $username . "&password=" . urlencode($password) . "&from=$from&to=" . json_encode($to) . "&input_data=" . urlencode(json_encode($input_data)) . "&pattern_code=$pattern_code";
            $handler = curl_init($url);
            curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($handler, CURLOPT_POSTFIELDS, $input_data);
            curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($handler);
            /*==============SMS=============*/

            Auth::login($user);
            session()->put('reserve-danger', 'پرداخت انجام نشد');
            session()->put('danger-alert', 'پرداخت انجام نشد');
            return redirect('/profile/buy-package/danger');
        }
    }

    public function buy_package_success(Request $request)
    {
        if (session('reserve-success')) {
            $title = "پرداخت موفق | " . setting()['title'];
            $seo_title = "پرداخت موفق," . setting()['seo_title'];
            $seo_content = "پرداخت موفق," . setting()['seo_content'];
            return view('front.profile.package.reserve-success', compact('title', 'seo_title', 'seo_content'));
        }
        return redirect('/profile');
    }

    public function buy_package_danger(Request $request)
    {
        if (session('reserve-danger')) {
            $title = "پرداخت ناموفق | " . setting()['title'];
            $seo_title = "پرداخت ناموفق," . setting()['seo_title'];
            $seo_content = "پرداخت ناموفق," . setting()['seo_content'];
            return view('front.profile.package.reserve-danger', compact('title', 'seo_title', 'seo_content'));

        }
        return redirect('/profile');
    }

    public function timings_reserve()
    {
        if (Auth::user()->HairStylist == "YES") {
            if (Auth::user()->verifire == "YES") {
                $items = DepositRequest::with('hairstylist')->where('hairstylist_id', Auth::id())->orderBy('id', 'desc')->paginate(15);
                $DesksServices= DesksService::where(['user_id'=> Auth::id()])->get();
                $Active = "timings_reserve";
                $title = "زمان های رزرو شده | " . setting()['title'];
                $seo_title = "زمان های رزرو شده," . setting()['seo_title'];
                $seo_content = "زمان های رزرو شده," . setting()['seo_content'];
                if (setting()['Payment_membership']=="ACTIVE"){
                    if (Auth::user()->membership_status == "OK"){
                        return view('front.profile.timings_reserve.index', compact('Active', 'title', 'seo_title', 'seo_content', 'items','DesksServices'));
                    }elseif(Auth::user()->membership_status == "END"){
                        session()->put('error-alert', 'زمان اشتراک شما به پایان رسیده است');
                        return redirect('/profile');
                    }else{
                        session()->put('error-alert', 'برای دستری به بخش های پنل، اشتراک تهیه کنید');
                        return redirect('/profile');
                    }
                }else{
                    return view('front.profile.timings_reserve.index', compact('Active', 'title', 'seo_title', 'seo_content', 'items','DesksServices'));
                }
            } else {
                session()->put('error-alert', 'پنل شما هنوز فعال نشده است');
                return redirect('/profile');
            }
        } else {
            return redirect('/profile');
        }
    }

    public function MellatErrors($err)
    {
        switch ($err) {
            case "11":
                return "شماره كارت نامعتبر است";
                break;
            case "12":
                return "موجودي كافي نيست";
                break;
            case "13":
                return "رمز نادرست است";
                break;
            case "14":
                return "تعداد دفعات وارد كردن رمز بيش از حد مجاز است";
                break;
            case "15":
                return "كارت نامعتبر است";
                break;
            case "16":
                return "دفعات برداشت وجه بيش از حد مجاز است";
                break;
            case "17":
                return "كاربر از انجام تراكنش منصرف شده است";
                break;
            case "18":
                return "تاريخ انقضاي كارت گذشته است";
                break;
            case "19":
                return "مبلغ برداشت وجه بيش از حد مجاز است";
                break;
            case "111":
                return "صادر كننده كارت نامعتبر است";
                break;
            case "112":
                return "خطاي سوييچ صادر كننده كارت";
                break;
            case "113":
                return "پاسخي از صادر كننده كارت دريافت نشد";
                break;
            case "114":
                return "دارنده كارت مجاز به انجام اين تراكنش نيست";
                break;
            case "21":
                return "پذيرنده نامعتبر است";
                break;
            case "23":
                return "خطاي امنيتي رخ داده است";
                break;
            case "24":
                return "اطلاعات كاربري پذيرنده نامعتبر است";
                break;
            case "25":
                return "مبلغ نامعتبر است";
                break;
            case "31":
                return "پاسخ نامعتبر است";
                break;
            case "32":
                return "فرمت اطلاعات وارد شده صحيح نمي باشد";
                break;
            case "33":
                return "حساب نامعتبر است";
                break;
            case "34":
                return "خطاي سيستمي";
                break;
            case "35":
                return "تاريخ نامعتبر است";
                break;
            case "41":
                return "شماره درخواست تكراري است";
                break;
            case "42":
                return "تراكنش Sale يافت نشد";
                break;
            case "43":
                return "قبلا درخواست Verify داده شده است";
                break;
            case "44":
                return "درخواست Verfiy يافت نشد";
                break;
            case "45":
                return "تراكنش Settle شده است";
                break;
            case "46":
                return "تراكنش Settle نشده است";
                break;
            case "47":
                return "تراكنش Settle يافت نشد";
                break;
            case "48":
                return "تراكنش Reverse شده است";
                break;
            case "49":
                return "تراكنش Refund يافت نشد";
                break;
            case "412":
                return "شناسه قبض نادرست است";
                break;
            case "413":
                return "شناسه پرداخت نادرست است";
                break;
            case "414":
                return "سازمان صادر كننده قبض نامعتبر است";
                break;
            case "415":
                return "زمان جلسه كاري به پايان رسيده است";
                break;
            case "416":
                return "خطا در ثبت اطلاعات";
                break;
            case "417":
                return "شناسه پرداخت كننده نامعتبر است";
                break;
            case "418":
                return "اشكال در تعريف اطلاعات مشتري";
                break;
            case "419":
                return "تعداد دفعات ورود اطلاعات از حد مجاز گذشته است";
                break;
            case "421":
                return "IP نامعتبر است";
                break;
            case "51":
                return "تراكنش تكراري است";
                break;
            case "54":
                return "تراكنش مرجع موجود نيست";
                break;
            case "55":
                return "تراكنش نامعتبر است";
                break;
            case "61":
                return "خطا در واريز";
                break;
            default:
                return "خطای مورد نظر در سیستم وجود ندارد!";
        }
    }
}
