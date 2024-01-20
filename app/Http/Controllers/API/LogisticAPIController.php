<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\LogisticCompany;
use App\Http\Controllers\AppBaseController;
use App\Models\LogisticPhoto;
use App\Helpers\CommonHelper;

class LogisticAPIController extends AppBaseController
{
    public function logistic_companies(Request $request){

        $logistic_companies = LogisticCompany::with('logistic_photos','state','area')
                                ->where('status',1);

        if(isset($request->state_id)){
            $logistic_companies = $logistic_companies->where('state_id',$request->state_id);
        }

        if(isset($request->search)){
            $logistic_companies = $logistic_companies->where('name','LIKE','%'.$request->search.'%');
        }

        if(isset($request->exporter)){
            $logistic_companies = $logistic_companies->where('exporter_status',$request->exporter);
        }

        $logistic_companies = $logistic_companies->get();

        $logistic_company_list = array();
        foreach ($logistic_companies as $logistic_company){

            $temp=array();
            $temp['id'] = $logistic_company->id;
            $temp['name'] = $logistic_company->name;
            $temp['reg_no'] = $logistic_company->reg_no;
            $temp['id_no'] = $logistic_company->id_no;
            $temp['contact'] = $logistic_company->contact;
            $temp['email'] = $logistic_company->email;
            $temp['state_id'] = $logistic_company->state_id;
            $temp['area_id'] = $logistic_company->area_id;
            $temp['state'] = $logistic_company->state->name;
            $temp['area'] = $logistic_company->area->name;
            $temp['address'] = $logistic_company->address;
            $temp['description'] = $logistic_company->description;
            $temp['nursery'] = $logistic_company->nursery;
            $temp['exporter_status'] = $logistic_company->exporter_status;
            $temp['profile'] = $logistic_company->profile ? url('public/' . $logistic_company->profile) : '';
            $temp['status'] = $logistic_company->status;
            $temp['logistic_company_photos'] = $logistic_company->app_logistic_photos;

            array_push($logistic_company_list,$temp);
        }

        $top_banners = CommonHelper::banners();
        $bottom_banners = CommonHelper::banners();
        $response['top_banners'] = $top_banners;
        $response['logistic_company'] = $logistic_company_list;
        $response['bottom_banners'] = $bottom_banners;

        return $this->responseWithData($response,"Logistic companies retrieved successfully.");
    }

}
