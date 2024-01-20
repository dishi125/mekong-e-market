<?php

namespace App\Console\Commands;

use App\Helpers\CommonHelper;
use App\Models\UserProfile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Post;

class LivetradeNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'livetrade:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Trade notification of live';

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
//        dd($current_date.":00");
//        $current_date="2020-07-17 08:25:00";
        $posts=Post::where('date_time',$current_date)->where('can_show',1)->get();
//        Log::info("count:".$posts);
        if($posts->count()>0){
            foreach ($posts as $post){
                $users=UserProfile::get();
                foreach ($users as $user){
                    if($user->area_id==$post->product->area_id){
                        $notification_array=array();
                        $notification_array['title']="Post Live";
                        $notification_array['message']="In your area ".$post->product->product_name." live";
//                      dd($notification_array);
//                        Log::info("notification : ",$notification_array);
                        $users=array($user->id);
                        CommonHelper::sendPushNotification($users,$notification_array);
//                        CommonHelper::my_sendpushnotifications($users,$notification_array);
                    }
                }
            }
        }
//        Log::info("---------script-done-------");
    }
}
