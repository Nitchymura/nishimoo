<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BusinessCommentLike;

class BusinessCommentLikeController extends Controller
{
    private $business_comment_like;
    
    public function __construct(BusinessCommentLike $business_comment_like){
        $this->business_comment_like = $business_comment_like;
    }

    public function store($comment_id){
        // 既に「いいね」しているか確認
        $existing_like = $this->business_comment_like
            ->where('user_id', Auth::user()->id)
            ->where('business_comment_id', $comment_id)
            ->first();

        // 既に「いいね」していなければ保存
        if(!$existing_like){
            $this->business_comment_like->create([
                'user_id' => Auth::user()->id,
                'business_comment_id' => $comment_id
            ]);
        }

        return redirect()->back();
    }

    public function destroy($comment_id){
        // 「いいね」を取り消す
        $this->business_comment_like
            ->where('user_id', Auth::user()->id)
            ->where('business_comment_id', $comment_id)
            ->delete();

        return redirect()->back();
    }
}
