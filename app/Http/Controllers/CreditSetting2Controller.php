<?php

namespace App\Http\Controllers;

use App\Models\CreditSetting1;
use App\Helpers\CommonHelper;
use App\Models\CreditSetting2;
use App\Models\MainCategory;
use App\Models\SubCategory;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class CreditSetting2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $view='credit_setting2';

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

        return view('credit_category.index')->with('view', $this->view)->with(compact('prefered_ids','preferd_req','preferd_cnt','main_categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        $input = $request->all();

        $input['main_category_id']=$request->main_category_id;
        $input['spices_category']=$request->spices_category;
        if($input['spices_category']=="Hot"){
            $credit=CreditSetting1::where('main_category_id',$input['main_category_id'])->pluck('hot_species_credit')->first();
        }
        elseif ($input['spices_category']=="Mid"){
            $credit=CreditSetting1::where('main_category_id',$input['main_category_id'])->pluck('mid_species_credit')->first();
        }
        elseif($input['spices_category']=="Low"){
            $credit=CreditSetting1::where('main_category_id',$input['main_category_id'])->pluck('low_species_credit')->first();
        }
        $input['credit_per_transaction']=$credit;
        $input['sub_categories']=$request->sub_cat_ids;
        $CreditSetting2 = CreditSetting2::create($input);
        $exploded_subcat=explode(",",$input['sub_categories']);
        foreach ($exploded_subcat as $subcat){
            $updatesubcat=SubCategory::where('id',$subcat)->update(['can_display_in_credit_setting2'=>0]);
        }
//        $main_category=MainCategory::where('id',$input['main_category_id'])->update(['can_display_in_credit_setting1'=>0]);
        Flash::success('Credit Setting 2 saved successfully.');

        return redirect(route('credit_setting2.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $CreditSetting2 = CreditSetting2::find($id);

        if (empty($CreditSetting2)) {
            Flash::error('Credit Setting 2 not found');

            return redirect(route('credit_category.index'));
        }

        return view('credit_category.show')->with('CreditSetting2', $CreditSetting2);
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
        $CreditSetting2=CreditSetting2::find($id);

        if (empty($CreditSetting2)) {
            Flash::error('Credit Setting 2 not found');

            return redirect(route('credit_setting2.index'));
        }
        $main_categories=MainCategory::orderby('id','desc')->get(['name','id']);

        return view('credit_category.index')->with('CreditSetting2', $CreditSetting2)->with('view', $this->view)->with('edit',0)->with(compact('prefered_ids','preferd_req','preferd_cnt','main_categories'));
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
        $CreditSetting2=CreditSetting2::find($id);
        if (empty($CreditSetting2)) {
            Flash::error('Credit Setting 2 not found');

            return redirect(route('credit_setting2.index'));
        }
        $input=$request->all();
        $input['main_category_id']=$request->main_category_id;
        $input['spices_category']=$request->spices_category;
        if($input['spices_category']=="Hot"){
            $credit=CreditSetting1::where('main_category_id',$input['main_category_id'])->pluck('hot_species_credit')->first();
        }
        elseif ($input['spices_category']=="Mid"){
            $credit=CreditSetting1::where('main_category_id',$input['main_category_id'])->pluck('mid_species_credit')->first();
        }
        elseif($input['spices_category']=="Low"){
            $credit=CreditSetting1::where('main_category_id',$input['main_category_id'])->pluck('low_species_credit')->first();
        }
        $input['credit_per_transaction']=$credit;
        $input['sub_categories']=$request->sub_cat_ids;
//        $main_cat=MainCategory::where('id',$CreditSetting2->main_category_id)->update(['can_display_in_credit_setting1'=>null]);

        $exploded_subs=explode(",",$CreditSetting2->sub_categories);
        foreach ($exploded_subs as $es){
            $supdate=SubCategory::where('id',$es)->update(['can_display_in_credit_setting2'=>null]);
        }
        $CreditSetting2 = $CreditSetting2->update($input);
        $exploded_reqsubs=explode(",",$input['sub_categories']);
        foreach ($exploded_reqsubs as $ers){
            $supdate=SubCategory::where('id',$ers)->update(['can_display_in_credit_setting2'=>0]);
        }
//        $main_cat=MainCategory::where('id',$request->main_category_id)->update(['can_display_in_credit_setting1'=>0]);

        Flash::success('Credit Setting 2 updated successfully.');

        return redirect(route('credit_setting2.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $CreditSetting2=CreditSetting2::find($id);

        if (empty($CreditSetting2)) {
            Flash::error('Credit Setting 2 not found');

            return redirect(route('credit_setting2.index'));
        }

        $CreditSetting2->delete($id);

        Flash::success('Credit Setting 2 deleted successfully.');

        return redirect(route('credit_setting2.index'));
    }

    public function getcreditsetting2(Request $request) {

        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
        $CreditSetting2 = CreditSetting2::query();

        if($request->start_date){
            $startDate = CommonHelper::LocalToUtcDate($request->start_date." 00:00:00");
            $CreditSetting2 = $CreditSetting2->where('created_at','>=',$startDate);
        }

        if($request->end_date){
            $endDate = CommonHelper::LocalToUtcDate($request->end_date." 23:59:59");
            $CreditSetting2 = $CreditSetting2->where('created_at',"<=",$endDate);
        }

        if($request->search){
            $CreditSetting2 = $CreditSetting2->whereHas('main_category',function ($mainQuery) use($request) {
                $mainQuery->where('name', 'Like', '%' . $request->search . '%');
            })
                ->orwhere('spices_category','Like','%'.$request->search .'%')
                ->orwhere('credit_per_transaction','Like','%'.$request->search .'%');
        }

        $CreditSetting2 = $CreditSetting2->paginate($request->per_page);

        return view('credit_setting2.sub_table',compact('CreditSetting2','prefered_ids','preferd_req','preferd_cnt'))->render();
    }

    public function subcategorylist($id)
    {
        try {
            $scs=SubCategory::where('main_category_id',$id)->where('can_display_in_credit_setting2',null)->where('status',1)->get();
//            $new=[NULL=>"Select Sub Category"];
            foreach ($scs as $sc)
            {
                $new[$sc->id]=$sc->name;
            }
            return ['data'=>$new,"status"=>1];
        }
        catch(\Exception $e)
        {
            return ["status"=>0,"error"=>$e->getMessage()];
        }
    }

}
