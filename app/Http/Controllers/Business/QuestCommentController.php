<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\QuestComment;

class QuestCommentController extends Controller
{
    private $quest_comment;

    public function __construct(QuestComment $quest_comment){
        $this->quest_comment = $quest_comment;
    }

    public function deactivateQuestComment($id){
        $this->quest_comment->destroy($id);
        return redirect()->back();
    }

    public function activateQuestComment($id){
        $this->quest_comment->onlyTrashed()->findOrFail($id)->restore();
        //restore() -- restores a soft-deleted record
        //  onlyTrashed() -- get only soft-deleted records
        return redirect()->back();
    }
}
