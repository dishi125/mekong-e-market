<?php

namespace App\Http\Controllers;

use App\Models\CreditSetting1;
use App\Helpers\CommonHelper;
use App\Models\MainCategory;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class CreditCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $view='credit_category';

    public function index()
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
        $main_categories=MainCategory::orderby('id','desc')->get(['name','id']);

        return view('credit_category.index')
            ->with('view', $this->view)->with(compact('prefered_ids','preferd_req','preferd_cnt','main_categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
        $main_categories=MainCategory::orderby('id','desc')->get(['name','id']);

        return view('credit_category.create')->with('view', $this->view)->with(compact('prefered_ids','preferd_req','preferd_cnt','main_categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $input['hot_species_credit']=trim($request->hot_species_credit);
        $input['mid_species_credit']=trim($request->mid_species_credit);
        $input['low_species_credit']=trim($request->low_species_credit);
        $CreditSetting1 = CreditSetting1::create($input);
        $main_category=MainCategory::where('id',$input['main_category_id'])->update(['can_display_in_credit_setting1'=>0]);
        Flash::success('Credit Setting 1 saved successfully.');

        return redirect(route('credit_category.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $CreditSetting1 = CreditSetting1::find($id);

        if (empty($grade)) {
            Flash::error('Credit Setting 1 not found');

            return redirect(route('credit_category.index'));
        }

        return view('credit_category.show')->with('CreditSetting1', $CreditSetting1);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
        $CreditSetting1=CreditSetting1::find($id);

        if (empty($CreditSetting1)) {
            Flash::error('Credit Setting 1 not found');

            return redirect(route('credit_category.index'));
        }
        $main_categories=MainCategory::orderby('id','desc')->get(['name','id']);

        return view('credit_category.index')->with('CreditSetting1', $CreditSetting1)->with('view', $this->view)->with('edit',0)->with(compact('prefered_ids','preferd_req','preferd_cnt','main_categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $CreditSetting1=CreditSetting1::find($id);
        if (empty($CreditSetting1)) {
            Flash::error('Credit Setting 1 not found');

            return redirect(route('credit_category.index'));
        }
        $input=$request->all();
        $input['hot_species_credit']=trim($request->hot_species_credit);
        $input['mid_species_credit']=trim($request->mid_species_credit);
        $input['low_species_credit']=trim($request->low_species_credit);
        $main_cat=MainCategory::where('id',$CreditSetting1->main_category_id)->update(['can_display_in_credit_setting1'=>null]);
        $CreditSetting1 = $CreditSetting1->update($input);
        $main_cat=MainCategory::where('id',$request->main_category_id)->update(['can_display_in_credit_setting1'=>0]);

        Flash::success('Credit Setting 1 updated successfully.');

        return redirect(route('credit_category.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $CreditSetting1=CreditSetting1::find($id);

        if (empty($CreditSetting1)) {
            Flash::error('Credit Setting 1 not found');

            return redirect(route('credit_category.index'));
        }

        $CreditSetting1->delete($id);

        Flash::success('Credit Setting 1 deleted successfully.');

        return redirect(route('credit_category.index'));
    }

    public function getcreditcategory(Request $request) {

        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
        $CreditSetting1 = CreditSetting1::query();

        if($request->start_date){
            $startDate = CommonHelper::LocalToUtcDate($request->start_date." 00:00:00");
            $CreditSetting1 = $CreditSetting1->where('created_at','>=',$startDate);
        }

        if($request->end_date){
            $endDate = CommonHelper::LocalToUtcDate($request->end_date." 23:59:59");
            $CreditSetting1 = $CreditSetting1->where('created_at',"<=",$endDate);
        }

        if($request->search){
            $CreditSetting1 = $CreditSetting1->whereHas('main_category',function ($mainQuery) use($request) {
                                        $mainQuery->where('name', 'Like', '%' . $request->search . '%');
                                        })
                               ->orwhere('hot_species_credit','Like','%'.$request->search .'%')
                               ->orwhere('mid_species_credit','Like','%'.$request->search .'%')
                               ->orwhere('low_species_credit','Like','%'.$request->search .'%');
        }
        $CreditSetting1 = $CreditSetting1->paginate($request->per_page);

        return view('credit_category.sub_table',compact('CreditSetting1','prefered_ids','preferd_req','preferd_cnt'))->render();
    }
}
