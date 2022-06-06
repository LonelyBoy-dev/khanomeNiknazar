<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/cache-clear', function() {
    Artisan::call('cache:clear');
  Artisan::call('config:cache');
  Artisan::call('view:clear');
    dd("cache clear All");
});

Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', function(){
    return Redirect::to('/profile', 301);
});

Route::post('/get_data', [App\Http\Controllers\Front\FrontsController::class, 'get_data']);

/*===============CronJobs=======================*/
Route::get('/membership-CronJobs-HairStylist',[\App\Http\Controllers\Admin\AdminMembershipController::class,'membership_cranJob']);
/*===============CronJobs=======================*/

Route::post('/payment-verify-reserve', [App\Http\Controllers\Front\FrontsController::class, 'payment_verify_reserve']);
Route::post('/profile/package/buy-package-verify', [App\Http\Controllers\Front\ProfileController::class, 'buy_package_verify']);
MenuBuilder::routes();
//===================Front=========================//
Route::get('/', [App\Http\Controllers\Front\FrontsController::class, 'index'])->name('home');

//============blog=============
Route::get('/blogs', [App\Http\Controllers\Front\FrontsController::class, 'blogs']);
Route::get('/blogs/{category?}', [App\Http\Controllers\Front\FrontsController::class, 'blogs']);
Route::get('/blog/{slug}', [App\Http\Controllers\Front\FrontsController::class, 'blog']);
Route::post('/blog/comment/store', [App\Http\Controllers\Front\FrontsController::class, 'blog_comment_store']);
Route::post('/blog/set/view', [App\Http\Controllers\Front\Ajax\FrontAjaxController::class, 'set_view_post'])->name('view.set_view_post');

Route::get('/search', [App\Http\Controllers\Front\FrontsController::class, 'search']);
Route::post('/search/doSearch', [App\Http\Controllers\Front\FrontsController::class, 'doSearch']);
Route::get('/hairstylist/{id}', [App\Http\Controllers\Front\FrontsController::class, 'hairstylist']);
Route::get('/reserve/{id}', [App\Http\Controllers\Front\FrontsController::class, 'reserve']);
Route::post('/reserve/get-service', [App\Http\Controllers\Front\Ajax\FrontAjaxController::class, 'get_service_reserve']);
Route::post('/reserve/get-desks-services-reserve', [App\Http\Controllers\Front\Ajax\FrontAjaxController::class, 'get_desks_services_reserve']);
Route::post('/reserve/get-day-reserve', [App\Http\Controllers\Front\Ajax\FrontAjaxController::class, 'get_day_reserve']);
Route::post('/reserve/get-time-service', [App\Http\Controllers\Front\Ajax\FrontAjaxController::class, 'get_time_service']);
Route::post('/reserve/set-reserve/{id}', [\App\Http\Controllers\Front\FrontsController::class, 'set_reserve']);

Route::get('/contact', [App\Http\Controllers\Front\FrontsController::class, 'contact']);
Route::get('/about', [App\Http\Controllers\Front\FrontsController::class, 'about']);
Route::get('/guide', [App\Http\Controllers\Front\FrontsController::class, 'guide']);
Route::get('/privacy', [App\Http\Controllers\Front\FrontsController::class, 'privacy']);
Route::post('/contact-store', [\App\Http\Controllers\Front\FrontsController::class,'contact_store']);


Auth::routes();
Route::post('/register/register', [App\Http\Controllers\Auth\RegisterUserController::class, 'register']);
Route::get('/register/ConfirmMobile', [App\Http\Controllers\Auth\RegisterUserController::class, 'ConfirmMobile']);
Route::post('/register/ConfirmMobile/checkCode', [App\Http\Controllers\Auth\RegisterUserController::class, 'checkCode']);

Route::get('/register/hairStylist', [App\Http\Controllers\Auth\RegisterHairStylistController::class, 'index']);
Route::post('/register/hairStylist/register', [App\Http\Controllers\Auth\RegisterHairStylistController::class, 'register']);
Route::get('/register/hairStylist/ConfirmMobile', [App\Http\Controllers\Auth\RegisterHairStylistController::class, 'ConfirmMobile']);
Route::post('/register/hairStylist/ConfirmMobile/checkCode', [App\Http\Controllers\Auth\RegisterHairStylistController::class, 'checkCode']);
Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class, 'userLogout'])->name('logout');

