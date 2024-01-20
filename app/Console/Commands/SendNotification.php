<?php

namespace App\Console\Commands;

use App\Helpers\CommonHelper;
use App\Models\Notification;
use App\Models\UserProfile;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send_notification:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
//        Log::info("---------script-start-------");

        $current_date = \Carbon\Carbon::now()->format('Y-m-d H:i');
        $notifications = Notification::with([
                        'userTypeWise' => function($query){
                            $query->select('id','user_type');
                        },
                        'userIdWise' => function($query){
                            $query->select('id','user_type');

                        }])->where('date','<=',$current_date)
                        ->where('is_sent',0)
                        ->get();

        //all user
        $all_users = UserProfile::where('user_type','!=',0)->pluck('id')->toArray();

        foreach ($notifications as $notification){

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
//                Log::error($users);
                CommonHelper::sendPushNotification($users,$notification_array);
                $notification->is_sent = 1;
                $notification->save();

            } catch (\Exception $e) {
                Log::error($e->getTraceAsString());
            }
        }

//        Log::info("---------script-done-------");
    }
}
