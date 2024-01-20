<?php

namespace App\Http\Controllers\API;

use App\Enums\Type;
use App\Http\Controllers\Controller;
use App\Models\CreditSetting2;
use Illuminate\Http\Request;
use App\Models\MainCategory;
use App\Repositories\MainCategoryRepository;
use App\Http\Controllers\AppBaseController;

class CategoriesAPIController extends AppBaseController
{

    public function __construct( MainCategoryRepository $maincategoryRepo )
    {
        $this->MainCategoryRepository = $maincategoryRepo;
    }
    public function get_categories()
    {
        $categories = $this->MainCategoryRepository->getCategoriesData();
        if(empty($categories)){
            $message='categories not found.';
            return $this->responseError($message);
        }
        else{
            $response=array();
            $message='Categories retrieved successfully.';
            $creditset2=CreditSetting2::where('status',1)->orderBy('id','asc')->get();
            $transaction_fees=array();
            foreach ($creditset2 as $cset2){
                $explodsub=explode(",",$cset2->sub_categories);
                foreach ($explodsub as $cs){
                    $temp=array();
                    $temp['main_category_id']=$cset2->main_category_id;
                    $temp['sub_category_id']=(int)$cs;
                    $temp['transaction_fee']=$cset2->credit_per_transaction;
                    array_push($transaction_fees,$temp);
                }
            }
//            $response['transaction_fees']=$transaction_fees;
            return $this->responseWith2Data($categories->toArray(),$transaction_fees,$message);
        }
    }
    public function get_user_type(){

        $types = Type::toArray();
        $response = array();
        foreach ($types as $key => $value){
            $response[] = array(
                'type'=> $key,
                'id'=> $value,
            );
        }

        $message='User Type retrieved successfully.';
        return $this->responseWithData($response,$message);
    }
}
