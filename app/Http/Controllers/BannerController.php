<?php

namespace App\Http\Controllers;
//namespace Trending\Http\Controllers\File;

use App\Helpers\CommonHelper;
use App\Http\Requests\CreateBannerRequest;
use App\Http\Requests\UpdateBannerRequest;
use App\Models\Banner;
use App\Models\BannerPackage;
use App\Repositories\BannerRepository;
use App\Http\Controllers\AppBaseController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Flash;
use Response;
use App\Models\UserProfile;
//use VideoThumbnail;
//use Lakshmaji\Thumbnail\Facade\Thumbnail;

class BannerController extends AppBaseController
{
    /** @var  BannerRepository */
    private $bannerRepository;
    public $view = "banners";
    public function __construct(BannerRepository $bannerRepo)
    {
        $this->bannerRepository = $bannerRepo;
    }

    /**
     * Display a listing of the Banner.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
//        $bn=url('public/Banners/Banner_219950.mp4');
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
        return view('banners.index')
            ->with('view', $this->view)->with('index',0)->with(compact('preferd_cnt','preferd_req','prefered_ids'));
    }

    /**
     * Show the form for creating a new Banner.
     *
     * @return Response
     */
    public function create()
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
         $banners = $this->bannerRepository->paginate(10);
         $bannerPackages = BannerPackage::get();
        return view('banners.index') ->with('banners', $banners)->with('view', $this->view)->with('bannerPackages', $bannerPackages)->with(compact('prefered_ids','preferd_req','preferd_cnt'));
    }

    /**
     * Store a newly created Banner in storage.
     *
     * @param CreateBannerRequest $request
     *
     * @return Response
     */
    public function store(CreateBannerRequest $request)
    {
        $input = $request->all();

        if($request->type==0) {
            if ($request->hasFile('banner_photo')) {
                $image = $request->file('banner_photo');
                $image_name = 'Banner_' . rand(111111, 999999) . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('Banners');
                $imageName = 'Banners/' . $image_name;
                $image->move($destinationPath, $image_name);
                $input['banner_photo'] = $imageName;

//            $imgext=array("png","jpg", "jpeg", "jpe", "jif", "jfif", "jfi","tiff","tif","raw","arw","svg","svgz","bmp", "dib");
//            $videoext=array("avi","flv","wmv","mov","mp4");
//            $infoPath = pathinfo(public_path($image_name));
//            $extension = $infoPath['extension'];
//            if(in_array($extension,$imgext)){
//                dd($extension);
//                $input['type']=0;
//            }
//            elseif(in_array($extension,$videoext)){
//                dd($extension);
//                $input['type']=1;
//            }
            }
        }
        else if($request->type==1){
            $input['banner_photo'] = $request->video_link;
            /*$video_id = explode("?v=", $input['banner_photo']);
            $video_id = $video_id[1];
            $thumbnail="http://img.youtube.com/vi/".$video_id."/maxresdefault.jpg";
            $input['thumbnail_img']=$thumbnail;*/
        }
//        $input['duration_digit']=$request->duration;
        $duration = CommonHelper::convertDurationToSecond(Carbon::now(),$input['duration'],$input['duration_type']);

        $input['duration'] = $duration;

        $input['start_date'] =  CommonHelper::LocalToUtcDateTime($input['start_date']);

        $banner = $this->bannerRepository->create($input);

        Flash::success('Banner saved successfully.');

        return redirect(route('banners.index'));
    }

    /**
     * Display the specified Banner.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $banner = $this->bannerRepository->find($id);

        if (empty($banner)) {
            Flash::error('Banner not found');

            return redirect(route('banners.index'));
        }
         $banners = $this->bannerRepository->paginate(10);
        return view('banners.show')->with('banner', $banner)->with('banners', $banners)->with('view', $this->view);
    }

    /**
     * Show the form for editing the specified Banner.
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
        $banner = $this->bannerRepository->find($id);

        if (empty($banner)) {
            Flash::error('Banner not found');

            return redirect(route('banners.index'));
        }
         $banners = $this->bannerRepository->paginate(10);
        $bannerPackages = BannerPackage::get();
        return view('banners.index')->with('banner', $banner) ->with('banners', $banners)->with('view', $this->view)->with('edit',0)->with('bannerPackages', $bannerPackages)->with(compact('preferd_cnt','preferd_req','prefered_ids'));
    }

    /**
     * Update the specified Banner in storage.
     *
     * @param int $id
     * @param UpdateBannerRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBannerRequest $request)
    {
        $banner = $this->bannerRepository->find($id);

        if (empty($banner)) {
            Flash::error('Banner not found');

            return redirect(route('banners.index'));
        }
        $input = $request->all();

        $deleted_photo = isset($input['deleted_photo']) ? $input['deleted_photo'] : '';
        if($deleted_photo == $banner->banner_photo){
            unlink(public_path( $banner->banner_photo));
            $input['banner_photo'] = '';
        }
        if($request->type==0) {
            if ($request->hasFile('banner_photo')) {
                $image = $request->file('banner_photo');
                $image_name = 'Banner_' . rand(111111, 999999) . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('Banners');
                $imageName = 'Banners/' . $image_name;
                $image->move($destinationPath, $image_name);
                $input['banner_photo'] = $imageName;
//                $input['thumbnail_img'] = null;
            }
            if($request->video_link==null){
                $input['banner_photo']=$banner->banner_photo;
//                $input['thumbnail_img']=null;
            }
        }
        elseif($request->type==1){
            /*if($request->video_link==null){
                $input['banner_photo']=$banner->banner_photo;
                $input['thumbnail_img']=null;
            }*/
