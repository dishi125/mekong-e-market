<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserSettingsControllers extends Controller
{
    //
    public function index()
    {
        $preferd_cnt=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->count();
        $preferd_req=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->get();
        $prefered_ids=UserProfile::where('preferred_status',1)->where('is_seen_preferred',0)->pluck('id');
        return view('change_password.index')->with(compact('preferd_cnt','prefered_ids','preferd_req'));
    }
    public function changepassword(Request $request)
    {
        $user=Auth::user();
        $request->validate([
            'oldpassword' => ['required', function ($attribute, $value, $fail) use ($user) {
                if (!\Hash::check($value, $user->password)) {
                    return $fail(__('The current password is incorrect.'));
                }
            }],
        ],["oldpassword.required"=>"Please Enter Current Password.",

            ]);
        $request->validate([
            'newpassword' => 'required|min:8',
            'repassword' => 'required|same:newpassword',

        ],[
            "newpassword.required"=>"Please Enter New Password.",
            "newpassword.min"=>"Please enter new password attlist 8 characters.",
            "repassword.required"=>"Please Enter Re-type New Password.",
            "repassword.same"=>"Please Enter New Password and Retype New Password Same."
        ]);
        $user= User::find($user->id);
        $user->password=Hash::make($request->newpassword);
        $user->save();
        Auth::logout();
        return redirect(route('login'));
    }
}
