<?php


namespace App\Helpers;

use App\Enums\Type;
use App\Models\Banner;
use App\Models\LoginToken;
use App\Models\Post;
use App\Models\Rating;
use App\Models\CreditTransaction;
use App\Models\SecurityDepositTransaction;
use Carbon\CarbonInterval;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;
use App\Models\UserProfile;
use Carbon\Carbon;

class CommonHelper
{
    public static function LocalToUtcDate($date_time) {
//        when to use user local date-time
        return \Carbon\Carbon::parse($date_time, env('TIME_ZONE'))->setTimezone("UTC")->format('Y-m-d H:i');
    }

    public static function LocalToUtcDateTime($date_time,$time_zone = null) {
        if(! $time_zone) {
            $time_zone = env('TIME_ZONE');
        }
        return \Carbon\Carbon::parse($date_time, $time_zone)->setTimezone("UTC");
    }

    public static function UTCToLocalDateTime($date_time,$time_zone = null) {
        if(! $time_zone) {
            $time_zone = env('TIME_ZONE');
        }
        return \Carbon\Carbon::parse($date_time, "UTC")->setTimezone($time_zone);
    }

    public static function UTCDateTime($date_time) {
        return \Carbon\Carbon::parse($date_time, "UTC");
    }


    public static function addSecondsToUTCDate($date_time,$duration,$type) {
        $date_time = self::UTCDateTime($date_time);
        $end_date = date($type,strtotime('+'.(int)$duration.' seconds',strtotime($date_time)));
        return $end_date;
    }

    public static function convertDurationToSecond($date_time,$duration,$duration_type) {
        $end_date = date('Y-m-d H:i:s',strtotime('+'.(int)$duration.' '.$duration_type,strtotime($date_time)));
        return strtotime($end_date)-strtotime($date_time);
    }

    //convert 25 months to 2 year 1 month
//    public static function convertSecondToDuration($date_time,$duration,$duration_type) {
//        $duration_format = array('minutes' => '%i', 'hours' => '%h', 'days' => '%a', 'months' => '%m');
//        $end_date = date('Y-m-d H:i:s',strtotime('+'.(int)$duration.' seconds',strtotime($date_time)));
//        $start_date_time = date_create($date_time);
//        $end_date_time = date_create($end_date);
//        $interval = date_diff($start_date_time, $end_date_time);
//        return $interval->format($duration_format[$duration_type]);
//    }

    //for convert to direct required duration type
    public static function convertSecondToDuration($date_time,$duration,$duration_type) {
        $duration_format = array('minutes' => 60, 'hours' => 60*60 , 'days' => 60*60*24, 'months' => 2628002.88, 'years' => 2628002.88 * 365);
        $end_date = date('Y-m-d H:i:s',strtotime('+'.(int)$duration.' seconds',strtotime($date_time)));
        $interval = strtotime($end_date)-strtotime($date_time);
        return round($interval / $duration_format[$duration_type]);
    }

    public static function addSecondsToDate($date_time,$duration,$type,$time_zone = null) {
        $date_time = self::UTCToLocalDateTime($date_time,$time_zone);
        $end_date = date($type,strtotime('+'.(int)$duration.' seconds',strtotime($date_time)));
        return $end_date;
    }

    public static function getPricedropArray($start_date,$frame = 180, $total_frame = 900){

        $sec = 0;
        $formatter = array();
        while ($sec < $total_frame) {

            $value = date('H:i',strtotime('+'.(int)$sec.' seconds',strtotime($start_date)));
            array_push($formatter,$value);
            $sec += (int)$frame;
        }
        return $formatter;
    }

    public static function getTimeArray($time_zone,$frame = 900,$is_upcoming = 1){ //1 for upcoming and  0 for ended

        $sec = 0;
        $formatter = array();

        while ($sec < 86400) { //1 divs

            $value = date('H:i', strtotime(date('Y-m-d') . ' +'.$sec. ' seconds'));
            $local_time = Carbon::now($time_zone)->format('H:i');

            if($local_time == "00:00"){
                $local_time = '24:00';
            }

            if(strtotime($local_time) < strtotime($value)){

                if($is_upcoming == 1){
                    $days = 0;// upcoming - past time consider as today's day's time
                }else{
                    $days = -1;
                }
                $temp['date'] = Carbon::now($time_zone)->addDays($days)->format('Y-m-d');

            }else {

                if($is_upcoming == 1){
                    $days = 1;// upcoming - past time consider as next day's time
                }else{
                    $days = 0;
                }
                $temp['date'] = Carbon::now($time_zone)->addDays($days)->format('Y-m-d');
            }

            $temp['time'] = $value;
            array_push($formatter,$temp);
            $sec += (int)$frame;
        }

        return $formatter;
    }

