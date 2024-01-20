<?php

namespace App\Http\Controllers;

use App\Models\CreditSetting1;
use App\Models\Area;
use App\Models\Banner;
use App\Models\BannerPackage;
use App\Models\CreditSetting2;
use App\Models\Grade;
use App\Models\LogisticCompany;
use App\Models\MainCategory;
use App\Models\SettingPage;
use App\Models\Specie;
use App\Models\State;
use App\Models\SubCategory;
use App\Models\UserProfile;
use App\Models\WeightUnit;
use Illuminate\Http\Request;

class MekongController extends Controller
{
    public function status_change(Request $request, $id)
    {
        if ($request->action == 'area') {
            $data = Area::find($id);


            if (empty($data)) {
                return ["status" => 0, "data" => "Area Not Found"];
            }
            $data->status = $data->status == 1 ? 0 : 1;
            $data->save();
            return ["status" => 1];
        } elseif ($request->action == 'state') {
            $data = State::find($id);


            if (empty($data)) {
                return ["status" => 0, "data" => "Area Not Found"];
            }
            $data->status = $data->status == 1 ? 0 : 1;
            $data->save();
            return ["status" => 1];
        } elseif ($request->action == 'main_categories') {
            $data = MainCategory::find($id);


            if (empty($data)) {
                return ["status" => 0, "data" => "Area Not Found"];
            }
            $data->status = $data->status == 1 ? 0 : 1;
            $data->save();
            return ["status" => 1];
        }
        elseif ($request->action == 'sub_categories') {
            $data = SubCategory::find($id);


            if (empty($data)) {
                return ["status" => 0, "data" => "Area Not Found"];
            }
            $data->status = $data->status == 1 ? 0 : 1;
            $data->save();
            return ["status" => 1];
        }
        elseif ($request->action == 'species') {
            $data = Specie::find($id);


            if (empty($data)) {
                return ["status" => 0, "data" => "Area Not Found"];
            }
            $data->status = $data->status == 1 ? 0 : 1;
            $data->save();
            return ["status" => 1];
        }
        elseif ($request->action == 'banner_packages') {
            $data = BannerPackage::find($id);


            if (empty($data)) {
                return ["status" => 0, "data" => "Area Not Found"];
            }
            $data->status = $data->status == 1 ? 0 : 1;
            $data->save();
            return ["status" => 1];
        }
        elseif ($request->action == 'banners') {
            $data = Banner::find($id);


            if (empty($data)) {
                return ["status" => 0, "data" => "Area Not Found"];
            }
            $data->status = $data->status == 1 ? 0 : 1;
            $data->save();
            return ["status" => 1];
        }
        elseif ($request->action == 'logistic_companies') {
            $data = LogisticCompany::find($id);


            if (empty($data)) {
                return ["status" => 0, "data" => "Area Not Found"];
            }
            $data->status = $data->status == 1 ? 0 : 1;
            $data->save();
            return ["status" => 1];
        }
        elseif ($request->action == 'grade') {
            $data = Grade::withTrashed()->find($id);


            if (empty($data)) {
                return ["status" => 0, "data" => "Grade Not Found"];
            }
            if(isset($data->deleted_at)){
                $data->deleted_at = null;
                $data->save();

            } else {
                $data->delete();
            }
            return ["status" => 1];
        }
        elseif ($request->action == 'unit_weight') {
            $data = WeightUnit::withTrashed()->find($id);


            if (empty($data)) {
                return ["status" => 0, "data" => "Grade Not Found"];
            }
            if(isset($data->deleted_at)){
                $data->deleted_at = null;
                $data->save();

            } else {
                $data->delete();
            }
            return ["status" => 1];
        }
        elseif ($request->action == 'credit_category') {
            $data = CreditSetting1::find($id);


            if (empty($data)) {
                return ["status" => 0, "data" => "Credit Setting 1 Not Found"];
            }
            $data->status = $data->status == 1 ? 0 : 1;
            $data->save();
            return ["status" => 1];
        }
        elseif ($request->action == 'credit_setting2') {
            $data = CreditSetting2::find($id);


            if (empty($data)) {
                return ["status" => 0, "data" => "Credit Setting 2 Not Found"];
            }
            $data->status = $data->status == 1 ? 0 : 1;
            $data->save();
            return ["status" => 1];
        }
        elseif ($request->action == 'setting_page') {
            $data = SettingPage::withTrashed()->find($id);


            if (empty($data)) {
                return ["status" => 0, "data" => "Grade Not Found"];
            }
            if(isset($data->deleted_at)){
                $data->deleted_at = null;
                $data->save();

            } else {
                $data->delete();
            }
            return ["status" => 1];
        }

    }
}
