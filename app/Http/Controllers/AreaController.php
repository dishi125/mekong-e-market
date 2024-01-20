<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use App\Http\Requests\CreateAreaRequest;
use App\Http\Requests\UpdateAreaRequest;
use App\Models\Area;
use App\Repositories\AreaRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\UserProfile;

class AreaController extends AppBaseController
{
    /** @var  AreaRepository */
    private $areaRepository;
    public $view='areas';

    public function __construct(AreaRepository $areaRepo)
    {
        $this->areaRepository = $areaRepo;
    }

    /**
     * Display a listing of the Area.
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
        return view('states.index')
            ->with('view', $this->view)->with(compact('preferd_cnt','preferd_req','prefered_ids'));
    }

    /**
     * Show the form for creating a new Area.
     *
     * @return Response
     */
    public function create()
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
        return view('states.index')->with('view', $this->view)->with(compact('preferd_cnt','preferd_req','prefered_ids'));
    }

    /**
     * Store a newly created Area in storage.
     *
     * @param CreateAreaRequest $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        switch($request->import)
        {
            case 'Create':
                $request->validate([
                    'state_id' => 'required',
                    'name' => 'required',
                ]);
                $area = $this->areaRepository->create($input);

                Flash::success('Area saved successfully.');

                return redirect(route('areas.index'));

            //break;
            case 'import':

                $request->validate([
                    'upload_csv' => 'required'
                ]);
                if(isset($input['upload_csv']))
                {
                    $file = $request->File('upload_csv');

                    $flag = Excel::import(new Area,$request->File('upload_csv'));
                    if($flag)
                    {
                        Flash::success('Area Imported successfully.');

                        return redirect(route('areas.index'));
                    }
                    else
                    {
                        Flash::error('Area Imported failed.');

                        return redirect(route('areas.index'));
                    }
                    //return back();
                }
                else
                {
                    // $area = $this->areaRepository->create($input);

                    Flash::error('Please Select A excel file!');

                    return redirect(route('areas.index'));
                }

            //break;
        }
    }

    /**
     * Display the specified Area.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $area = $this->areaRepository->find($id);

        if (empty($area)) {
            Flash::error('Area not found');

            return redirect(route('areas.index'));
        }
         $areas = $this->areaRepository->paginate(10);
        return view('states.index')->with('area', $area) ->with('areas', $areas) ->with('view', $this->view);;
    }

    /**
     * Show the form for editing the specified Area.
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
        $area = $this->areaRepository->find($id);

        if (empty($area)) {
            Flash::error('Area not found');

            return redirect(route('areas.index'));
        }
        return view('states.index')->with('area', $area)->with('edit',0)->with('view', $this->view)->with(compact('preferd_cnt','preferd_req','prefered_ids'));
    }

    /**
     * Update the specified Area in storage.
     *
     * @param int $id
     * @param UpdateAreaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAreaRequest $request)
    {
        $area = $this->areaRepository->find($id);

        if (empty($area)) {
            Flash::error('Area not found');

            return redirect(route('areas.index'));
        }

        $area = $this->areaRepository->update($request->all(), $id);

        Flash::success('Area updated successfully.');

        return redirect(route('areas.index'));
    }

    /**
     * Remove the specified Area from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $area = $this->areaRepository->find($id);

        if (empty($area)) {
            Flash::error('Area not found');

            return redirect(route('areas.index'));
        }

        $this->areaRepository->delete($id);

        Flash::success('Area deleted successfully.');

        return redirect(route('areas.index'));
    }

    public function get_areas(Request $request)
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
        $areas = Area::query();

        if($request->start_date){
            $startDate = CommonHelper::LocalToUtcDate($request->start_date." 00:00:00");
            $areas = $areas->where('created_at','>=',$startDate);
        }

        if($request->end_date){
            $endDate = CommonHelper::LocalToUtcDate($request->end_date." 23:59:59");
            $areas = $areas->where('created_at',"<=",$endDate);
        }

        if($request->search){
            $areas = $areas->where(function ($mainQuery) use($request){
                            $mainQuery->where('name','Like','%'.$request->search.'%')
                                ->orWhereHas('state',function ($query) use($request) {
                                    $query->where('name','Like','%'.$request->search.'%');
                                });
                            });

        }
        $areas = $areas->paginate($request->per_page);

        return view('areas.sub_table',compact('areas'))->with(compact('preferd_cnt','preferd_req','prefered_ids'))->render();
    }
}
