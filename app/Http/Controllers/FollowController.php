<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Follow;

class FollowController extends Controller
{
    private $follow;
    public function __construct(Follow $follow){
        $this->follow = $follow;
    }

    public function storeFollow($user_id){
        $this->follow->follower_id =  Auth::user()->id;  //user who clicks "follow"
        $this->follow->followed_id =  $user_id;  //target user (being followed)
        $this->follow->save();

        //go to previous page
        return redirect()->back();
    }

    public function deleteFollow($user_id){
        $this->follow->where('follower_id', Auth::user()->id)
                    ->where('followed_id', $user_id)
                    ->delete();
        return redirect()->back();
    }


    
    // For AJAX
    public function follow($user_id){

        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
      
        Follow::create([
            'follower_id' => Auth::id(),
            'followed_id' => $user_id,
        ]);

        return response()->json(['message' => 'Followed']);
    }

    public function unfollow($user_id){

        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        
        Follow::where('follower_id', Auth::id())
                ->where('followed_id', $user_id)
                ->delete();
        
        return response()->json(['message' => 'Unfollowed']);
    }
}