    public static function sendOtp($phone_no) {

        if(env('APP_ENV') == 'local'){
            return 'true';
        }

        $otp = mt_rand(100000,999999);

        $account_sid = env('ACCOUNT_SID');
        $auth_token = env('ACCOUNT_TOKEN');

        $twilio_number = env('MOBILE_NO');

        $client = new Client($account_sid, $auth_token);
        $client->messages->create(
        // Where to send a text message (your cell phone?)
            $phone_no,
            array(
                'from' => $twilio_number,
                'body' => $otp
            )
        );
        return $otp;
    }

    public static function viewprofile($user_profile_id) {

        $user = UserProfile::find($user_profile_id);
        $response = array();
        $reviews = Rating::where('seller_id',$user_profile_id)->count('review');
        $rateavg = Rating::where('seller_id',$user_profile_id)->avg('rate');
        $as_buyer_review = Rating::where('buyer_id',$user_profile_id)->whereNotNull('review')->where('review','!=','')->count('review');

        if($user) {
            $response['id'] = $user->id;
            $response['name'] = $user->name;
            $response['email'] = $user->email;
            $response['password'] = base64_decode($user->password);
            $response['profile_pic'] = $user->profile_pic;
            $response['phone_no'] = str_replace('+60','',$user->phone_no);
            $response['sub_category_id'] = $user->sub_category_id ? $user->sub_category_id : 0;
            $response['user_type'] = $user->user_type ? $user->user_type : 0;
            $response['user_type_name'] = Type::getKey((int)$response['user_type']);
            $response['main_category_id'] = $user->main_category_id ? $user->main_category_id : 0;
            $response['company_name'] = $user->company_name ? $user->company_name : '';
            $response['company_reg_no'] = $user->company_reg_no ? $user->company_reg_no : '';
            $response['company_tel_no'] = $user->company_tel_no ? $user->company_tel_no : '';
            $response['state_id'] = $user->state_id ? $user->state_id : 0;
            $response['area_id'] = $user->area_id ? $user->area_id : 0;
            $response['address'] = $user->address ? $user->address : '';
            $response['company_email'] = $user->company_email ? $user->company_email : '';
            $response['document'] = $user->document ? url('public/' . $user->document) : '';
            $response['preferred_status'] = $user->preferred_status;
            $response['is_approved_status'] = $user->is_approved_status;
            $response['parent_id'] = $user->parent_id;
            $response['job_description'] = $user->job_description ? $user->job_description : '';
            $response['package_id'] = $user->package_id ? $user->package_id : 0;
            $response['rating'] = ($rateavg) ? $rateavg : 0;
            $response['review_count'] = $reviews;
            $response['as_buyer_review'] = $as_buyer_review;
            return $response;
        }
    }

    public static function check_company_detail($user){

        if(isset($user->main_category_id) && isset($user->company_name) && isset($user->company_reg_no) && isset($user->company_tel_no) && isset($user->state_id) && isset($user->area_id) && isset($user->address) && isset($user->company_email) && isset($user->document) && $user->document != ''){
            return true;
        }
        return false;
    }