//            else{
                $input['banner_photo'] = $request->video_link;
                $video_id = explode("?v=", $input['banner_photo']);
                $video_id = $video_id[1];
                $thumbnail="http://img.youtube.com/vi/".$video_id."/maxresdefault.jpg";
//                $input['thumbnail_img']=$thumbnail;
        }
//        $input['duration_digit']=$request->duration;
        $duration = CommonHelper::convertDurationToSecond(Carbon::now(),$input['duration'],$input['duration_type']);
        $input['duration'] = $duration;
        $input['start_date'] =  CommonHelper::LocalToUtcDateTime($input['start_date']);
        $banner = $this->bannerRepository->update($input, $id);

        Flash::success('Banner updated successfully.');
        return redirect(route('banners.index'));
    }

    /**
     * Remove the specified Banner from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $banner = $this->bannerRepository->find($id);

        if (empty($banner)) {
            Flash::error('Banner not found');

            return redirect(route('banners.index'));
        }

        $this->bannerRepository->delete($id);

        Flash::success('Banner deleted successfully.');

        return redirect(route('banners.index'));
    }

    public function get_banners(Request $request)
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
        $banners = Banner::query();

        if($request->start_date){
            $startDate = CommonHelper::LocalToUtcDate($request->start_date." 00:00:00");
            $banners = $banners->where('start_date','>=',$startDate);
        }

        if($request->end_date){
            $endDate = CommonHelper::LocalToUtcDate($request->end_date." 23:59:59");
            $banners = $banners->where('start_date',"<=",$endDate);
        }

        if($request->search){
            $banners = $banners->where(function ($mainQuery) use($request){
                            $mainQuery->where('name','Like','%'.$request->search.'%')
                                ->orwhere('contact','Like','%'.$request->search.'%')
                                ->orwhere('price','Like','%'.$request->search.'%')
                                ->orwhere('location','Like','%'.$request->search.'%');
                       });
        }
        $banners = $banners->paginate($request->per_page);

        return view('banners.sub_table',compact('banners','prefered_ids','preferd_req','preferd_cnt'))->render();
    }
}
