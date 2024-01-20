<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use App\Http\Requests\CreateBannerPackageRequest;
use App\Http\Requests\UpdateBannerPackageRequest;
use App\Models\BannerPackage;
use App\Repositories\BannerPackageRepository;
use App\Http\Controllers\AppBaseController;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Flash;
use Response;
use App\Models\UserProfile;

class BannerPackageController extends AppBaseController
{
    /** @var  BannerPackageRepository */
    private $bannerPackageRepository;
    public $view='banner_packages';

    public function __construct(BannerPackageRepository $bannerPackageRepo)
    {
        $this->bannerPackageRepository = $bannerPackageRepo;
    }

    /**
     * Display a listing of the BannerPackage.
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
        return view('banner_packages.index')
            ->with('view', $this->view)->with(compact('preferd_cnt','preferd_req','prefered_ids'));
    }

    /**
     * Show the form for creating a new BannerPackage.
     *
     * @return Response
     */
    public function create()
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
         $bannerPackages = $this->bannerPackageRepository->paginate(10);
        return view('banner_packages.create') ->with('bannerPackages', $bannerPackages)->with('view', $this->view)->with(compact('prefered_ids','preferd_req','preferd_cnt'));
    }

    /**
     * Store a newly created BannerPackage in storage.
     *
     * @param CreateBannerPackageRequest $request
     *
     * @return Response
     */
    public function store(CreateBannerPackageRequest $request)
    {
        $input = $request->all();

        $duration = CommonHelper::convertDurationToSecond(Carbon::now(),$input['duration'],$input['duration_type']);

        $input['duration'] = $duration;

        $bannerPackage = $this->bannerPackageRepository->create($input);

        Flash::success('Banner Package saved successfully.');

        return redirect(route('bannerPackages.index'));
    }

    /**
     * Display the specified BannerPackage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $bannerPackage = $this->bannerPackageRepository->find($id);

        if (empty($bannerPackage)) {
            Flash::error('Banner Package not found');

            return redirect(route('bannerPackages.index'));
        }
         $bannerPackages = $this->bannerPackageRepository->paginate(10);
        return view('banner_packages.index')->with('bannerPackage', $bannerPackage) ->with('bannerPackages', $bannerPackages)->with('view', $this->view);
    }

    /**
     * Show the form for editing the specified BannerPackage.
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
        $bannerPackage = $this->bannerPackageRepository->find($id);

        if (empty($bannerPackage)) {
            Flash::error('Banner Package not found');

            return redirect(route('bannerPackages.index'));
        }
         $bannerPackages = $this->bannerPackageRepository->paginate(10);
        return view('banner_packages.index')->with('bannerPackage', $bannerPackage)->with('edit',0)->with('bannerPackages', $bannerPackages)->with('view', $this->view)->with(compact('preferd_cnt','preferd_req','prefered_ids'));
    }

    /**
     * Update the specified BannerPackage in storage.
     *
     * @param int $id
     * @param UpdateBannerPackageRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBannerPackageRequest $request)
    {
        $bannerPackage = $this->bannerPackageRepository->find($id);

        if (empty($bannerPackage)) {
            Flash::error('Banner Package not found');

            return redirect(route('bannerPackages.index'));
        }

        $input = $request->all();
        $duration = CommonHelper::convertDurationToSecond(Carbon::now(),$input['duration'],$input['duration_type']);
        $input['duration'] = $duration;
        $bannerPackage = $this->bannerPackageRepository->update($input, $id);

        Flash::success('Banner Package updated successfully.');

        return redirect(route('bannerPackages.index'));
    }

    /**
     * Remove the specified BannerPackage from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $bannerPackage = $this->bannerPackageRepository->find($id);

        if (empty($bannerPackage)) {
            Flash::error('Banner Package not found');

            return redirect(route('bannerPackages.index'));
        }

        $this->bannerPackageRepository->delete($id);

        Flash::success('Banner Package deleted successfully.');

        return redirect(route('bannerPackages.index'));
    }

    public function get_banner_packages(Request $request)
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
        $bannerPackages = BannerPackage::query();

        if($request->start_date){
            $startDate = CommonHelper::LocalToUtcDate($request->start_date." 00:00:00");
            $bannerPackages = $bannerPackages->where('created_at','>=',$startDate);
        }

        if($request->end_date){
            $endDate = CommonHelper::LocalToUtcDate($request->end_date." 23:59:59");
            $bannerPackages = $bannerPackages->where('created_at',"<=",$endDate);
        }

        if($request->search){
            $bannerPackages = $bannerPackages->where(function ($mainQuery) use($request){
                $mainQuery->where('price','Like','%'.$request->search.'%')
                          ->orwhere('location','Like','%'.$request->search.'%');
            });
        }
        $bannerPackages = $bannerPackages->paginate($request->per_page);

        return view('banner_packages.sub_table',compact('bannerPackages','prefered_ids','preferd_req','preferd_cnt'))->render();
    }
}