    public static function user_credit_balance($user_profile_id){

        $user_credit_balance = CreditTransaction::select(
            DB::raw('SUM(CASE
                                    WHEN type = 0 THEN amount
                                    ELSE -amount
                                    END) AS BalanceCredit'))
            ->where('user_profile_id',$user_profile_id)
            ->pluck('BalanceCredit');

        return $user_credit_balance[0] ? $user_credit_balance[0] : 0;
    }

    public static function user_security_deposit($user_profile_id){

        $user_security_deposit = SecurityDepositTransaction::select(
            DB::raw('SUM(CASE
                                WHEN type = 0 THEN amount
                                ELSE -amount
                                END) AS SecurityDeposit'))
            ->where('user_profile_id',$user_profile_id)
            ->pluck('SecurityDeposit');

        return $user_security_deposit[0] ? $user_security_deposit[0] : 0;
    }

    public static function banners($time_zone = "UTC")
    {
        $current_date_time = Carbon::now();
        $banners = Banner::select('banners.*',DB::raw('DATE_ADD(start_date, INTERVAL duration SECOND) as end_date'))
            ->where('status',1)
            ->where('type',0)
            ->whereRaw('"'.$current_date_time.'" BETWEEN start_date AND DATE_ADD(start_date, INTERVAL duration SECOND)')
            ->get();
//        $banners =Banner::get();
        $respons = array();
        foreach ($banners as $banner){

            $temp = array();
            $temp['id'] = $banner->id;
            $temp['name'] = $banner->name;
            $temp['contact'] = $banner->contact;
            $temp['email'] = $banner->email;
            $temp['location'] = $banner->location;
            $temp['price'] = $banner->price;
            $temp['duration'] = $banner->duration;//in seconds
            $temp['start_date'] = CommonHelper::UTCToLocalDateTime($banner->start_date, $time_zone)->format('Y-m-d \a\\t h.ia');
            $temp['end_date'] = CommonHelper::UTCToLocalDateTime($banner->end_date, $time_zone)->format('Y-m-d \a\\t h.ia');
            $temp['banner_link'] = $banner->banner_link;

//            $infoPath = pathinfo(public_path($banner->banner_photo));
//            $extension = $infoPath['extension'];
//            $imgext=array("png","jpg", "jpeg", "jpe", "jif", "jfif", "jfi","tiff","tif","raw","arw","svg","svgz","bmp", "dib");
//            $videoext=array("avi","flv","wmv","mov","mp4");
//            if(in_array($extension,$imgext)){
//                $temp['type']="image";
            $temp['banner_photo'] = $banner->banner_photo ? url('public/' . $banner->banner_photo) : '';
//            }
//            elseif (in_array($extension,$videoext)){
//                $temp['type']="video";
//                $temp['banner_video'] = $banner->banner_photo ? url('public/' . $banner->banner_photo) : '';
//            }
            array_push($respons,$temp);
        }
        return $respons;
    }

    public static function getUpcomingTradeDates($time_array,$local_time,$local_date,$request){ // 2 date ni vachche na data lavva
        if(!$request->time_zone) {
            $request->time_zone = env('TIME_ZONE');
        }
        $filtered = array_filter($time_array, function ($time) use ($local_time) {
            if(strtotime($local_time) < strtotime($time['time'])){
                return $time;
            }
        });
        if($request->time=="23:45"){
            $current_interval=null;
        }
        else {
            $current_interval = current($filtered)['time'];
        }
        $key = array_search($current_interval,array_column($time_array,'time'));
        if($key === false){//if key not found like 23:45 - 00:00
            $prev_key = count($time_array)-1;
            $key = 0;
            if(!isset($request->date)){
                $local_date = $time_array[$prev_key]['date'];
            }
            $start_date = $local_date;
            $end_date = $time_array[$key]['date'];

        }else if($key == 0){
            $prev_key = count($time_array)-1;
            $key = 0;
            if(!isset($request->date)){
                $local_date = $time_array[$prev_key]['date'];
            }
            $start_date = $end_date = $local_date;
//            $end_date = Carbon::now($request->time_zone)->addDays('1')->format('Y-m-d');

        }else{
            $prev_key = $key-1;
            if(!isset($request->date)){
                $local_date = $time_array[$prev_key]['date'];
            }
            $start_date = $end_date = $local_date;
        }

        //default dates for upcoming event
        $startDate = CommonHelper::LocalToUtcDateTime($start_date.' '.$time_array[$prev_key]['time'], $request->time_zone)->format('Y-m-d H:i:s');
        $endDate = CommonHelper::LocalToUtcDateTime($end_date.' '.$time_array[$key]['time'], $request->time_zone)->addSeconds(-1)->format('Y-m-d H:i:s'); //

        $message = '';

        if(!isset($request->date) && isset($request->time)){
            $curdate=Carbon::now($request->time_zone)->format('Y-m-d');
            $startDate = CommonHelper::LocalToUtcDateTime($curdate.' '.$time_array[$prev_key]['time'], $request->time_zone)->format('Y-m-d H:i:s');
        }
        if(!isset($request->time) && isset($request->date)){
            $curdate=Carbon::now($request->time_zone)->format('Y-m-d');
            if ($curdate==$request->date){
                $curdate=Carbon::now($request->time_zone)->format('Y-m-d H:i');
                $startDate = CommonHelper::LocalToUtcDateTime($curdate, $request->time_zone)->format('Y-m-d H:i:s');
//                dd($curdate,$startDate);
            }
            else{
                $endDate = CommonHelper::LocalToUtcDateTime($start_date.' '.$time_array[$prev_key]['time'], $request->time_zone)->addHours(24)->format('Y-m-d H:i:s'); //
            }
        }

        //starting date-time should be greater than current date-time interval
        /*if(strtotime(Carbon::now()) > strtotime($startDate)){
            $message = "Invalid Date/Time";
        }*/
        return array('startDate' => $startDate, 'endDate' => $endDate, 'message' => $message);
    }

    public static function getUpcomingEndedTradeDate($time_array,$local_time,$request)
    {
        if(!$request->time_zone) {
            $request->time_zone = env('TIME_ZONE');
        }

        $filtered = array_filter($time_array, function ($time) use ($local_time) {
            if(strtotime($local_time) < strtotime($time['time'])){
                return $time;
            }
        });

        $current_interval = current($filtered)['time'];
        $key = array_search($current_interval,array_column($time_array,'time'));

        if($key === false){//if key not found like 23:45 - 00:00
            $prev_key = count($time_array)-1;
            if(!isset($request->date)){
                $local_date = $time_array[$prev_key]['date'];
            }
            $start_date = $local_date;

        }else if($key == 0){
            $prev_key = count($time_array)-1;
            if(!isset($request->date)){
                $local_date = $time_array[$prev_key]['date'];
            }
            $start_date = $local_date;

        }else{
            $prev_key = $key-1;
            if(!isset($request->date)){
                $local_date = $time_array[$prev_key]['date'];
            }
            $start_date = $local_date;
        }

        //default dates for upcoming event
        $localStartDate = $start_date.' '.$time_array[$prev_key]['time'];
        $startDate = CommonHelper::LocalToUtcDateTime($localStartDate, $request->time_zone)->format('Y-m-d H:i:s');

        $message = '';
        if($request->is_up_coming){
            //starting date-time should be greater than current date-time interval
            if(strtotime(Carbon::now()) > strtotime($startDate)){
                $message = "Invalid Date/Time";
            }
        }else {
            //starting date-time should be greater than current date-time interval
            if(strtotime(Carbon::now()) < strtotime($startDate)){
                $message = "Invalid Date/Time";
            }
        }
        return array('startDate' => $startDate, 'message' => $message, 'localStartDate' => $localStartDate);
    }

    public static function ratings_reviews($userid)
    {
        $data=Rating::select(DB::raw("avg(rate) as average_rate,count('review') as total_reviews"))
            ->where('seller_id',$userid)
            ->groupby('seller_id')->get();
        $ratingdata=array();
        if ($data->isEmpty()){
            $ratingdata['avg_rate']=0;
            $ratingdata['review_count']=0;
        }
        foreach ($data as $dt){
            $ratingdata['avg_rate']=$dt->average_rate;
            $ratingdata['review_count']=$dt->total_reviews;
        }
        $review_count=$ratingdata['review_count'];
        $avg_rate=$ratingdata['avg_rate'];
        return $ratingdata;
    }

    public static function start_in($date_time){
        $currdt=Carbon::now();
        $st_in_date_time= $currdt->diffInHours($date_time) . ':' . $currdt->diff($date_time)->format('%I:%S');
        return $st_in_date_time;
    }

    public static function end_in($end_date)
    {
        $currdt=Carbon::now();
        $ed_in_date_time= $currdt->diffInHours($end_date) . ':' . $currdt->diff($end_date)->format('%I:%S');
        return $ed_in_date_time;
    }

    public static function number_format_short($n, $precision = 1){
        if ($n < 900) {
            // 0 - 900
            $n_format = number_format($n, $precision);
            $suffix = '';
        } else if ($n < 900000) {
            // 0.9k-850k
            $n_format = number_format($n / 1000, $precision);
            $suffix = 'K';
        } else if ($n < 900000000) {
            // 0.9m-850m
            $n_format = number_format($n / 1000000, $precision);
            $suffix = 'M';
        } else if ($n < 900000000000) {
            // 0.9b-850b
            $n_format = number_format($n / 1000000000, $precision);
            $suffix = 'B';
        } else {
            // 0.9t+
            $n_format = number_format($n / 1000000000000, $precision);
            $suffix = 'T';
        }

        // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
        // Intentionally does not affect partials, eg "1.50" -> "1.50"
        if ( $precision > 0 ) {
            $dotzero = '.' . str_repeat( '0', $precision );
            $n_format = str_replace( $dotzero, '', $n_format );
        }

        return $n_format . $suffix;
    }

    public static function get_trade_count($seller_id){

        //live,upcomin,ended trade counts
        $local_date = \Illuminate\Support\Carbon::now(env('TIME_ZONE'))->format('Y-m-d');
        $local_time = Carbon::now(env('TIME_ZONE'))->format('H:i:s');

        $current_date = CommonHelper::LocalToUtcDateTime($local_date .' '.$local_time)->format('Y-m-d H:i:s');
        return Post::select(DB::raw('count(*) as trade_count,
                    (
                    CASE
                        WHEN `is_pause` = 0 and "'.$current_date.'" > DATE_ADD(date_time, INTERVAL frame SECOND) THEN 0
                        WHEN `is_pause` = 0 and can_show = 1 and "'.$current_date.'" BETWEEN date_time AND DATE_ADD(date_time, INTERVAL frame SECOND) THEN 1
                        WHEN `is_pause` = 1 THEN 2
                        ELSE (
                                CASE WHEN can_show = 1 THEN 3 END
                              )
                    END
                    ) as trade'))
            ->whereHas('product',function ($query) use($seller_id){
                $query->where('user_profile_id',$seller_id);
            })
            ->groupBy('trade')
            ->pluck('trade_count','trade');
    }

    public static function sendPushNotification($user_id, $data)
    {

        // Do not send push notification from localhost
        if (env('APP_ENV') == 'local') {
            Log::info($data);
            Log::info("local environment");
            return true;
        }
        else{
            $tokenArrs = LoginToken::whereIn('user_id', $user_id)->where('token', '!=', '')->get(['token','device_type']);

            if (count($tokenArrs) == 0) {
//                Log::info('no token found');
                return false;
            }
//        dd($tokenArrs->toArray());
            $tokens = array();
            foreach($tokenArrs as $value) {
                $tokens[$value['device_type']][] = $value['token'];
            }
//        dd($tokens);

//            Log::info('----PUSH TOKENS all---');
//            Log::info($tokens);

            if(isset($tokens[1])){
                $ios_fields = array(
                    'registration_ids' => $tokens[1],
                    'data' => $data,
                    'notification' => array(
                        "title" => $data['title'],
                        "body" => $data['message'],
                        "priority" => "high",
                        "sound" => "default",

                    )
                );
//            dd($ios_fields);
                self::sendNotification($ios_fields,"ios");
            }

            if(isset($tokens[0])) {
//            dd($tokens[0]);
                $android_fields = array(
                    'registration_ids' => $tokens[0],
                    'data' => $data
                );
//                dd($android_fields);
                self::sendNotification($android_fields,"android");
            }
            return true;
        }
    }

    public static function sendNotification($data,$type){

        $api_key = env('NOTIFICATION_KEY');
        if($type=="ios"){
            $api_key="AAAATLfbCIw:APA91bGJxtWDctP8gyNGdHlJ47knCtaZZipYvyeFK4XQCPeck4jqUAEDL2T1vFaNLJq8FoiT-QreNX06Y7MYqEeHZmWBH6wdcpJJsv_l9cjhWqhvHPgRNLX7t36cLH49kT4fsQVi3gpw";
        }
//        dd($api_key);
//        $api_key="AAAA7rtn3Bc:APA91bE0ITpzuNMCAJd8X9ODkSyFk3nFSORQEmWMFv0Vc81lqJxrZQNnNQr_SCwC3LhjsfDV_iKBke9iE3Y1j9isvvuZXdiy2hyi6AQJweLC4sqUL-pBxvNs_I7-YjOCljoGHSHU_5T1";
        $headers = array('Authorization: key=' . $api_key, 'Content-Type: application/json');
        $url = 'https://fcm.googleapis.com/fcm/send';

//        dd(json_encode($data));
        $android_ch = curl_init();
        curl_setopt($android_ch, CURLOPT_URL, $url);
        curl_setopt($android_ch, CURLOPT_POST, true);
        curl_setopt($android_ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($android_ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($android_ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($android_ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($android_ch, CURLOPT_POSTFIELDS, json_encode($data));

        $result = curl_exec($android_ch);
//        dd(curl_exec($android_ch));
//        Log::info('--- START '.$type.'--');
//        Log::info($result);
//        Log::info('---END '.$type.'--');

        curl_close($android_ch);
        $data = explode(':', $result);
        $sucess = explode(",", $data[2]);
//        Log::info($sucess);
//        dd($result);
    }

    public static function my_sendpushnotifications($users,$notification_array){
//        dd($users);
// API access key from Google API's Console
        $api_key = env('NOTIFICATION_KEY');
        $url = 'https://fcm.googleapis.com/fcm/send';

        $tokenArrs = LoginToken::whereIn('user_id', $users)->where('token', '!=', '')->get(['token','device_type']);
        if (count($tokenArrs) == 0) {
            Log::info('no token found');
            return false;
        }

        foreach($tokenArrs as $user_data){
            // $registrationIds = ["co2hzichSZKDlafmo5gRyi:APA91bGtg9Kx-5f0VkIEUG4hAFQoXZiCvWzF73XQHrGEBsKypMhgM_bsonXWxJb6KvdQ_TRXyQRiig-KQrYOJpBS5KvsBB10EF9vQCqDC9C4JjqnXxOAqQlEYk7kGLnMZudbuiMbQ0OO"];
            $registrationIds = [$user_data->token];
//            dd($registrationIds);
// prepare the message
            $message = array(
                'title' => $notification_array['title'],
                'body' => $notification_array['message'],
                'vibrate' => 1,
                'sound' => 1
            );

            if($user_data->device_type == 0){
                $fields = array(
                    'registration_ids' => $registrationIds,
                    'data' => $message
                );
            }else if($user_data->device_type == 1){
                $fields = array(
                    'registration_ids' => $registrationIds,
                    'notification' => $message
                );
            }else{
                Log::info('Invalid User Type');
                return false;
            }
//            dd($fields);
            $headers = array(
                'Authorization: key=' . $api_key,
                'Content-Type: application/json'
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
//            dd(json_encode($fields));
            $result = curl_exec($ch);
//            dd($result);
            curl_close($ch);
        }
    }
    public static function frameCreation(){

        $frames = array();
        $day_frame = array();
        $day = 1440;//one day in minute

        for($time = 6; $time < 1440 ; $time++){
            if($day % $time == 0){
                array_push($day_frame,$time);
            }
        }

        foreach($day_frame as $key => $val){
            $interval = $val;
            for($time = 5; $time < $val ; $time++){
                if($interval % $time == 0){
                    if($time == 5) {//it should be multiple of 5 as table has 5 fields of price
                        $show_time = CarbonInterval::seconds($val * 60)->cascade()->forHumans();
                        array_push($frames,array('frame' => $val,'repost'=> $interval/$time,'time'=> $time,'show_time'=> $show_time));
                    }
                }
            }
        }
        return $frames;
    }

//    public static function get_repost_times($frames,$value){
//
//        $out = array_filter($frames, function($val,$key) use($value){
//            return $val['frame'] == $value;
//        },ARRAY_FILTER_USE_BOTH);
//
//        $out = array_values($out);
//        return $out;
//    }
}
