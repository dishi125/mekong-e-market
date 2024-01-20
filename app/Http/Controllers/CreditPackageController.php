<?php

namespace App\Http\Controllers;

use App\Enums\Type;
use App\Helpers\CommonHelper;
use App\Http\Requests\CreateCreditPackageRequest;
use App\Http\Requests\UpdateCreditPackageRequest;
use App\Models\CreditPackage;
use App\Models\MyPackage;
use App\Repositories\CreditPackageRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use App\Models\UserProfile;

class CreditPackageController extends AppBaseController
{
    /** @var  CreditPackageRepository */
    private $creditPackageRepository;
    public $view = "credit_packages";
    public function __construct(CreditPackageRepository $creditPackageRepo)
    {
        $this->creditPackageRepository = $creditPackageRepo;
    }

    /**
     * Display a listing of the CreditPackage.
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
        return view('credit_packages.index')
            ->with('view',$this->view)->with(compact('prefered_ids','preferd_req','preferd_cnt'));
    }

    /**
     * Show the form for creating a new CreditPackage.
     *
     * @return Response
     */
    public function create()
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
         $creditPackages = $this->creditPackageRepository->paginate(10);
        return view('credit_packages.index') ->with('creditPackages', $creditPackages)->with('view',$this->view)->with(compact('preferd_cnt','preferd_req','prefered_ids'));;
    }

    /**
     * Store a newly created CreditPackage in storage.
     *
     * @param CreateCreditPackageRequest $request
     *
     * @return Response
     */
    public function store(CreateCreditPackageRequest $request)
    {
        $input = $request->all();

        $creditPackage = $this->creditPackageRepository->create($input);

        Flash::success('Credit Package saved successfully.');

        return redirect(route('creditPackages.index'));
    }

    /**
     * Display the specified CreditPackage.
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
        $creditPackage = $this->creditPackageRepository->find($id);

        if (empty($creditPackage)) {
            Flash::error('Credit Package not found');

            return redirect(route('creditPackages.index'));
        }
         $creditPackages = $this->creditPackageRepository->paginate(10);
        return view('credit_packages.index')->with('creditPackage', $creditPackage) ->with('creditPackages', $creditPackages)->with('view',$this->view)->with(compact('prefered_ids','preferd_req','preferd_cnt'));
    }

    /**
     * Show the form for editing the specified CreditPackage.
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
        $creditPackage = $this->creditPackageRepository->find($id);

        if (empty($creditPackage)) {
            Flash::error('Credit Package not found');

            return redirect(route('creditPackages.index'));
        }
         $creditPackages = $this->creditPackageRepository->paginate(10);
        return view('credit_packages.index')->with('creditPackage', $creditPackage) ->with('edit',0)->with('creditPackages', $creditPackages)->with('view',$this->view)->with(compact('preferd_cnt','preferd_req','prefered_ids'));
    }

    /**
     * Update the specified CreditPackage in storage.
     *
     * @param int $id
     * @param UpdateCreditPackageRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCreditPackageRequest $request)
    {
        $creditPackage = $this->creditPackageRepository->find($id);

        if (empty($creditPackage)) {
            Flash::error('Credit Package not found');

            return redirect(route('creditPackages.index'));
        }

        $creditPackage = $this->creditPackageRepository->update($request->all(), $id);

        Flash::success('Credit Package updated successfully.');

        return redirect(route('creditPackages.index'));
    }

    /**
     * Remove the specified CreditPackage from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $creditPackage = $this->creditPackageRepository->find($id);

        if (empty($creditPackage)) {
            Flash::error('Credit Package not found');

            return redirect(route('creditPackages.index'));
        }

        $this->creditPackageRepository->delete($id);

        Flash::success('Credit Package deleted successfully.');

        return redirect(route('creditPackages.index'));
    }
    public function creditPackages_status_change($id)
    {
        $CreditPackage = CreditPackage::find($id);


        if (empty($CreditPackage)) {
            return ["status"=>0,"data"=>"User Not Found"];
        }
        $CreditPackage->status=$CreditPackage->status==1? 0:1;
        $CreditPackage->save();
        return ["status"=>1];
//        return $CreditPackage;


    }

    public function creditBalance()
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
        $view = "creditBalances";
        $type = array_flip(Type::toArray());
        return view('credit_packages.show')->with('type',$type)->with('view',$view)->with(compact('prefered_ids','preferd_req','preferd_cnt'));
    }

    public function get_credit_balance(Request $request)
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
        $creditBalances = MyPackage::orderBy('id','desc');

        if($request->start_date){
            $startDate = CommonHelper::LocalToUtcDate($request->start_date." 00:00:00");
            $creditBalances = $creditBalances->where('created_at','>=',$startDate);
        }

        if($request->end_date){
            $endDate = CommonHelper::LocalToUtcDate($request->end_date." 23:59:59");
            $creditBalances = $creditBalances->where('created_at',"<=",$endDate);
        }

        if(isset($request->user_type) && $request->user_type != ''){
            $creditBalances = $creditBalances->whereHas('user', function ($query) use ($request){
                $query->where('user_type',$request->user_type);
            });
        }

        if($request->search){
            $creditBalances = $creditBalances->where(function ($mainQuery) use($request){
                $mainQuery->where('transaction_id','Like','%'.$request->search.'%')
                            ->orwhereHas('user',function ($query) use($request){
                                $query->where('name','Like','%'.$request->search.'%');
                            });
            });
        }
        $creditBalances = $creditBalances->paginate($request->per_page);

        $type = array_flip(Type::toArray());

        return view('credit_packages.sub_listtable',compact('creditBalances','type','preferd_cnt','preferd_req','prefered_ids'))->render();
    }

    public function get_credit_packages(Request $request)
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
        $creditPackages = CreditPackage::Query();

        if($request->start_date){
            $startDate = CommonHelper::LocalToUtcDate($request->start_date." 00:00:00");
            $creditPackages = $creditPackages->where('created_at','>=',$startDate);
        }

        if($request->end_date){
            $endDate = CommonHelper::LocalToUtcDate($request->end_date." 23:59:59");
            $creditPackages = $creditPackages->where('created_at',"<=",$endDate);
        }

        if($request->search){
            $creditPackages = $creditPackages->where(function ($mainQuery) use($request){
                    $mainQuery->where('amount','Like','%'.$request->search.'%')
                              ->orwhere('credit','Like','%'.$request->search.'%');
            });
        }
        $creditPackages = $creditPackages->paginate($request->per_page);

        return view('credit_packages.sub_table',compact('creditPackages','prefered_ids','preferd_req','preferd_cnt'))->render();
    }
}
