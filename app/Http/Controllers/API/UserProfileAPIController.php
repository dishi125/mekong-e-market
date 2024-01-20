<?php

namespace App\Http\Controllers\API;

use App\Enums\Type;
use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use App\Models\LoginToken;
use App\Models\Notifications_api;
use App\Models\Rating;
use App\Models\SubCategory;
use App\Repositories\SubscriptionRepository;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Models\UserProfile;
use App\Repositories\UserProfilesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Types\Null_;
use Twilio\Rest\Client;
use App\Mail\ForgotPassword;
use Illuminate\Support\Facades\Mail;



class UserProfileAPIController extends AppBaseController
{
    private $UserProfilesRepository;
    private $SubscriptionRepository;

    public function __construct(UserProfilesRepository $userprofilesRepo, SubscriptionRepository $subscriptionrepo)
    {
        $this->UserProfilesRepository = $userprofilesRepo;
        $this->SubscriptionRepository = $subscriptionrepo;
    }

    public function send_otp(Request $request)
    {

        $messages = [
            'phone_no.required' => 'Please enter your phone number.'
        ];
        $validator = Validator::make($request->all(), [
            'phone_no' => 'required'
        ], $messages);

        if ($validator->fails()) {
            return $this->responseError($validator->messages()->first());
        }

        $otp = CommonHelper::sendOtp($request->phone_no);

        return $this->responseWithData($otp, 'OTP send successfully.');

    }

    public function register_user(Request $request)
    {
        $messages = [
            'name.required' => 'Please enter your name',
            'email.required' => 'Please enter your e-mail address.',
            'password.required' => 'Please enter your password.',
        ];

        $validation_array = [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'profile' => 'mimes:jpeg,png,jpg',
        ];

        if (!(isset($request->parent_id) && $request->parent_id != 0)) {
            //sub-admin
            $messages['phone_no.required'] = 'Please enter your phone number.';
            $validation_array['phone_no'] = 'required';

        } else {
            //check sub user limit
            $sub_users = $this->SubscriptionRepository->can_add_sub_admin($request->parent_id);
            if(!$sub_users){
                return $this->responseError('Sorry, You are not allow to add new Sub Admin.');
            }
        }

        $validator = Validator::make($request->all(), $validation_array, $messages);

        if ($validator->fails()) {
            return $this->responseError($validator->messages()->first());
        }

        $input['name'] = $request->name;
        $input['email'] = $request->email;
        $input['password'] = base64_encode($request->password);
        $input['parent_id'] = $request->parent_id ? $request->parent_id : 0;
        $input['phone_no'] = $request->phone_no ? "+60" . $request->phone_no : '';

        $is_user_exist = UserProfile::where('email', $input['email'])->exists();

        if (!$is_user_exist) {

            if ($request->file('profile')) {

                $file = $request->file('profile');
                $file_path = 'profile_pics/';
                $file_name = 'Profile_' . time() . rand(11111,99999) . '.' . $file->getClientOriginalExtension();

                $file->move(public_path($file_path), $file_name);
                $input['profile_pic'] = $file_path . $file_name;
                $response['profile_pic']=url('public/' . $input['profile_pic']);
            }

            $this->UserProfilesRepository->create($input);
            $response['name'] = $request->name;
            $response['email'] = $request->email;
            $response['password'] = $request->password;
            $response['phone_no']="+60" . $request->phone_no;
            $message = 'User registered successfully.';

            if (isset($request->parent_id) && $request->parent_id != 0) {
                $sub_users = $this->SubscriptionRepository->getSubUser((object)array('user_profile_id' => $request->parent_id), 0);
                return $this->responseWithData($sub_users, 'create Sub Admin Successfully');
            }

            return $this->responseWithData($response,$message);

        } else {

            if (isset($request->parent_id) && $request->parent_id != 0) {
                $sub_users = $this->SubscriptionRepository->getSubUser((object)array('user_profile_id' => $request->parent_id));
                return $this->responseWithData($sub_users, "Sub-Admin already Exist");
            }
            return $this->responseError('Sorry, User already exist.');
        }

    }

