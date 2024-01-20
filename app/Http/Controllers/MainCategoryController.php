<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use App\Http\Requests\CreateMainCategoryRequest;
use App\Http\Requests\UpdateMainCategoryRequest;
use App\Models\MainCategory;
use App\Models\UserProfile;
use App\Repositories\MainCategoryRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\DB;
use Response;

class MainCategoryController extends AppBaseController
{
    /** @var  MainCategoryRepository */
    private $mainCategoryRepository;

    public function __construct(MainCategoryRepository $mainCategoryRepo)
    {
        $this->mainCategoryRepository = $mainCategoryRepo;
    }

    /**
     * Display a listing of the MainCategory.
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

        return view('main_categories.index')
            ->with('view',"main_categories")->with(compact('preferd_cnt','prefered_ids','preferd_req'));
    }

    /**
     * Show the form for creating a new MainCategory.
     *
     * @return Response
     */
    public function create()
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
        $mainCategories = $this->mainCategoryRepository->paginate(10);
        return view('main_categories.index') ->with('mainCategories', $mainCategories)->with('view',"main_categories")->with(compact('preferd_req','preferd_cnt','prefered_ids'));
    }

    /**
     * Store a newly created MainCategory in storage.
     *
     * @param CreateMainCategoryRequest $request
     *
     * @return Response
     */
    public function store(CreateMainCategoryRequest $request)
    {
        $input = $request->all();

        $mainCategory = $this->mainCategoryRepository->create($input);

        Flash::success('Main Category saved successfully.');

        return redirect(route('mainCategories.index'));
    }

    /**
     * Display the specified MainCategory.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $mainCategory = $this->mainCategoryRepository->find($id);

        if (empty($mainCategory)) {
            Flash::error('Main Category not found');

            return redirect(route('mainCategories.index'));
        }


        return view('main_categories.index')->with('mainCategory', $mainCategory)->with('view',"main_categories");;
    }

    /**
     * Show the form for editing the specified MainCategory.
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

        $mainCategory = $this->mainCategoryRepository->find($id);

        if (empty($mainCategory)) {
            Flash::error('Main Category not found');

            return redirect(route('mainCategories.index'));
        }
        $mainCategories = $this->mainCategoryRepository->paginate(10);
        return view('main_categories.index')->with('mainCategory', $mainCategory)->with('edit',1)->with('mainCategories', $mainCategories)->with('view',"main_categories")->with(compact('preferd_cnt','prefered_ids','preferd_req'));;
    }

    /**
     * Update the specified MainCategory in storage.
     *
     * @param int $id
     * @param UpdateMainCategoryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMainCategoryRequest $request)
    {
        $mainCategory = $this->mainCategoryRepository->find($id);

        if (empty($mainCategory)) {
            Flash::error('Main Category not found');

            return redirect(route('mainCategories.index'));
        }

        $mainCategory = $this->mainCategoryRepository->update($request->all(), $id);

        Flash::success('Main Category updated successfully.');

        return redirect(route('mainCategories.index'));
    }

    /**
     * Remove the specified MainCategory from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $mainCategory = $this->mainCategoryRepository->find($id);

        if (empty($mainCategory)) {
            Flash::error('Main Category not found');

            return redirect(route('mainCategories.index'));
        }

        $this->mainCategoryRepository->delete($id);

        Flash::success('Main Category deleted successfully.');

        return redirect(route('mainCategories.index'));
    }

    public function get_main_category(Request $request)
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');

        $mainCategories = MainCategory::query();

        if($request->start_date){
            $startDate = CommonHelper::LocalToUtcDate($request->start_date." 00:00:00");
            $mainCategories = $mainCategories->where('created_at','>=',$startDate);
        }

        if($request->end_date){
            $endDate = CommonHelper::LocalToUtcDate($request->end_date." 23:59:59");
            $mainCategories = $mainCategories->where('created_at',"<=",$endDate);
        }

        if($request->search){
            $mainCategories = $mainCategories->where('name','Like','%'.$request->search.'%');
        }
        $mainCategories = $mainCategories->paginate($request->per_page);

        return view('main_categories.sub_table',compact('mainCategories','preferd_cnt','preferd_req','prefered_ids'))->render();
    }
}
