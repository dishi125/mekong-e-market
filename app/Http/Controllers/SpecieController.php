<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use App\Http\Requests\CreateSpecieRequest;
use App\Http\Requests\UpdateSpecieRequest;
use App\Models\MainCategory;
use App\Models\Specie;
use App\Models\SubCategory;
use App\Models\UserProfile;
use App\Repositories\SpecieRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class SpecieController extends AppBaseController
{
    /** @var  SpecieRepository */
    private $specieRepository;
    public $view='species';

    public function __construct(SpecieRepository $specieRepo)
    {
        $this->specieRepository = $specieRepo;
    }

    /**
     * Display a listing of the Specie.
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

        $main_categories=MainCategory::orderby('id','desc')->get(['name','id']);
        $sub_categories=SubCategory::orderby('id','desc')->get(['name','id']);

        return view('main_categories.index')
            ->with('view', $this->view)->with(compact('preferd_cnt','prefered_ids','preferd_req','main_categories','sub_categories'));
    }

    /**
     * Show the form for creating a new Specie.
     *
     * @return Response
     */
    public function create()
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');

        $main_categories=MainCategory::orderby('id','desc')->get(['name','id']);
        $sub_categories=SubCategory::orderby('id','desc')->get(['name','id']);
        $species = $this->specieRepository->paginate(10);
        return view('main_categories.index') ->with('species', $species)->with('view', $this->view)->with(compact('preferd_cnt','preferd_req','prefered_ids','main_categories','sub_categories'));
    }

    /**
     * Store a newly created Specie in storage.
     *
     * @param CreateSpecieRequest $request
     *
     * @return Response
     */
    public function store(CreateSpecieRequest $request)
    {
        $input = $request->all();

        $specie = $this->specieRepository->create($input);

        Flash::success('Specie saved successfully.');

        return redirect(route('species.index'));
    }

    /**
     * Display the specified Specie.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $specie = $this->specieRepository->find($id);

        if (empty($specie)) {
            Flash::error('Specie not found');

            return redirect(route('species.index'));
        }
         $species = $this->specieRepository->paginate(10);;
        return view('main_categories.index')->with('species', $specie) ->with('species', $species)  ->with('view', $this->view);
    }

    /**
    /**
     * Show the form for editing the specified Specie.
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

        $specie = $this->specieRepository->find($id);

        if (empty($specie)) {
            Flash::error('Specie not found');

            return redirect(route('species.index'));
        }
        $main_categories=MainCategory::orderby('id','desc')->get(['name','id']);
        $sub_categories=SubCategory::orderby('id','desc')->get(['name','id']);
         $species = $this->specieRepository->paginate(10);;
        return view('main_categories.index')->with('specie', $specie) ->with('species', $species)->with('view', $this->view)->with('edit',0) ->with(compact('preferd_cnt','prefered_ids','preferd_req','main_categories','sub_categories')) ;
    }

    /**
     * Update the specified Specie in storage.
     *
     * @param int $id
     * @param UpdateSpecieRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSpecieRequest $request)
    {
        $specie = $this->specieRepository->find($id);

        if (empty($specie)) {
            Flash::error('Specie not found');

            return redirect(route('species.index'));
        }

        $specie = $this->specieRepository->update($request->all(), $id);

        Flash::success('Specie updated successfully.');

        return redirect(route('species.index'));
    }

    /**
     * Remove the specified Specie from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $specie = $this->specieRepository->find($id);

        if (empty($specie)) {
            Flash::error('Specie not found');

            return redirect(route('species.index'));
        }

        $this->specieRepository->delete($id);

        Flash::success('Specie deleted successfully.');

        return redirect(route('species.index'));
    }
    public function subcategorylist($id)
    {
        try {
            $scs=SubCategory::where('main_category_id',$id)->get();
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

    public function get_species(Request $request)
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');

        $species = Specie::query();

        if($request->start_date){
            $startDate = CommonHelper::LocalToUtcDate($request->start_date." 00:00:00");
            $species = $species->where('created_at','>=',$startDate);
        }

        if($request->end_date){
            $endDate = CommonHelper::LocalToUtcDate($request->end_date." 23:59:59");
            $species = $species->where('created_at',"<=",$endDate);
        }
        if($request->main_cat){
            if($request->main_cat==null){
                $species = $species;
            }
            $species = $species->where('main_category_id',$request->main_cat);
        }
        if($request->sub_cat){
            $species = $species->where('sub_category_id',$request->sub_cat);
        }
        if($request->search){
            $species = $species->where(function ($mainQuery) use($request){
                                $mainQuery->where('name','Like','%'.$request->search.'%')
                                ->orwhereHas('subcategory', function ($query)  use ($request){
                                    $query->where('name','Like','%'.$request->search.'%');
                                })
                                ->orwhereHas('subcategory.maincategory', function ($query)  use ($request){
                                    $query->where('name','Like','%'.$request->search.'%');
                                });
                        });
        }
        $species = $species->paginate($request->per_page);

        return view('species.sub_table',compact('species','preferd_cnt','preferd_req','prefered_ids'))->render();
    }
}