Route::post('/hairstylist/comment/store', [App\Http\Controllers\Front\FrontsController::class, 'comment_store']);

Route::post('/password/remember', [App\Http\Controllers\Auth\RegisterHairStylistController::class, 'password_reset']);
Route::get('/password/remember/ConfirmMobileResetPass', [App\Http\Controllers\Auth\RegisterHairStylistController::class, 'ConfirmMobileResetPass']);
Route::post('/password/remember/ConfirmMobile/checkCodeResetPass', [App\Http\Controllers\Auth\RegisterHairStylistController::class, 'checkCodeResetPass']);
Route::get('/password/remember/NewPass', [App\Http\Controllers\Auth\RegisterHairStylistController::class, 'NewPass']);
Route::post('/password/remember/NewPass/store', [App\Http\Controllers\Auth\RegisterHairStylistController::class, 'NewPass_store']);

//===============HairStylist profile==========
Route::group(['middleware' => 'auth'], function () {
    Route::get('/profile', [App\Http\Controllers\Front\ProfileController::class, 'index']);
    Route::post('/profile/reserve/AcceptReserve', [\App\Http\Controllers\Front\Ajax\FrontAjaxController::class, 'Accept_Reserve']);
    Route::post('/profile/reserve/CancelReserve', [\App\Http\Controllers\Front\Ajax\FrontAjaxController::class, 'Cancel_Reserve']);
    Route::post('/profile/reserve/DeleteReserve', [\App\Http\Controllers\Front\Ajax\FrontAjaxController::class, 'Delete_Reserve']);

    Route::get('/profile/setting', [App\Http\Controllers\Front\ProfileController::class, 'setting']);
    Route::post('/profile/setting-store', [App\Http\Controllers\Front\ProfileController::class, 'setting_store']);
    Route::get('/profile/change-password', [App\Http\Controllers\Front\ProfileController::class, 'change_password']);
    Route::post('/profile/change-password-change', [App\Http\Controllers\Front\ProfileController::class, 'change_password_change']);
    Route::post('/profile/service/store', [App\Http\Controllers\Front\ProfileController::class, 'service_store']);
    Route::post('/profile/service/edit', [App\Http\Controllers\Front\ProfileController::class, 'service_edit']);
    Route::post('/profile/service/delete-service', [\App\Http\Controllers\Front\Ajax\FrontAjaxController::class, 'delete_service']);
    Route::post('/profile/service/get-service', [\App\Http\Controllers\Front\Ajax\FrontAjaxController::class, 'get_service_profile']);

    Route::get('/profile/comments', [App\Http\Controllers\Front\ProfileController::class, 'comments']);
    Route::post('/profile/comments/store-answer', [App\Http\Controllers\Front\ProfileController::class, 'store_answer']);
    Route::post('/profile/comments/get-comments', [App\Http\Controllers\Front\Ajax\FrontAjaxController::class, 'get_comments']);
    Route::post('/profile/comments/report-comments', [App\Http\Controllers\Front\Ajax\FrontAjaxController::class, 'report_comment']);

    Route::get('/profile/tickets', [App\Http\Controllers\Front\ProfileController::class, 'tickets']);
    Route::get('/profile/ticket/create', [App\Http\Controllers\Front\ProfileController::class, 'ticket_create']);
    Route::post('/profile/ticket/store', [App\Http\Controllers\Front\ProfileController::class, 'ticket_store']);
    Route::get('/profile/ticket/{id}', [App\Http\Controllers\Front\ProfileController::class, 'ticket_show']);

    Route::post('/profile/Ajax/uploadImageUser', [\App\Http\Controllers\Front\Ajax\FrontAjaxController::class, 'uploadImageUser']);
    Route::post('/profile/remove_gallery', [\App\Http\Controllers\Front\Ajax\FrontAjaxController::class, 'remove_gallery']);

    Route::get('/profile/H-timings', [App\Http\Controllers\Front\ProfileController::class, 'timings_hairdresser']);
    Route::get('/profile/H-timings/{id}', [App\Http\Controllers\Front\ProfileController::class, 'timings_hairdresser']);
    Route::post('/profile/H-timings/store', [App\Http\Controllers\Front\ProfileController::class, 'timings_hairdresser_store']);
    Route::post('/profile/H-timings/store-time', [App\Http\Controllers\Front\ProfileController::class, 'timings_hairdresser_store_time']);
    Route::post('/profile/H-timings/update', [App\Http\Controllers\Front\ProfileController::class, 'timings_hairdresser_update']);
    Route::post('/profile/H-timings/remove', [App\Http\Controllers\Front\Ajax\FrontAjaxController::class, 'timings_hairdresser_remove']);
    Route::post('/profile/H-timings/get-H-timings', [App\Http\Controllers\Front\Ajax\FrontAjaxController::class, 'get_timings_hairdresser']);

    Route::get('/profile/desks-services', [App\Http\Controllers\Front\ProfileController::class, 'desks_services']);
    Route::post('/profile/desks-service/store', [App\Http\Controllers\Front\ProfileController::class, 'desks_service_store']);
    Route::get('/profile/desks-service/{id}', [App\Http\Controllers\Front\ProfileController::class, 'desks_service']);
    Route::post('/profile/desks-service/update', [App\Http\Controllers\Front\ProfileController::class, 'desks_service_update']);
    Route::post('/profile/desks-service/remove', [App\Http\Controllers\Front\Ajax\FrontAjaxController::class, 'desks_service_remove']);
    Route::post('/profile/desks-service/get-desks', [App\Http\Controllers\Front\Ajax\FrontAjaxController::class, 'get_desks_service']);

    Route::get('/profile/favorites', [App\Http\Controllers\Front\ProfileController::class, 'favorites']);
    Route::post('/index/favorite', [App\Http\Controllers\Front\Ajax\FrontAjaxController::class, 'add_remove_favorite']);

    Route::get('/profile/appointments', [App\Http\Controllers\Front\ProfileController::class, 'appointments']);

    Route::get('/profile/my-patients', [App\Http\Controllers\Front\ProfileController::class, 'my_patients']);

    Route::get('/profile/blockList', [App\Http\Controllers\Front\ProfileController::class, 'blockList']);
    Route::post('/profile/blockList/block-Unblock-user', [App\Http\Controllers\Front\Ajax\FrontAjaxController::class, 'block_Unblock_user']);

    Route::get('/profile/reserve-success', [App\Http\Controllers\Front\ProfileController::class, 'reserve_success']);
    Route::get('/profile/reserve-danger', [App\Http\Controllers\Front\ProfileController::class, 'reserve_danger']);

    Route::get('/profile/wallet', [App\Http\Controllers\Front\ProfileController::class, 'wallet']);
    Route::post('/profile/wallet/store-request', [App\Http\Controllers\Front\ProfileController::class, 'store_request_wallet']);
    Route::post('/profile/wallet/get-wallet', [App\Http\Controllers\Front\Ajax\FrontAjaxController::class, 'get_wallet_hairstylist']);

    Route::get('/profile/reports', [App\Http\Controllers\Front\ProfileController::class, 'reports']);

    Route::get('/profile/timings-reserve', [App\Http\Controllers\Front\ProfileController::class, 'timings_reserve']);

    Route::get('/profile/package', [App\Http\Controllers\Front\ProfileController::class, 'packages']);
    Route::post('/profile/package/buy-package', [App\Http\Controllers\Front\ProfileController::class, 'buy_package']);
    Route::get('/profile/buy-package/success', [App\Http\Controllers\Front\ProfileController::class, 'buy_package_success']);
    Route::get('/profile/buy-package/danger', [App\Http\Controllers\Front\ProfileController::class, 'buy_package_danger']);
    Route::post('/profile/package/get-package', [App\Http\Controllers\Front\Ajax\FrontAjaxController::class, 'get_package']);

    Route::post('/profile/app/get-wallet-reserve', [App\Http\Controllers\Front\Ajax\FrontAjaxController::class, 'get_wallet_reserve']);
    Route::post('/profile/get-day-reserved', [App\Http\Controllers\Front\Ajax\FrontAjaxController::class, 'get_day_reserved']);

});

