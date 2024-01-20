<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use App\Http\Requests\CreateSubCategoryRequest;
use App\Http\Requests\UpdateSubCategoryRequest;
use App\Models\MainCategory;
use App\Models\SubCategory;
use App\Models\UserProfile;
use App\Repositories\SubCategoryRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\DB;
use Response;

class SubCategoryController extends AppBaseController
{
    /** @var  SubCategoryRepository */
    private $subCategoryRepository;
    public $view='sub_categories';

    public function __construct(SubCategoryRepository $subCategoryRepo)
    {
        $this->subCategoryRepository = $subCategoryRepo;
    }

    /**
     * Display a listing of the SubCategory.
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
        return view('main_categories.index')
            ->with('view', $this->view)->with(compact('preferd_cnt','prefered_ids','preferd_req','main_categories'));
    }

    /**
     * Show the form for creating a new SubCategory.
     *
     * @return Response
     */
    public function create()
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');

        $main_categories=MainCategory::orderby('id','desc')->get(['name','id']);
        $subCategories = $this->subCategoryRepository->paginate(10);
        return view('main_categories.create') ->with('subCategories', $subCategories)->with('view', $this->view)->with(compact('preferd_cnt','preferd_req','prefered_ids','main_categories'));
    }

    /**
     * Store a newly created SubCategory in storage.
     *
     * @param CreateSubCategoryRequest $request
     *
     * @return Response
     */
    public function store(CreateSubCategoryRequest $request)
    {
        $input = $request->all();

        $subCategory = $this->subCategoryRepository->create($input);

        Flash::success('Sub Category saved successfully.');

        return redirect(route('subCategories.index'));
    }

    /**
     * Display the specified SubCategory.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $subCategory = $this->subCategoryRepository->find($id);

        if (empty($subCategory)) {
            Flash::error('Sub Category not found');

            return redirect(route('subCategories.index'));
        }
        $main_categories=MainCategory::orderby('id','desc')->get(['name','id']);
         $subCategories = $this->subCategoryRepository->paginate(10);
        return view('main_categories.index')->with('subCategory', $subCategory) ->with('subCategories', $subCategories)->with('view', $this->view)->with(compact('main_categories'));
    }

    /**
     * Show the form for editing the specified SubCategory.
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

        $subCategory = $this->subCategoryRepository->find($id);

        if (empty($subCategory)) {
            Flash::error('Sub Category not found');

            return redirect(route('subCategories.index'));
        }
        $main_categories=MainCategory::orderby('id','desc')->get(['name','id']);
         $subCategories = $this->subCategoryRepository->paginate(10);
        return view('main_categories.index')->with('subCategory', $subCategory) ->with('edit',0)->with('subCategories', $subCategories)->with('view', $this->view)->with(compact('preferd_cnt','prefered_ids','preferd_req','main_categories'));
    }

    /**
     * Update the specified SubCategory in storage.
     *
     * @param int $id
     * @param UpdateSubCategoryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSubCategoryRequest $request)
    {
        $subCategory = $this->subCategoryRepository->find($id);

        if (empty($subCategory)) {
            Flash::error('Sub Category not found');

            return redirect(route('subCategories.index'));
        }

        $subCategory = $this->subCategoryRepository->update($request->all(), $id);

        Flash::success('Sub Category updated successfully.');

        return redirect(route('subCategories.index'));
    }

    /**
     * Remove the specified SubCategory from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $subCategory = $this->subCategoryRepository->find($id);

        if (empty($subCategory)) {
            Flash::error('Sub Category not found');

            return redirect(route('subCategories.index'));
        }

        $this->subCategoryRepository->delete($id);

        Flash::success('Sub Category deleted successfully.');

        return redirect(route('subCategories.index'));
    }

    public function get_sub_category(Request $request)
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');

        $subCategories = SubCategory::query();
        if($request->start_date){
            $startDate = CommonHelper::LocalToUtcDate($request->start_date." 00:00:00");
            $subCategories = $subCategories->where('created_at','>=',$startDate);
        }

        if($request->end_date){
            $endDate = CommonHelper::LocalToUtcDate($request->end_date." 23:59:59");
            $subCategories = $subCategories->where('created_at',"<=",$endDate);
        }
        if($request->main_cat){
            $subCategories = $subCategories->where('main_category_id',$request->main_cat);
        }
        if($request->search){
            $subCategories = $subCategories->where(function ($mainQuery) use ($request){
                $mainQuery->where('name','Like','%'.$request->search.'%')
                    ->orwhereHas('maincategory', function ($query)  use ($request){
                        $query->where('name','Like','%'.$request->search.'%');
                    });
                });
        }

        $subCategories = $subCategories->paginate($request->per_page);

        return view('sub_categories.sub_table',compact('subCategories','preferd_cnt','preferd_req','prefered_ids'))->render();
    }
}
