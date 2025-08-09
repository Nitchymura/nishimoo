<?php

namespace App\Http\Controllers\Quest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\QuestLike;
use App\Models\User;
use App\Models\Quest;

class QuestLikeController extends Controller
{
    private $quest_like;
    private $user;
    private $quest;

    public function __construct(QuestLike $quest_like, Quest $quest, User $user){
        $this->quest_like = $quest_like;
        $this->quest = $quest;
        $this->user = $user;
    }
    
    public function storeQuestLike($quest_id){
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'please login.'], 401);
        }

        $this->quest_like->user_id = Auth::user()->id;
        $this->quest_like->quest_id = $quest_id; //post we are liking
        $this->quest_like->save();

        //go to previous page
        return redirect()->back();
    }

    public function deleteQuestLike($quest_id){
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'please login.'], 401);
        }
        //delete()
        $this->quest_like->where('user_id', Auth::user()->id)
                    ->where('quest_id', $quest_id)
                    ->delete();

        return redirect()->back();
    }
}
