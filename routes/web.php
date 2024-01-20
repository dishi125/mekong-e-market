<?php

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

Route::get('/', function () {
    return redirect(route('login'));
});

Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');


Auth::routes(['verify' => true]);
Route::get('/register', function () {
    return redirect(route('login'));
});
Route::get('/home', 'HomeController@index')->middleware('verified');

Route::group(['middleware' => 'auth'], function () {
    Route::resource('mainCategories', 'MainCategoryController');
    Route::resource('subCategories', 'SubCategoryController');
    Route::resource('species', 'SpecieController');
    Route::resource('states', 'StateController');
    Route::resource('areas', 'AreaController');
    Route::resource('banners', 'BannerController');
    Route::resource('bannerPackages', 'BannerPackageController');
    Route::resource('notifications', 'notificationController');

    Route::resource('userProfiles', 'UserProfilesController');

    Route::resource('bannerPackages', 'BannerPackageController');
    Route::resource('logisticCompanies', 'LogisticCompanyController');

    Route::resource('subscriptions', 'SubscriptionController');

    Route::resource('creditPackages', 'CreditPackageController');
    Route::post('user_status_change/{id}', 'UserProfilesController@status_change');
    Route::post('creditPackages_status_change/{id}', 'CreditPackageController@creditPackages_status_change');
    Route::post('subscription_package_status_change/{id}', 'SubscriptionController@subscription_package_status_change');
    Route::post('change-password', 'UserSettingsControllers@changepassword')->name('change.password');
    Route::post('get_user', 'UserProfilesController@get_user');
    Route::post('frame', 'ProductController@frame')->name('frame');

    Route::post('frame_setcreditrm', 'ProductController@frame_setcreditrm')->name('frame_setcreditrm');

    Route::post('set_credit', 'CreditManagementController@set_credit')->name('set_credit');

    Route::get('userSubscription', 'SubscriptionController@userSubscription')->name('user.subscribe');

    Route::get('preferredreqUserProfiles', 'UserProfilesController@preferredreqUserProfiles')->name('user.preferredreq');

    Route::get('top-up', 'CreditPackageController@creditBalance')->name('top-up.creditBalance');
    Route::get('liveTrade', 'ProductController@LiveTrade')->name('live.trade');
    Route::get('upcomingTrade', 'ProductController@UpcomingTrade')->name('upcoming.trade');
    Route::get('endedTrade', 'ProductController@EndedTrade')->name('ended.trade');
    Route::get('subcategory/{id}', 'SpecieController@subcategorylist');
    Route::get('userSettings', 'UserSettingsControllers@index');
    Route::get('livedetail/{id}', 'ProductController@live_detail');
    Route::get('upcomingdetail/{id}', 'ProductController@upcoming_detail');
    Route::get('endeddetail/{id}', 'ProductController@ended_detail');

    Route::get('Profile/deals/live/{id}', 'UserProfilesController@live_deals')->name('user.live.deal');
    Route::get('Profile/deals/upcoming/{id}', 'UserProfilesController@upcomig_deals')->name('user.upcoming.deal');
    Route::get('Profile/deals/paused/{id}', 'UserProfilesController@paused_deals')->name('user.paused.deal');
    Route::get('Profile/deals/ended/{id}', 'UserProfilesController@ended_deals')->name('user.ended.deal');
    Route::get('Profile/purchase/{id}', 'UserProfilesController@purchase')->name('user.purchase.deal');
    Route::get('Profile/rating/buyer/{id}', 'UserProfilesController@asbuyer')->name('user.rating.buyer');
    Route::get('Profile/rating/seller/{id}', 'UserProfilesController@asseller')->name('user.rating.seller');
    Route::resource('creditManagements', 'CreditManagementController');


    //Status
    Route::post('statusChange/{id}', 'MekongController@status_change');

    //ajax call
    Route::get('ajax/main_categories', 'MainCategoryController@get_main_category');
    Route::get('ajax/sub_categories', 'SubCategoryController@get_sub_category');
    Route::get('ajax/species', 'SpecieController@get_species');
    Route::get('ajax/states', 'StateController@get_states');
    Route::get('ajax/areas', 'AreaController@get_areas');
    Route::get('ajax/banners', 'BannerController@get_banners');
    Route::get('ajax/banner_packages', 'BannerPackageController@get_banner_packages');
    Route::get('ajax/subscriptions', 'SubscriptionController@get_subscriptions');
    Route::get('ajax/user_subscriptions', 'SubscriptionController@get_user_subscriptions');
    Route::get('ajax/creditBalances', 'CreditPackageController@get_credit_balance');
    Route::get('ajax/credit_packages', 'CreditPackageController@get_credit_packages');
    Route::get('ajax/user_profiles', 'UserProfilesController@get_user_profiles');
    Route::get('ajax/notifications', 'notificationController@get_notifications');
    Route::get('ajax/logistic_companies', 'LogisticCompanyController@get_logistic_companies');
    Route::get('ajax/credit_managements', 'CreditManagementController@get_credit_managements');

    Route::get('ajax/user_preferredreq', 'UserProfilesController@get_user_preferredreq');

    //product-management
    Route::get('ajax/live', 'ProductController@get_live_products');
    Route::get('ajax/upcoming', 'ProductController@get_upcoming_products');
    Route::get('ajax/ended', 'ProductController@get_ended_products');
    //export sales report
    Route::get('salesReportExport', 'ProductController@salesReportExport')->name('salesReportExport');

    //seller
    Route::get('ajax/profile/live_deals', 'ProductController@get_live_products');
    Route::get('ajax/profile/upcomig_deals', 'ProductController@get_upcoming_products');
    Route::get('ajax/profile/paused_deals', 'UserProfilesController@get_paused');
    Route::get('ajax/profile/ended_deals', 'ProductController@get_ended_products');
    Route::get('ajax/profile/purchase_deals', 'UserProfilesController@get_purchase');


    //product terms
    Route::resource('grades', 'GradeController');
    Route::get('ajax/grades', 'GradeController@getGrades');
    Route::resource('weightUnits', 'WeightUnitController');
    Route::get('ajax/weight_units', 'WeightUnitController@getWeightUnits');
    Route::resource('credit_category', 'CreditCategoryController');
    Route::get('ajax/credit_category', 'CreditCategoryController@getcreditcategory');
    Route::resource('credit_setting2', 'CreditSetting2Controller');
    Route::get('ajax/credit_setting2', 'CreditSetting2Controller@getcreditsetting2');
    Route::get('credit_setting2_subcats/{main_cat_id}', 'CreditSetting2Controller@subcategorylist');

    //setting page
    Route::resource('settingPages', 'SettingPageController');
    Route::get('ajax/setting_pages', 'SettingPageController@settingPages');
    Route::resource('settings', 'SettingController');
    Route::get('ajax/settings', 'SettingController@getSettings');

    //contact-us
    Route::resource('contactuses', 'ContactUsController');
    Route::get('ajax/contactuses', 'ContactUsController@getContactuses');

    //test-notification
    Route::post('notifications/test', 'notificationController@notificationTest')->name('notifications.test');

    Route::post('set_allnotificationdata','HomeController@set_allnotificationdata');
    Route::post('change_nofification_status','HomeController@change_nofification_status');

    Route::post('set_preferred_approved_status','UserProfilesController@set_preferred_approved_status');

    Route::get('area/{id}', 'LogisticCompanyController@arealist');
});












