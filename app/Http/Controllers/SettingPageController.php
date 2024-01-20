<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSettingPageRequest;
use App\Http\Requests\UpdateSettingPageRequest;
use App\Models\SettingPage;
use App\Repositories\SettingPageRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use App\Models\UserProfile;

class SettingPageController extends AppBaseController
{
    /** @var  SettingPageRepository */
    private $settingPageRepository;
    public $view='setting_pages';

    public function __construct(SettingPageRepository $settingPageRepo)
    {
        $this->settingPageRepository = $settingPageRepo;
    }

    /**
     * Display a listing of the SettingPage.
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
        return view('setting_pages.index')
            ->with('view', $this->view)->with(compact('prefered_ids','preferd_req','preferd_cnt'));
    }

    /**
     * Show the form for creating a new SettingPage.
     *
     * @return Response
     */
    public function create()
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
        return view('banner_packages.index')->with('view', $this->view)->with(compact('prefered_ids','preferd_req','preferd_cnt'));
    }

    /**
     * Store a newly created SettingPage in storage.
     *
     * @param CreateSettingPageRequest $request
     *
     * @return Response
     */
    public function store(CreateSettingPageRequest $request)
    {
        $input = $request->all();
        $settingPage = $this->settingPageRepository->create($input);

        Flash::success('Setting Page saved successfully.');

        return redirect(route('settingPages.index'));
    }

    /**
     * Display the specified SettingPage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
        $settingPage = $this->settingPageRepository->find($id);

        if (empty($settingPage)) {
            Flash::error('Setting Page not found');

            return redirect(route('settingPages.index'));
        }

        return view('setting_pages.show')->with('settingPage', $settingPage)->with('view', $this->view)->with(compact('prefered_ids','preferd_req','preferd_cnt'));
    }

    /**
     * Show the form for editing the specified SettingPage.
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
        $settingPage = $this->settingPageRepository->find($id);

        if (empty($settingPage)) {
            Flash::error('Setting Page not found');

            return redirect(route('settingPages.index'));
        }

        return view('setting_pages.index')->with('settingPage', $settingPage)->with('edit',0)->with('view', $this->view)->with(compact('prefered_ids','preferd_req','preferd_cnt'));
    }

    /**
     * Update the specified SettingPage in storage.
     *
     * @param int $id
     * @param UpdateSettingPageRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSettingPageRequest $request)
    {
        $settingPage = $this->settingPageRepository->find($id);

        if (empty($settingPage)) {
            Flash::error('Setting Page not found');

            return redirect(route('settingPages.index'));
        }

        $settingPage = $this->settingPageRepository->update($request->all(), $id);

        Flash::success('Setting Page updated successfully.');

        return redirect(route('settingPages.index'));
    }

    /**
     * Remove the specified SettingPage from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $settingPage = $this->settingPageRepository->find($id);

        if (empty($settingPage)) {
            Flash::error('Setting Page not found');

            return redirect(route('settingPages.index'));
        }

        $this->settingPageRepository->delete($id);

        Flash::success('Setting Page deleted successfully.');

        return redirect(route('settingPages.index'));
    }

    public function settingPages(Request $request) {

        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
        $settingPages = SettingPage::withTrashed();

        if($request->start_date){
            $startDate = CommonHelper::LocalToUtcDate($request->start_date." 00:00:00");
            $settingPages = $settingPages->where('created_at','>=',$startDate);
        }

        if($request->end_date){
            $endDate = CommonHelper::LocalToUtcDate($request->end_date." 23:59:59");
            $settingPages = $settingPages->where('created_at',"<=",$endDate);
        }

        if($request->search){
            $settingPages = $settingPages->where(function ($mainQuery) use($request){
                $mainQuery->where('name','Like','%'.$request->search.'%');
            });
        }

        $settingPages = $settingPages->paginate($request->per_page);

        return view('setting_pages.sub_table',compact('settingPages'))->with(compact('prefered_ids','preferd_req','preferd_cnt'))->render();
    }
}
