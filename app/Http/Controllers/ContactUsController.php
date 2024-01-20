<?php

namespace App\Http\Controllers;

use App\Enums\Type;
use App\Helpers\CommonHelper;
use App\Http\Requests\CreateContactUsRequest;
use App\Http\Requests\UpdateContactUsRequest;
use App\Models\ContactUs;
use App\Repositories\ContactUsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use App\Models\UserProfile;

class ContactUsController extends AppBaseController
{
    /** @var  ContactUsRepository */
    private $contactUsRepository;
    public $view = "contactuses";
    public function __construct(ContactUsRepository $contactUsRepo)
    {
        $this->contactUsRepository = $contactUsRepo;
    }

    /**
     * Display a listing of the ContactUs.
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
        $type = array_flip(Type::toArray());
        return view('contactuses.index',compact('type'))->with('view', $this->view)->with(compact('prefered_ids','preferd_req','preferd_cnt'));
    }

    /**
     * Show the form for creating a new ContactUs.
     *
     * @return Response
     */
    public function create()
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
        return view('contactuses.create')->with(compact('prefered_ids','preferd_req','preferd_cnt'));
    }

    /**
     * Store a newly created ContactUs in storage.
     *
     * @param CreateContactUsRequest $request
     *
     * @return Response
     */
    public function store(CreateContactUsRequest $request)
    {
        $input = $request->all();

        $contactUs = $this->contactUsRepository->create($input);

        Flash::success('Contact Us saved successfully.');

        return redirect(route('contactuses.index'));
    }

    /**
     * Display the specified ContactUs.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $contactUs = $this->contactUsRepository->find($id);

        if (empty($contactUs)) {
            Flash::error('Contact Us not found');

            return redirect(route('contactuses.index'));
        }

        return view('contactuses.show')->with('contactUs', $contactUs);
    }

    /**
     * Show the form for editing the specified ContactUs.
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
        $contactUs = $this->contactUsRepository->find($id);

        if (empty($contactUs)) {
            Flash::error('Contact Us not found');

            return redirect(route('contactuses.index'));
        }

        return view('contactuses.edit')->with('contactUs', $contactUs)->with(compact('prefered_ids','preferd_req','preferd_cnt'));
    }

    /**
     * Update the specified ContactUs in storage.
     *
     * @param int $id
     * @param UpdateContactUsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateContactUsRequest $request)
    {
        $contactUs = $this->contactUsRepository->find($id);

        if (empty($contactUs)) {
            Flash::error('Contact Us not found');

            return redirect(route('contactuses.index'));
        }

        $contactUs = $this->contactUsRepository->update($request->all(), $id);

        Flash::success('Contact Us updated successfully.');

        return redirect(route('contactuses.index'));
    }

    /**
     * Remove the specified ContactUs from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $contactUs = $this->contactUsRepository->find($id);

        if (empty($contactUs)) {
            Flash::error('Contact Us not found');

            return redirect(route('contactuses.index'));
        }

        $this->contactUsRepository->delete($id);

        Flash::success('Contact Us deleted successfully.');

        return redirect(route('contactuses.index'));
    }

    public function getContactuses(Request $request) {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
        $contactuses = ContactUs::with('user');

        if($request->start_date){
            $startDate = CommonHelper::LocalToUtcDate($request->start_date." 00:00:00");
            $contactuses = $contactuses->where('created_at','>=',$startDate);
        }

        if($request->end_date){
            $endDate = CommonHelper::LocalToUtcDate($request->end_date." 23:59:59");
            $contactuses = $contactuses->where('created_at',"<=",$endDate);
        }

        if (isset($request->user_type) && $request->user_type != '') {
            $contactuses = $contactuses->whereHas('user', function ($query) use($request) {
                $query->where('user_type', $request->user_type);
            });
        }

        if($request->search){
            $contactuses = $contactuses->where(function ($mainQuery) use($request){
                $mainQuery->where('email','Like','%'.$request->search.'%')
                            ->orWhereHas('user',function ($query) use($request){
                                $query->where('name','Like','%'.$request->search.'%');
                            })
                            ->orWhere('message','Like','%'.$request->search.'%');
            });
        }

        $contactuses = $contactuses->paginate($request->per_page);

        return view('contactuses.sub_table',compact('contactuses'))->with(compact('prefered_ids','preferd_req','preferd_cnt'))->render();
    }
}
