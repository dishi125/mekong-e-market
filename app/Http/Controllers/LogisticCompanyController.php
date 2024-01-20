<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use App\Http\Requests\CreateLogisticCompanyRequest;
use App\Http\Requests\UpdateLogisticCompanyRequest;
use App\Models\Area;
use App\Models\LogisticCompany;
use App\Models\LogisticPhoto;
use App\Models\State;
use App\Repositories\LogisticCompanyRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use App\Models\UserProfile;

class   LogisticCompanyController extends AppBaseController
{
    /** @var  LogisticCompanyRepository */
    private $logisticCompanyRepository;
    public $view = "logistic_companies";

    public function __construct(LogisticCompanyRepository $logisticCompanyRepo)
    {
        $this->logisticCompanyRepository = $logisticCompanyRepo;
    }

    /**
     * Display a listing of the LogisticCompany.
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
        $logisticCompanies = $this->logisticCompanyRepository->paginate(10);
//        $states = State::get();
//        $areas = Area::get();
        $cnt=LogisticCompany::count();
        if($cnt==0){
            $lastid="100000";
        }
        else{
            $lastidno=LogisticCompany::orderby('id','DESC')->first();
            $lastid=($lastidno->id_no)+1;
        }
        return view('logistic_companies.index')->with('logisticCompanies', $logisticCompanies)->with('view', $this->view)->with('lastid',$lastid)->with(compact('preferd_cnt','preferd_req','prefered_ids'));
    }

    /**
     * Show the form for creating a new LogisticCompany.
     *
     * @return Response
     */
    public function create()
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
         $logisticCompanies = $this->logisticCompanyRepository->paginate(10);
//         $states = State::get();
//         $areas = Area::get();
        return view('logistic_companies.index')->with('logisticCompanies', $logisticCompanies)->with('view', $this->view)->with(compact('prefered_ids','preferd_req','preferd_cnt'));
    }

    /**
     * Store a newly created LogisticCompany in storage.
     *
     * @param CreateLogisticCompanyRequest $request
     *
     * @return Response
     */
    public function store(CreateLogisticCompanyRequest $request)
    {
        $input = $request->all();


        if ($request->hasFile('profile')) {
            $image = $request->file('profile');
            $image_name = 'Logistic_' . rand(111111, 999999) . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('Logistic');
            $imageName = 'Logistic/' . $image_name;
            $image->move($destinationPath, $image_name);
            $input['profile'] = $imageName;
        }

        $logisticCompany = $this->logisticCompanyRepository->create($input);

        if($request->hasFile('final_photos')){
            foreach ($input['final_photos'] as $photo){
                $image = $photo;
                $image_name = 'LogisticPhoto_' . rand(111111, 999999) . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('Logistic');
                $imageName = 'Logistic/' . $image_name;
                $image->move($destinationPath, $image_name);

                $logistic_photo = new LogisticPhoto();
                $logistic_photo->logistic_company_id = $logisticCompany->id;
                $logistic_photo->image = $imageName;
                $logistic_photo->save();
            }
        }

        Flash::success('Logistic Company saved successfully.');

        return Response::json('true');
    }

    /**
     * Display the specified LogisticCompany.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $logisticCompany = $this->logisticCompanyRepository->find($id);

        if (empty($logisticCompany)) {
            Flash::error('Logistic Company not found');

            return redirect(route('logisticCompanies.index'));
        }
         $logisticCompanies = $this->logisticCompanyRepository->paginate(10);
        return view('logistic_companies.show')->with('logisticCompany', $logisticCompany) ->with('logisticCompanies', $logisticCompanies)->with('view', $this->view);
    }

    /**
     * Show the form for editing the specified LogisticCompany.
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
        $logisticCompany = $this->logisticCompanyRepository->find($id);
        $logistic_photos = LogisticPhoto::where('logistic_company_id',$id)
                            ->get();

        if (empty($logisticCompany)) {
            Flash::error('Logistic Company not found');

            return redirect(route('logisticCompanies.index'));
        }
         $logisticCompanies = $this->logisticCompanyRepository->paginate(10);

//            $states = State::get();
//            $areas = Area::get();
        return view('logistic_companies.index')->with('logisticCompany', $logisticCompany)->with('logistic_photos', $logistic_photos)->with('logisticCompanies', $logisticCompanies)->with('edit', 0)->with('view', $this->view)->with(compact('preferd_cnt','preferd_req','prefered_ids'));
    }

    /**
     * Update the specified LogisticCompany in storage.
     *
     * @param int $id
     * @param UpdateLogisticCompanyRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateLogisticCompanyRequest $request)
    {
        $logisticCompany = $this->logisticCompanyRepository->find($id);

        if (empty($logisticCompany)) {
            Flash::error('Logistic Company not found');

            return redirect(route('logisticCompanies.index'));
        }

        $input = $request->all();

        $deleted_profile = isset($input['deleted_profile']) ? $input['deleted_profile'] : '';
        $deleted_photos = isset($input['deleted_photos']) ? json_decode($input['deleted_photos'],true) : array();

        if($deleted_profile == $logisticCompany->profile){
            unlink(public_path( $logisticCompany->profile));
            $input['profile'] = '';
        }

        $deleted_photos_data = LogisticPhoto::whereIn('id',$deleted_photos)->get();
        foreach ($deleted_photos_data as $image){
            unlink(public_path($image->image));
            $image->delete();
        }

        if ($request->hasFile('profile')) {
            $image = $request->file('profile');
            $image_name = 'LogisticPhoto_' . rand(111111, 999999) . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('Logistic');
            $imageName = 'Logistic/' . $image_name;
            $image->move($destinationPath, $image_name);
            $input['profile'] = $imageName;
        }

        if($request->hasFile('final_photos')){
            foreach ($input['final_photos'] as $photo){
                $image = $photo;
                $image_name = 'Logistic_' . rand(111111, 999999) . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('Logistic');
                $imageName = 'Logistic/' . $image_name;
                $image->move($destinationPath, $image_name);

                $logistic_photo = new LogisticPhoto();
                $logistic_photo->logistic_company_id = $logisticCompany->id;
                $logistic_photo->image = $imageName;
                $logistic_photo->save();
            }
        }


        $logisticCompany = $this->logisticCompanyRepository->update($input, $id);

        Flash::success('Logistic Company updated successfully.');

        return Response::json('true');
    }

    /**
     * Remove the specified LogisticCompany from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $logisticCompany = $this->logisticCompanyRepository->find($id);

        if (empty($logisticCompany)) {
            Flash::error('Logistic Company not found');

            return redirect(route('logisticCompanies.index'));
        }

        $this->logisticCompanyRepository->delete($id);

        Flash::success('Logistic Company deleted successfully.');

        return redirect(route('logisticCompanies.index'));
    }

    public function get_logistic_companies(Request $request)
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
        $logisticCompanies = LogisticCompany::Query();

        if($request->start_date){
            $startDate = CommonHelper::LocalToUtcDate($request->start_date." 00:00:00");
            $logisticCompanies = $logisticCompanies->where('created_at','>=',$startDate);
        }

        if($request->end_date){
            $endDate = CommonHelper::LocalToUtcDate($request->end_date." 23:59:59");
            $logisticCompanies = $logisticCompanies->where('created_at',"<=",$endDate);
        }

        if($request->search){
            $logisticCompanies = $logisticCompanies->where(function ($mainQuery) use($request){
                $mainQuery->where('name','Like','%'.$request->search.'%')
                          ->orwhere('contact','Like','%'.$request->search.'%')
                          ->orwhere('email','Like','%'.$request->search.'%')
                          ->orwhere('id_no','Like','%'.$request->search.'%');
            });
        }
        $logisticCompanies = $logisticCompanies->paginate($request->per_page);

        return view('logistic_companies.sub_table',compact('logisticCompanies','prefered_ids','preferd_req','preferd_cnt'))->render();
    }

    public function arealist($id){
        try {
            $scs=Area::where('state_id',$id)->get();
//            $new=[NULL=>"Select Sub Category"];
            foreach ($scs as $sc)
            {
                $new[$sc->id]=$sc->name;
            }
            return ['message'=>$new,"status"=>1];
        }
        catch(\Exception $e)
        {
            return ["status"=>0,"error"=>$e->getMessage()];
        }
    }
}
