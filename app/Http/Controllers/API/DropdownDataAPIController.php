<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Setting;
use App\Models\SettingPage;
use App\Models\WeightUnit;
use Illuminate\Http\Request;
use App\Models\MainCategory;
use App\Http\Controllers\AppBaseController;
use App\Models\State;
use App\Repositories\StateRepository;

class DropdownDataAPIController extends AppBaseController
{
    public function __construct(StateRepository $stateRepo)
    {
        $this->StateRepository = $stateRepo;
    }

    public function getstate_area()
    {
        $statearea = $this->StateRepository->getstatearea();
        if(empty($statearea)){
            $message='There are not avilable any states or areas.';
            return $this->responseError($message);
        }
        else{
            $message='states and areas retrieved successfully.';
            return $this->responseWithData($statearea->toArray(),$message);
        }
    }

    public function get_mainCategory(){
        $maincatdata=MainCategory::all();
        if(empty($maincatdata)){
            return $this->responseError("There are not avilable any main category.");
        }
        else{
            return $this->responseWithData($maincatdata,'Main categories retrieved successfully.');
        }
    }

    public function get_grade_weight() {

        $grades = Grade::get(['id','name']);
        $weight_unit = WeightUnit::get(['id','unit']);

        $response['grades'] = $grades;
        $response['weight_units'] = $weight_unit;

        return $this->responseWithData($response,'Grades & Weight Units retrieved successfully.');
    }

    public function contactUs() {

        $contactUs = Setting::whereIn('name',array('contact_email','contact_mobile','contact_address'))->get(['name','value']);
        return $this->responseWithData($contactUs,'Contact Details retrieved successfully.');
    }

    public function settingPages() {

        $settingPages = SettingPage::get(['name','description']);
        return $this->responseWithData($settingPages,'Setting Pages retrieved successfully.');
    }
}
