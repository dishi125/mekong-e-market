<?php

namespace App\Http\Controllers\API;

use App\Enums\Type;
use App\Http\Controllers\Controller;
use App\Models\Favourite;
use App\Models\Post;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\AppBaseController;

class FavouritesAPIController extends AppBaseController
{
    public function favouriteUnFavourite(Request $request){

        $messages = [
            'user_profile_id.required' => 'Please enter your id.',
            'post_id' => 'Please enter post id.'
        ];

        $validator = Validator::make($request->all(), [
            'user_profile_id' => 'required',
            'post_id' => 'required',
        ], $messages);

        if ($validator->fails()) {
            return $this->responseError($validator->messages()->first());
        }

        $user = UserProfile::find($request->user_profile_id);
        if (!$user) {
            return $this->responseError("User Not Found");
        }

        $post = Post::find($request->post_id);
        if (!$post) {
            return $this->responseError("Post Not Found");
        }

        if($post->product->user_profile_id == $request->user_profile_id){
            $user_type = 1;//seller can only like his product other-wise that is buyer
        } else {
            $user_type = 0;
        }

        $fav_post = Favourite::where('user_profile_id',$request->user_profile_id)
                                ->where('post_id',$request->post_id)
                                ->where('user_type',$user_type)
                                ->first();

        if(!$fav_post){
            $fav=new Favourite;
            $fav->user_type = $user_type;
            $fav->user_profile_id = $request->user_profile_id;
            $fav->post_id = $request->post_id;
            $fav->save();
            return $this->sendSuccess("Post added to Favourite Posts.");

        }else{
            $fav_post->forcedelete();
            return $this->sendSuccess("Post removed from Favourite Post.");
        }

    }
}
