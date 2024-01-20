<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use App\Http\Requests\CreateCreditManagementRequest;
use App\Http\Requests\UpdateCreditManagementRequest;
use App\Models\CreditManagement;
use App\Models\Frame;
use App\Models\Post;
use App\Repositories\CreditManagementRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use App\Models\UserProfile;

class CreditManagementController extends AppBaseController
{
    /** @var  CreditManagementRepository */
    private $creditManagementRepository;
    public $view = "credit_managements";

    public function __construct(CreditManagementRepository $creditManagementRepo)
    {
        $this->creditManagementRepository = $creditManagementRepo;
    }

    /**
     * Display a listing of the CreditManagement.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $f = Frame::find(1);

        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
        return view('credit_managements.index')->with('view',$this->view)->with(compact('prefered_ids','preferd_req','preferd_cnt','f'));
    }

    /**
     * Show the form for creating a new CreditManagement.
     *
     * @return Response
     */
    public function create()
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
        return view('credit_managements.create')->with('view',$this->view)->with(compact('preferd_cnt','preferd_req','prefered_ids'));
    }

    /**
     * Store a newly created CreditManagement in storage.
     *
     * @param CreateCreditManagementRequest $request
     *
     * @return Response
     */
    public function store(CreateCreditManagementRequest $request)
    {
        $input = $request->all();

        $creditManagement = $this->creditManagementRepository->create($input);

        Flash::success('Credit Management saved successfully.');

        return redirect(route('creditManagements.index'));
    }

    /**
     * Display the specified CreditManagement.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $creditManagement = $this->creditManagementRepository->find($id);

        if (empty($creditManagement)) {
            Flash::error('Credit Management not found');

            return redirect(route('creditManagements.index'));
        }
         $creditManagements = $this->creditManagementRepository->all();
        return view('credit_managements.show')->with('creditManagement', $creditManagement) ->with('creditManagements', $creditManagements);;
    }

    /**
     * Show the form for editing the specified CreditManagement.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $f = Frame::find(1);

        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
        $creditManagement = $this->creditManagementRepository->find($id);

        if (empty($creditManagement)) {
            Flash::error('Credit Management not found');

            return redirect(route('creditManagements.index'));
        }
        return view('credit_managements.edit')->with('creditManagement', $creditManagement)->with('view',$this->view)->with(compact('prefered_ids','preferd_req','preferd_cnt','f'));
    }

    /**
     * Update the specified CreditManagement in storage.
     *
     * @param int $id
     * @param UpdateCreditManagementRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCreditManagementRequest $request)
    {
        $creditManagement = $this->creditManagementRepository->find($id);

        if (empty($creditManagement)) {
            Flash::error('Credit Management not found');

            return redirect(route('creditManagements.index'));
        }

        $creditManagement = $this->creditManagementRepository->update($request->all(), $id);

        Flash::success('Credit Management updated successfully.');

        return redirect(route('creditManagements.index'));
    }

    /**
     * Remove the specified CreditManagement from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $creditManagement = $this->creditManagementRepository->find($id);

        if (empty($creditManagement)) {
            Flash::error('Credit Management not found');

            return redirect(route('creditManagements.index'));
        }

        $this->creditManagementRepository->delete($id);

        Flash::success('Credit Management deleted successfully.');

        return redirect(route('creditManagements.index'));
    }

    public function get_credit_managements(Request $request)
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
        $creditManagements = Post::Query()->with('creditmanagement')->orderby('id','desc');

        if($request->buyer_filer){
            if($request->buyer_filer=="with"){
                $postids=Post::orderby('id','desc')->pluck('id');
                $creditManagements = $creditManagements->whereHas('creditmanagement',function ($query) use ($postids){
                                        $query->whereIn('post_id',$postids);
                                    });
            }
            if ($request->buyer_filer=="without"){
                $postids=CreditManagement::orderBy('id','desc')->pluck('Post_id');
                $creditManagements = $creditManagements->whereNotIn('id',$postids);
            }
        }

        if($request->payment_filer){
            if ($request->payment_filer=="1"){
                $creditManagements = $creditManagements->whereHas('creditmanagement',function ($query){
                    $query->where('transaction_status',1);
                });
            }
            if ($request->payment_filer=="-1"){
                $creditManagements = $creditManagements->whereHas('creditmanagement',function ($query){
                    $query->where('transaction_status',0);
                });
            }
        }

        if($request->paymenttype_filer){
            if ($request->paymenttype_filer=='2'){
                $creditManagements = $creditManagements->whereHas('creditmanagement',function ($query){
                    $query->where('payment_type',2);
                });
            }
            if ($request->paymenttype_filer=='1'){
                $creditManagements = $creditManagements->whereHas('creditmanagement',function ($query){
                    $query->where('payment_type',1);
                });
            }
        }

        if($request->start_date){
            $startDate = CommonHelper::LocalToUtcDate($request->start_date." 00:00:00");
            $creditManagements = $creditManagements->where('created_at','>=',$startDate);
        }

        if($request->end_date){
            $endDate = CommonHelper::LocalToUtcDate($request->end_date." 23:59:59");
            $creditManagements = $creditManagements->where('created_at',"<=",$endDate);
        }

        if($request->search){
            $creditManagements = $creditManagements->where(function ($mainQuery) use($request){
                $mainQuery->whereHas('creditmanagement.buyer',function ($query) use($request){
                                $query->where('name','Like','%'.$request->search.'%');
                            })
                            ->orwhereHas('product.user',function ($query) use($request){
                                $query->where('name','Like','%'.$request->search.'%');
                            })
                            ->orwhereHas('product',function ($query) use($request){
                                $query->where('product_name','Like','%'.$request->search.'%');
                            });
            });
        }
        $total_amount = CreditManagement::sum('total_amount');

        $creditManagements = $creditManagements->paginate($request->per_page);

        $grand_total_amount = 0;
        if($creditManagements->lastPage() == $creditManagements->currentPage()){
            $grand_total_amount = $total_amount;
        }
        return view('credit_managements.sub_table',compact('creditManagements','grand_total_amount','preferd_cnt','preferd_req','prefered_ids'))->render();
    }

    public function set_credit(Request $request)
    {
        $request->validate(['credit_per_transaction'=>"required"]);
        $f=Frame::find(1);

        $f->credit_per_transaction=$request->credit_per_transaction;
        $f->save();

        return redirect()->back();
    }
}
