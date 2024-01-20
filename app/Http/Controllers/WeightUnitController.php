<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateWeightUnitRequest;
use App\Http\Requests\UpdateWeightUnitRequest;
use App\Models\WeightUnit;
use App\Repositories\WeightUnitRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use App\Models\UserProfile;

class WeightUnitController extends AppBaseController
{
    /** @var  WeightUnitRepository */
    private $weightUnitRepository;
    public $view='weight_units';

    public function __construct(WeightUnitRepository $weightUnitRepo)
    {
        $this->weightUnitRepository = $weightUnitRepo;
    }

    /**
     * Display a listing of the WeightUnit.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
        return view('grades.index')
            ->with('view', $this->view)->with(compact('prefered_ids','preferd_req','preferd_cnt'));
    }

    /**
     * Show the form for creating a new WeightUnit.
     *
     * @return Response
     */
    public function create()
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
        return view('grades.index')->with('view', $this->view)->with(compact('prefered_ids','preferd_req','preferd_cnt'));
    }

    /**
     * Store a newly created WeightUnit in storage.
     *
     * @param CreateWeightUnitRequest $request
     *
     * @return Response
     */
    public function store(CreateWeightUnitRequest $request)
    {
        $input = $request->all();

        $weightUnit = $this->weightUnitRepository->create($input);

        Flash::success('Weight Unit saved successfully.');

        return redirect(route('weightUnits.index'));
    }

    /**
     * Display the specified WeightUnit.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $weightUnit = $this->weightUnitRepository->find($id);

        if (empty($weightUnit)) {
            Flash::error('Weight Unit not found');

            return redirect(route('weightUnits.index'));
        }

        return view('grades.index')->with('weightUnit', $weightUnit);
    }

    /**
     * Show the form for editing the specified WeightUnit.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
        $weightUnit = $this->weightUnitRepository->find($id);

        if (empty($weightUnit)) {
            Flash::error('Weight Unit not found');

            return redirect(route('weightUnits.index'));
        }

        return view('grades.index')->with('weightUnit', $weightUnit)->with('edit',0)->with('view', $this->view)->with(compact('prefered_ids','preferd_req','preferd_cnt'));
    }

    /**
     * Update the specified WeightUnit in storage.
     *
     * @param int $id
     * @param UpdateWeightUnitRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateWeightUnitRequest $request)
    {
        $weightUnit = $this->weightUnitRepository->find($id);

        if (empty($weightUnit)) {
            Flash::error('Weight Unit not found');

            return redirect(route('weightUnits.index'));
        }

        $weightUnit = $this->weightUnitRepository->update($request->all(), $id);

        Flash::success('Weight Unit updated successfully.');

        return redirect(route('weightUnits.index'));
    }

    /**
     * Remove the specified WeightUnit from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $weightUnit = $this->weightUnitRepository->find($id);

        if (empty($weightUnit)) {
            Flash::error('Weight Unit not found');

            return redirect(route('weightUnits.index'));
        }

        $this->weightUnitRepository->delete($id);

        Flash::success('Weight Unit deleted successfully.');

        return redirect(route('weightUnits.index'));
    }

    public function getWeightUnits(Request $request) {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
        $weightUnits = WeightUnit::withTrashed();

        if($request->start_date){
            $startDate = CommonHelper::LocalToUtcDate($request->start_date." 00:00:00");
            $weightUnits = $weightUnits->where('created_at','>=',$startDate);
        }

        if($request->end_date){
            $endDate = CommonHelper::LocalToUtcDate($request->end_date." 23:59:59");
            $weightUnits = $weightUnits->where('created_at',"<=",$endDate);
        }

        if($request->search){
            $weightUnits = $weightUnits->where(function ($mainQuery) use($request){
                $mainQuery->where('unit','Like','%'.$request->search.'%');
            });
        }

        $weightUnits = $weightUnits->paginate($request->per_page);

        return view('weight_units.sub_table',compact('weightUnits'))->with(compact('prefered_ids','preferd_req','preferd_cnt'))->render();
    }
}