    public function login(Request $request)
    {
        $messages = [
            'email.required' => 'Please enter your e-mail address.',
            'password.required' => 'Please enter your password.',
            'token.required' => 'token is required.',
            'device_type.required' => 'device type is required.'
        ];

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
            'token' => 'required',
            'device_type' => 'required'
        ], $messages);

        if ($validator->fails()) {
            return $this->responseError($validator->messages()->first());
        }

        $email = $request->email;
        $password = base64_encode($request->password);
        $user = UserProfile::where('email', $email)->where('password', $password)->first();

        if (!$user) {
            return $this->responseError('Please check your email-id and password and re-enter again.');
        }

        if($request->sub_category_id) {
            $sub_category = SubCategory::find($request->sub_category_id);

            if(!$sub_category){
                return $this->responseError('Category Not found');
            }
        }

        /*$exist = LoginToken::where('user_id',$user->id)->where('token',$request->token)->first();
        if(!$exist) {
            $loginToken = new LoginToken();
            $loginToken->user_id = $user->id;
            $loginToken->token = $request->token;
            $loginToken->device_type = $request->device_type;
            $loginToken->save();
        }*/

        /*dishita*/
        $exist=LoginToken::where('user_id',$user->id)->first();
        if(!$exist){
            $loginToken = new LoginToken();
            $loginToken->user_id = $user->id;
            $loginToken->token = $request->token;
            $loginToken->device_type = $request->device_type;
            $loginToken->save();
        }
        else{
            $exist->token=$request->token;
            $exist->device_type = $request->device_type;
            $exist->save();
        }
        /*dishita*/

        $user->password = $password;
        if($request->sub_category_id) {
            $user->sub_category_id = $request->sub_category_id;
            $user->main_category_id = $sub_category->main_category_id;
        }
        $user->save();
        $reviews = Rating::where('seller_id',$user->id)->count('review');
        $rateavg = Rating::where('seller_id',$user->id)->avg('rate');
        $as_buyer_review = Rating::where('buyer_id',$user->id)->whereNotNull('review')->where('review','!=','')->count('review');
        $user['rating'] = ($rateavg) ? $rateavg : 0;
        $user['review_count'] = $reviews;
        $user['as_buyer_review'] = $as_buyer_review;
        return $this->responseWithData($user, 'Login successfully.');
    }

    public function forgotPassword(Request $request)
    {
        $user_detail = $request->user_detail;
        $messages = [
            'user_detail.required' => 'Please enter your mobile number or email address.',
        ];

        $validator = Validator::make($request->all(), [
            'user_detail' => 'required',
        ], $messages);

        if ($validator->fails()) {
            return $this->responseError($validator->messages()->first());
        }

        $user_count = UserProfile::where('email', $user_detail)->orWhere('phone_no', $user_detail)->count();
        $error_message = "please enter correct email address or mobile number.";

        if ($user_count == 0) {
            return $this->responseError($error_message);
        }

        $email = filter_var($user_detail, FILTER_VALIDATE_EMAIL);
        $otp = mt_rand(100000, 999999);

        if ($email) {

            $details = [
                'title' => 'Mail  from vasundhara vision',
                'OTP' => $otp
            ];
            Mail::to($user_detail)->send(new ForgotPassword($details));
            return $this->responseWithData($details['OTP'], "Mail send successfully");

        } else {

            //send otp to through sms
            if (!is_numeric(trim($user_detail, "+"))) {
                return $this->responseError($error_message);
            }
            $otp = CommonHelper::sendOtp($user_detail);
            return $this->responseWithData($otp, 'OTP send successfully.');
        }

    }

    public function reset_password(Request $request)
    {

        $messages = [
            'user_detail.required' => 'Please enter your mobile number or email address.',
            'password.required' => 'Please enter New Password.'
        ];

        $validator = Validator::make($request->all(), [
            'user_detail' => 'required',
            'password' => 'required'
        ], $messages);

        if ($validator->fails()) {
            return $this->responseError($validator->messages()->first());
        }

        $user = UserProfile::where('email', $request->user_detail)->orWhere('phone_no', $request->user_detail)->first();

        if (!$user) {
            return $this->responseError("please enter correct email address/mobile number.");
        }

        $password = base64_encode($request->password);
        $user->password = $password;
        $user->save();

        return $this->sendSuccess('Password reset Successfully');

    }

    public function blockUnblockUser(Request $request)
    {

        $messages = [
            'user_profile_id.required' => 'Please enter User Id',
        ];

        $validator = Validator::make($request->all(), [
            'user_profile_id' => 'required',
        ], $messages);

        if ($validator->fails()) {
            return $this->responseError($validator->messages()->first());
        }

        $user = UserProfile::find($request->user_profile_id);
        if (!$user) {
            return $this->responseError("User Not Found");
        }

        if ($user->is_approved_status == 1) {
            $user->is_approved_status = 0;
            $message = "Sub-Admin Block Successfully";
            if ($user->parent_id == 0) {
                $message = "User Block Successfully";
            }

        } else {
            $user->is_approved_status = 1;
            $message = "Sub-Admin Un-Block Successfully";
            if ($user->parent_id == 0) {
                $message = "User Un-Block Successfully";
            }
        }
        $user->save();

        $sub_users = $this->SubscriptionRepository->getSubUser((object)array('user_profile_id' => $user->parent_id));

        return $this->responseWithData($sub_users, $message);
    }

    public function getSubAdmins(Request $request)
    {

        $messages = [
            'user_profile_id.required' => 'Please enter User Id',
        ];

        $validator = Validator::make($request->all(), [
            'user_profile_id' => 'required',
        ], $messages);

        if ($validator->fails()) {
            return $this->responseError($validator->messages()->first());
        }

        //get both status sub-admin
        $sub_users = $this->SubscriptionRepository->getSubUser($request,null);

        return $this->responseWithData($sub_users, "Sub-Admins retrieved successfully ");
    }

    public function update_profile(Request $request)
    {
        $user_profile_id = $request->user_profile_id;
        $profile_pic = $request->file('profile_pic');
        $name = $request->name;
        $email = $request->email;
        $password = $request->password;
        $phone_no = $request->phone_no;
        $user_type = $request->user_type;
        $main_category_id = $request->main_category_id;
        $company_name = $request->company_name;
        $company_reg_no = $request->company_reg_no;
        $office_tel_no = $request->office_tel_no;
        $state_id = $request->state_id;
        $area_id = $request->area_id;
        $address = $request->address;
        $company_email = $request->company_email;
        $ssm_doc = $request->file('ssm_doc');
        $job_description = $request->job_description;
        $preferred = $request->preferred;

        $messages = [
            'user_profile_id.required' => 'Please enter user profile id.',
            'name.required' => 'Please enter your name.',
            'email.required' => 'Please enter your email address.',
            'password.required' => 'Please enter password.',
            'phone_no.required' => 'Please enter your mobile number.',
            'user_type.required' => 'Please select user type.',
            'preferred.required' => 'Please enter preferred status.',
        ];
        $validation =  [
            'user_profile_id' => 'required',
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'phone_no' => 'required',
            'user_type' => 'required',
            'preferred' => 'required'
        ];

        if($request->user_type != Type::Buyer){

            $messages['main_category_id.required'] = 'Please enter Main Category.';
            $messages['company_name.required'] = 'Please enter Company.';
            $messages['company_reg_no.required'] = 'Please enter Company Registration Number.';
            $messages['office_tel_no.required'] = 'Please enter Company Telephone Number.';
            $messages['state_id.required'] = 'Please enter Company State.';
            $messages['area_id.required'] = 'Please enter Company Area.';
            $messages['address.required'] = 'Please enter Company address.';
            $messages['company_email.required'] = 'Please enter Company Email-Id.';

            $validation['main_category_id'] = 'required';
            $validation['company_name'] = 'required';
            $validation['company_reg_no'] = 'required';
            $validation['office_tel_no'] = 'required';
            $validation['state_id'] = 'required';
            $validation['area_id'] = 'required';
            $validation['address'] = 'required';
            $validation['company_email'] = 'required';
        }

        $validator = Validator::make($request->all(),$validation, $messages);

        if ($validator->fails()) {
            return $this->responseError($validator->messages()->first());
        }

        $user_profile = UserProfile::find($user_profile_id);
        if (!$user_profile) {
            return $this->responseError("Your profile not found please register first.");
        }

        if(!(isset($user_profile->document) && $user_profile->document != '') && $request->user_type != Type::Buyer){
            $validator = Validator::make($request->all(),['ssm_doc' => 'required'], ["ssm_doc.required" => "Please Attach SSM Doc."]);
            if ($validator->fails()) {
                return $this->responseError($validator->messages()->first());
            }
        }

        if (!empty($profile_pic)) {
            $ext = $profile_pic->getClientOriginalExtension();
            $ext = strtolower($ext);
            //$all_ext = array("png", "jpg", "jpeg", "jpe", "jif", "jfif", "jfi", "tiff", "tif", "raw", "arw", "svg", "svgz", "bmp");
            $all_ext = array("png", "jpg", "jpeg", "jpe");
            if (in_array($ext, $all_ext)) {
                $file_path = 'profile_pics/';
                $destinationPath = public_path('profile_pics');
                $imagePath = 'Profile_' . time() . rand(11111,99999) . "." . $profile_pic->getClientOriginalExtension();
                $profile_pic->move($destinationPath, $imagePath);
                $user_profile->profile_pic = $file_path . $imagePath;
            } else {
                $message = 'Invalid type of image.';
                return $this->responseError($message);
            }
        }

        if (!empty($ssm_doc)) {
            $ext = $ssm_doc->getClientOriginalExtension();
            $ext = strtolower($ext);
            $all_ext = array("doc", "docm", "docx", "html", "htm", "odt", "pdf", "xls", "xlsx", "ods", "ppt", "pptx", "txt", "rtf", "wps", "xml", "xps", "csv", "xlsm", "wpd", "tex","png", "jpg", "jpeg", "jpe","bmp");
            if (in_array($ext, $all_ext)) {
                $file_path = 'ssm_documents/';
                $destinationPath = public_path('ssm_documents');
                $imagePath = 'ssm_document_'. time() . rand(11111,99999) . "." . $ssm_doc->getClientOriginalExtension();
                $ssm_doc->move($destinationPath, $imagePath);
                $user_profile->document = $file_path . $imagePath;
            } else {
                $message = 'Invalid type of document.';
                return $this->responseError($message);
            }
        }

        $user_profile->name = $name;
        $user_profile->email = $email;
        $user_profile->phone_no = "+60" . $phone_no;
        $user_profile->user_type = $user_type;
        $user_profile->main_category_id = $main_category_id;
        $user_profile->company_name = $company_name;
        $user_profile->company_reg_no = $company_reg_no;
        $user_profile->company_tel_no = $office_tel_no;
        $user_profile->state_id = $state_id;
        $user_profile->area_id = $area_id;
        $user_profile->address = $address;
        $user_profile->company_email = $company_email;
        $user_profile->job_description = $job_description;
//                if(isset($preferred)){
        $user_profile->preferred_status = $preferred;
        if($user_profile->preferred_status==1) {
            $user_profile->is_seen_preferred = 0;
        }
        else{
            $user_profile->is_seen_preferred=null;
        }
//                }
        if($password) {
            $user_profile->password = base64_encode($password);
        }
        $user_profile->save();

        return $this->sendSuccess("Thanks for your submission, our admin will verify and approve your account for further action.");
    }

    public function view_profile(Request $request){
        $messages = [
            'user_profile_id.required' => 'Please enter User Id.',
        ];

        $validator = Validator::make($request->all(), [
            'user_profile_id' => 'required',
        ], $messages);

        if ($validator->fails()) {
            return $this->responseError($validator->messages()->first());
        }
        $response = CommonHelper::viewprofile($request->user_profile_id);
        if($response!=null)
            return $this->responseWithData($response, 'Your profile retrieved successfully.');
        else
            return $this->responseError("Your profile not found please register first.");
    }

    public function contactUsSave(Request $request) {
        $messages = [
            'user_profile_id.required' => 'Please enter User Id.',
            'email.required' => 'Please enter Mail-Id.',
            'email.email' => 'Please enter Proper Mail-Id.',
            'message.required' => 'Please enter Message.',
        ];

        $validator = Validator::make($request->all(), [
            'user_profile_id' => 'required',
            'email' => 'required|email',
            'message' => 'required',
        ], $messages);

        if ($validator->fails()) {
            return $this->responseError($validator->messages()->first());
        }

        ContactUs::firstOrCreate([
            'user_profile_id' => $request->user_profile_id,
            'email' => $request->email,
            'message' => $request->message
        ]);

        return $this->sendSuccess('Message Sent Successfully');
    }

    public function display_notifications(Request $request){
        $messages = [
            'user_profile_id.required' => 'Please enter User Id.',
        ];
        $validator = Validator::make($request->all(), [
            'user_profile_id' => 'required',
        ], $messages);
        if ($validator->fails()) {
            return $this->responseError($validator->messages()->first());
        }
        $notifications=Notifications_api::where('to_user_id',$request->user_profile_id)->orderby('id','desc')->get();
        if($notifications->count()<=0){
            return $this->responseError("No notifications found.");
        }
        $notifyarr=array();
        foreach ($notifications as $notification){
            if($notification->desc=="purchase"){
                $temp=array();
                $temp['description']=$notification->user->name." buy ".$notification->post->product->product_name." at price ".$notification->bid_price;
                array_push($notifyarr,$temp);
            }
            elseif ($notification->desc=="fast_buy"){
                $temp=array();
                $temp['description']=$notification->user->name." fast buy ".$notification->post->product->product_name." at price ".$notification->bid_price;
                array_push($notifyarr,$temp);
            }
            elseif ($notification->desc=="rating"){
                $temp=array();
                $temp['description']=$notification->user->name." give rate ".$notification->rating->rate." and review '".$notification->rating->review."' on ".$notification->post->product->product_name;
                array_push($notifyarr,$temp);
            }
        }
        return $this->responseWithData($notifyarr, 'Your notifications retrieved successfully.');
    }

    public function update_subadminprofile(Request $request){
        $user_profile_id = $request->user_profile_id;
        $profile_pic = $request->file('profile_pic');
        $name = $request->name;
        $email = $request->email;
        $messages = [
            'user_profile_id.required' => 'Please enter user profile id.',
            'name.required' => 'Please enter your name.',
            'email.required' => 'Please enter your email address.',
        ];
        $validator =  Validator::make($request->all(), [
            'user_profile_id' => 'required',
            'name' => 'required',
            'email' => 'required',
        ], $messages);
        if ($validator->fails()) {
            return $this->responseError($validator->messages()->first());
        }
        $user_profile = UserProfile::find($user_profile_id);
        if (!$user_profile) {
            return $this->responseError("Your profile not found please register first.");
        }
        if (!empty($profile_pic)) {
            $ext = $profile_pic->getClientOriginalExtension();
            $ext = strtolower($ext);
            //$all_ext = array("png", "jpg", "jpeg", "jpe", "jif", "jfif", "jfi", "tiff", "tif", "raw", "arw", "svg", "svgz", "bmp");
            $all_ext = array("png", "jpg", "jpeg", "jpe");
            if (in_array($ext, $all_ext)) {
                $file_path = 'profile_pics/';
                $destinationPath = public_path('profile_pics');
                $imagePath = 'Profile_' . time() . rand(11111,99999) . "." . $profile_pic->getClientOriginalExtension();
                $profile_pic->move($destinationPath, $imagePath);
                $user_profile->profile_pic = $file_path . $imagePath;
            } else {
                $message = 'Invalid type of image.';
                return $this->responseError($message);
            }
        }
        $user_profile->name = $name;
        $user_profile->email = $email;
        $user_profile->save();
        return $this->sendSuccess("Thanks for your submission, our admin will verify and approve your account for further action.");
    }
}



