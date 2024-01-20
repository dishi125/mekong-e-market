<?php

namespace App\Http\Controllers;

use App\Enums\Type;
use App\Enums\UserType;
use App\Helpers\CommonHelper;
use App\Http\Requests\CreatenotificationRequest;
use App\Http\Requests\UpdatenotificationRequest;
use App\Models\Notification;
use App\Models\UserProfile;
use App\Repositories\notificationRepository;
use App\Http\Controllers\AppBaseController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Log;
use Response;

class notificationController extends AppBaseController
{
    /** @var  notificationRepository */
    private $notificationRepository;
    public $view='notifications';

    public function __construct(notificationRepository $notificationRepo)
    {
        $this->notificationRepository = $notificationRepo;
    }

    /**
     * Display a listing of the notification.
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
        return view('notifications.index')
            ->with('view',$this->view)->with(compact('prefered_ids','preferd_req','preferd_cnt'));
    }

    /**
     * Show the form for creating a new notification.
     *
     * @return Response
     */
    public function create()
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
         $notifications = $this->notificationRepository->paginate(10);
        return view('notifications.index') ->with('notifications', $notifications)->with('view',$this->view)->with(compact('prefered_ids','preferd_req','preferd_cnt'));
    }

    /**
     * Store a newly created notification in storage.
     *
     * @param CreatenotificationRequest $request
     *
     * @return Response
     */
    public function store(CreatenotificationRequest $request)
    {
        $input = $request->all();
        $input['date'] = CommonHelper::LocalToUtcDateTime($input['date']);

        $current_date = Carbon::now()->format('Y-m-d H');
        $notification_date = $input['date']->format('Y-m-d H');

        $notification = $this->notificationRepository->create($input);

        if(strtotime($current_date) == strtotime($notification_date)) {

            $notification = Notification::with([
                'userTypeWise' => function($query){
                    $query->select('id','user_type');
                },
                'userIdWise' => function($query){
                    $query->select('id','user_type');

                }])->find($notification->id);

            //all user
            $all_users = UserProfile::where('user_type','!=',0)->pluck('id')->toArray();

            if($notification->user_type == 0) {
                $users = $all_users;
            } else if($notification->user_type == 1) {
                $users = $notification->userTypeWise->pluck('id')->toArray();
            } else {
                $users = $notification->userIdWise->pluck('id')->toArray();
            }

            $notification_array = array();
            $notification_array['title'] = $notification->title;
            $notification_array['message'] = $notification->description;

            try {
                CommonHelper::sendPushNotification($users,$notification_array);
                $notification->is_sent = 1;
                $notification->save();

            } catch (\Exception $e) {
                Log::error($e->getTraceAsString());
            }
        }

        Flash::success('Notification saved successfully.');

        return redirect(route('notifications.index'));
    }

    /**
     * Display the specified notification.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $notification = $this->notificationRepository->find($id);

        if (empty($notification)) {
            Flash::error('Notification not found');

            return redirect(route('notifications.index'));
        }
         $notifications = $this->notificationRepository->paginate(10);
        return view('notifications.index')->with('notification', $notification) ->with('notifications', $notifications)->with('view',$this->view);
    }

    /**
     * Show the form for editing the specified notification.
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
        $notification = $this->notificationRepository->find($id);

        if (empty($notification)) {
            Flash::error('Notification not found');

            return redirect(route('notifications.index'));
        }

        $user_id = ['' => ''];
        if($notification->type_id){
            $users= UserProfile::where('user_type','=',$notification->type_id)->get();
            $user_id['']="Select User";
            foreach ($users as $us)
            {
                $user_id[$us->id]=$us->name;
            }
        }

         $notifications = $this->notificationRepository->paginate(10);
        return view('notifications.index',compact('user_id'))->with('notification', $notification)->with('edit',0) ->with('notifications', $notifications)->with('view',$this->view)->with(compact('prefered_ids','preferd_req','preferd_cnt'));
    }

    /**
     * Update the specified notification in storage.
     *
     * @param int $id
     * @param UpdatenotificationRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatenotificationRequest $request)
    {
        $notification = $this->notificationRepository->find($id);

        if (empty($notification)) {
            Flash::error('Notification not found');

            return redirect(route('notifications.index'));
        }
        $input = $request->all();
        $input['date'] = CommonHelper::LocalToUtcDateTime($input['date']);
        $notification = $this->notificationRepository->update($input, $id);

        Flash::success('Notification updated successfully.');

        return redirect(route('notifications.index'));
    }

    /**
     * Remove the specified notification from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $notification = $this->notificationRepository->find($id);

        if (empty($notification)) {
            Flash::error('Notification not found');

            return redirect(route('notifications.index'));
        }

        $this->notificationRepository->delete($id);

        Flash::success('Notification deleted successfully.');

        return redirect(route('notifications.index'));
    }

    public function get_notifications(Request $request)
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
        $notifications = Notification::Query();

        if($request->start_date){
            $startDate = CommonHelper::LocalToUtcDate($request->start_date." 00:00:00");
            $notifications = $notifications->where('created_at','>=',$startDate);
        }

        if($request->end_date){
            $endDate = CommonHelper::LocalToUtcDate($request->end_date." 23:59:59");
            $notifications = $notifications->where('created_at',"<=",$endDate);
        }

        if(isset($request->user_type) && $request->user_type != ''){
            $notifications = $notifications->where('user_type',$request->user_type);
        }

        if($request->search){
            $notifications = $notifications->where(function ($mainQuery) use($request){
                $mainQuery->where('title','Like','%'.$request->search.'%')
                            ->orWhere('description','Like','%'.$request->search.'%')
                            ->orwhereHas('user',function ($query) use($request){
                                $query->where('name','Like','%'.$request->search.'%');
                            });
            });
        }
        $notifications = $notifications->paginate($request->per_page);

        return view('notifications.sub_table',compact('notifications','preferd_cnt','preferd_req','prefered_ids'))->render();
    }

    public function notificationTest(Request $request) {
        try{
//        dd($request->notification_text);
        $data['title'] = "Test Message";
        $data['message'] = $request->notification_text;
//        dd($data);
        $user_id = UserProfile::pluck('id')->toArray();
        CommonHelper::sendPushNotification($user_id, $data);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
//                dd($e->getTraceAsString());
            Log::error($e->getTraceAsString());
        }
    }
}