//Login Google
Route::get('login/google', [App\Http\Controllers\Auth\LoginController::class, 'redirectToGoogle'])->name('login.google');
Route::get('login/google/callback', [App\Http\Controllers\Auth\LoginController::class, 'handleGoogleCallback']);

Route::get('admin/login', [App\Http\Controllers\Auth\AdminLoginController::class, 'showLoginForm']);
Route::post('admin/login', [App\Http\Controllers\Auth\AdminLoginController::class, 'login'])->name('admin.login.submit');
Route::group(['middleware' => 'admin'], function () {
    Route::prefix('admin')->group(function () {
        Route::get('/', function(){return Redirect::to('/admin/login');});
        Route::get('/logout', [App\Http\Controllers\Auth\AdminLoginController::class, 'logout'])->name('admin.logout');
        Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'index']);
        Route::get('/users/report', [App\Http\Controllers\Admin\AdminUsersController::class,'report']);
        Route::resource('/users', App\Http\Controllers\Admin\AdminUsersController::class);

        Route::get('/HairStylist/comments/{id}', [App\Http\Controllers\Admin\AdminHairStylistController::class,'comments']);
        Route::resource('/HairStylist', App\Http\Controllers\Admin\AdminHairStylistController::class);
        Route::get('/HairStylist-report-excel', [App\Http\Controllers\Admin\AdminHairStylistController::class,'report_excel']);
        Route::get('/HairStylist/report/{id}', [App\Http\Controllers\Admin\AdminHairStylistController::class,'report']);

        Route::get('/services', [App\Http\Controllers\Admin\AdminHairStylistController::class,'service_index']);
        Route::get('/services/create', [App\Http\Controllers\Admin\AdminHairStylistController::class,'service_create']);
        Route::post('/services/store', [App\Http\Controllers\Admin\AdminHairStylistController::class,'service_store']);
        Route::get('/services/edit/{id}', [App\Http\Controllers\Admin\AdminHairStylistController::class,'service_edit']);
        Route::post('/services/update/{id}', [App\Http\Controllers\Admin\AdminHairStylistController::class,'service_update']);

        Route::get('/tickets/verifire-hairdresser/{id}', [App\Http\Controllers\Admin\AdminTicketsController::class,'verifire_hairdresser']);
        Route::resource('/tickets', App\Http\Controllers\Admin\AdminTicketsController::class);

        /* ======================= Admin Post ========================*/
        Route::get('/admins/permissions/{id}', [App\Http\Controllers\Admin\AdminAdminController::class,'permission']);
        Route::post('/admins/permissions/store/{id}', [App\Http\Controllers\Admin\AdminAdminController::class,'permission_store']);
        Route::get('/profile', [App\Http\Controllers\Admin\AdminAdminController::class,'profile_index']);
        Route::post('/profile/profile_update', [App\Http\Controllers\Admin\AdminAdminController::class,'profile_update']);
        Route::resource('/admins', App\Http\Controllers\Admin\AdminAdminController::class);
        Route::get('/trashed/{data_table?}', [App\Http\Controllers\Admin\AdminTrashedController::class,'index']);

        Route::get('/discountCode', [App\Http\Controllers\Admin\AdminToolsController::class,'discountCode']);
        Route::get('/discountCode/create', [App\Http\Controllers\Admin\AdminToolsController::class,'discountCode_crate']);
        Route::post('/discountCode/store', [App\Http\Controllers\Admin\AdminToolsController::class,'discountCode_store']);

        Route::get('/features-hairstylist', [App\Http\Controllers\Admin\AdminToolsController::class,'features_hairstylist']);
        Route::get('/features-hairstylist/create', [App\Http\Controllers\Admin\AdminToolsController::class,'features_hairstylist_create']);
        Route::post('/features-hairstylist/store', [App\Http\Controllers\Admin\AdminToolsController::class,'features_hairstylist_store']);
        Route::get('/features-hairstylist/edit/{id}', [App\Http\Controllers\Admin\AdminToolsController::class,'features_hairstylist_edit']);
        Route::post('/features-hairstylist/update/{id}', [App\Http\Controllers\Admin\AdminToolsController::class,'features_hairstylist_update']);

        Route::get('/wallets', [\App\Http\Controllers\Admin\AdminWalletsController::class,'wallets']);
        Route::get('/wallets/reports', [App\Http\Controllers\Admin\AdminWalletsController::class,'reports']);
        Route::get('/wallets/excel', [App\Http\Controllers\Admin\AdminWalletsController::class,'excel']);

        Route::get('/specialties-hairstylist', [App\Http\Controllers\Admin\AdminToolsController::class,'specialties_hairstylist']);
        Route::get('/specialties-hairstylist/create', [App\Http\Controllers\Admin\AdminToolsController::class,'specialties_hairstylist_create']);
        Route::post('/specialties-hairstylist/store', [App\Http\Controllers\Admin\AdminToolsController::class,'specialties_hairstylist_store']);
        Route::get('/specialties-hairstylist/edit/{id}', [App\Http\Controllers\Admin\AdminToolsController::class,'specialties_hairstylist_edit']);
        Route::post('/specialties-hairstylist/update/{id}', [App\Http\Controllers\Admin\AdminToolsController::class,'specialties_hairstylist_update']);

        Route::resource('/settings', \App\Http\Controllers\Admin\AdminSettingsController::class);

        Route::get('/about', [App\Http\Controllers\Admin\AdminMoreController::class,'about']);
        Route::get('/guide', [App\Http\Controllers\Admin\AdminMoreController::class,'guide']);
        Route::get('/privacy', [App\Http\Controllers\Admin\AdminMoreController::class,'privacy']);
        Route::post('/more-store', [App\Http\Controllers\Admin\AdminMoreController::class,'store']);

        Route::get('/membership-package',[\App\Http\Controllers\Admin\AdminMembershipController::class,'package']);
        Route::post('/membership-package/store',[\App\Http\Controllers\Admin\AdminMembershipController::class,'package_store']);
        Route::get('/membership-Report',[\App\Http\Controllers\Admin\AdminMembershipController::class,'membership_Report']);


        Route::get('/admin/make_Timing',[\App\Http\Controllers\AdminController::class,'make_Timing']);


    });


    /* ======================= Ajax Admin ========================*/
    Route::post('/admin/Ajax/delete-all-items', [\App\Http\Controllers\Admin\Ajax\AdminAjaxController::class, 'delete_all_items_and_change_status'])->name('Ajax.delete-all-items');
    Route::post('/admin/Ajax/delete-solo-item', [\App\Http\Controllers\Admin\Ajax\AdminAjaxController::class, 'delete_solo_item'])->name('Ajax.delete-solo-item');
    Route::post('/admin/Ajax/uploadImageProfile-New', [\App\Http\Controllers\Admin\Ajax\AdminAjaxController::class, 'uploadimageuser_new'])->name('uploadimageuser-new');
    Route::post('/admin/Ajax/uploadImageUser', [\App\Http\Controllers\Admin\Ajax\AdminAjaxController::class, 'uploadImageUser']);
    Route::post('/admin/Ajax/Change_status_user',[\App\Http\Controllers\Admin\Ajax\AdminAjaxController::class, 'Change_status_user'])->name('Change_status_user');
    Route::post('/admin/Ajax/Change_status_Wallet',[\App\Http\Controllers\Admin\Ajax\AdminAjaxController::class, 'Change_status_Wallet']);
    Route::post('/admin/Ajax/admin-select-address',[\App\Http\Controllers\Admin\Ajax\AdminAjaxController::class, 'admin_select_address'])->name('Ajax.Change-status-user');
    Route::post('/admin/Ajax/Change-status-comments',[\App\Http\Controllers\Admin\Ajax\AdminAjaxController::class, 'Change_status_comments'])->name('Ajax.Change-status-comments');
    Route::post('/admin/Ajax/get-comment',[\App\Http\Controllers\Admin\Ajax\AdminAjaxController::class, 'get_comment'])->name('Ajax.get_comment');
    Route::post('/admin/Ajax/store-answer-comment',[\App\Http\Controllers\Admin\Ajax\AdminAjaxController::class, 'store_answer_comment']);
    Route::post('/admin/Ajax/restore-file',[\App\Http\Controllers\Admin\Ajax\AdminAjaxController::class, 'restore_table']);
    Route::post('/admin/Ajax/update-slider-banner-brand',[\App\Http\Controllers\Admin\Ajax\AdminAjaxController::class, 'update_slider_banner_brand']);
    Route::post('/admin/Ajax/update-membership-package',[\App\Http\Controllers\Admin\Ajax\AdminAjaxController::class, 'update_membership_package']);
    Route::post('/admin/Ajax/get-data-table',[\App\Http\Controllers\Admin\Ajax\AdminAjaxController::class, 'get_data_table']);

    Route::post('/admin/Ajax/uploadImageGalleryUser', [\App\Http\Controllers\Admin\Ajax\AdminAjaxController::class, 'uploadImageGalleryUser']);
    Route::post('/admin/remove_gallery', [\App\Http\Controllers\Admin\Ajax\AdminAjaxController::class, 'remove_gallery']);

});



