<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//per min 60 requests are allowed to following apis
Route::group(['middleware'=>['throttle:60,1']], function () {

    //user_profile
    Route::post('/send_otp','UserProfileAPIController@send_otp');
    Route::post('/register_user', 'UserProfileAPIController@register_user');
    Route::post('/login','UserProfileAPIController@login');
    Route::post('/forgot_password','UserProfileAPIController@forgotPassword');
    Route::post('/reset_password','UserProfileAPIController@reset_password');
    Route::post('/update_profile','UserProfileAPIController@update_profile');
    Route::post('/view_profile','UserProfileAPIController@view_profile');

    //get data for dropdown
    Route::get('/state_area','DropdownDataAPIController@getstate_area');
    Route::get('/display_categories','CategoriesAPIController@get_categories');
    Route::get('/get_user_type','CategoriesAPIController@get_user_type');
    Route::get('/get_grade_weight_unit','DropdownDataAPIController@get_grade_weight');

    //contact-detail
    Route::get('/contact_us','DropdownDataAPIController@contactUs');
    Route::post('/contact_us','UserProfileAPIController@contactUsSave');
    Route::get('/setting_pages','DropdownDataAPIController@settingPages');

    //post
    Route::post('/add_post','PostAPIController@addPost');
    Route::post('/view_post','PostAPIController@viewPost');
    Route::post('/update_post','PostAPIController@updatePost');

    //Credit
    Route::post('/my_credit','SubscriptionAPIController@myCredit');
    Route::post('/purchase_credit','SubscriptionAPIController@purchaseCredit');

    //subscription
    Route::post('/subscription_packages','SubscriptionAPIController@getSubscriptionPackages');
    Route::post('/subscribe_package','SubscriptionAPIController@subscribePackage');
    Route::post('/my_subscriptions','SubscriptionAPIController@mySubscriptions');

    //sub admin
    Route::post('/create_sub_user','UserProfileAPIController@register_user');//method is register_user but response will different
    Route::post('/get_sub_admins','UserProfileAPIController@getSubAdmins');
    Route::post('/block_unblock_sub_admin','UserProfileAPIController@blockUnblockUser');
    Route::post('/update_subadminprofile','UserProfileAPIController@update_subadminprofile');

    //get post
    Route::post('/get_ended_trade_posts','PostAPIController@ended_trade_posts');
    Route::post('/get_favourite_posts','PostAPIController@allFavouritePosts');

    //get seller post
    Route::post('/get_seller_posts','PostAPIController@allSellerPosts');
    Route::post('/get_seller_ended_posts','PostAPIController@getSellerEndedPosts');
    Route::post('/delete_post','PostAPIController@deletePost');
    Route::post('/play_pause_post','PostAPIController@playPausePost');

    //favourite post
    Route::post('/favourite_unfavourite_post','FavouritesAPIController@favouriteUnFavourite');

    //buy now
    Route::post('/buy_now','PostAPIController@buyNow');
    Route::post('/pay_out','PostAPIController@payOut');
    Route::post('/purchase_history','PostAPIController@purchaseHistory');

    //rating
    Route::post('/give_rating','RatingAPIController@give_rating');
    Route::post('/reviews_as_buyer_seller','RatingAPIController@view_review_as_buyer_seller');
    Route::get('/get_top_seller','RatingAPIController@get_top_seller');

    //logistic company
    Route::post('/logistic_companies','LogisticAPIController@logistic_companies');

    Route::post('/display_notifications','UserProfileAPIController@display_notifications');

    Route::post('/add_report','PostAPIController@add_report'); //for report particular post by user
    Route::post('/report_logistic_company','PostAPIController@report_logistic_company'); //for report particular logistic company by user

});

//there is no throttle for following apis as it is used in node api and in interval
//post
Route::post('/get_upcoming_trade_post','PostAPIController@upcomingTradePost'); //all upcoming slot posts
//seller post
Route::post('/get_seller_upcoming_posts','PostAPIController@getSellerUpcomingPosts'); //all upcoming slots posts of seller
//time array for live & upcoming post
Route::post('/get_time_array','PostAPIController@getTimeArray');

