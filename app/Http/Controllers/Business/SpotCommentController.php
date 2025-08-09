<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SpotComment;

class SpotCommentController extends Controller
{
    private $spot_comment;

    public function __construct(SpotComment $spot_comment){
        $this->spot_comment = $spot_comment;
    }

    public function deactivateSpotComment($id){
        $this->spot_comment->destroy($id);
        return redirect()->back();
    }

    public function activateSpotComment($id){
        $this->spot_comment->onlyTrashed()->findOrFail($id)->restore();
        //restore() -- restores a soft-deleted record
        //  onlyTrashed() -- get only soft-deleted records
        return redirect()->back();
    }
}
