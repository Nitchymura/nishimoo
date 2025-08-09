<?php

namespace App\Http\Controllers\Spot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SpotCommentLike;
use App\Models\SpotComment;
use Illuminate\Support\Facades\Auth;

class SpotCommentLikeController extends Controller
{
    private $likeComment;
    private $comment;

    public function __construct(SpotCommentLike $likeComment, SpotComment $comment)
    {
        $this->likeComment = $likeComment;
        $this->comment = $comment;
        $this->middleware('auth');
    }

    // SpotCommentLikeController.php
    public function like($comment_id)
    {
        SpotCommentLike::create([
            'user_id' => Auth::id(),
            'spot_comment_id' => $comment_id
        ]);

        // $count = SpotCommentLike::where('spot_comment_id', $comment_id)->count();

        // return response()->json(['count' => $count]);
        return redirect()->back();
    }

    public function unlike($comment_id)
    {
        SpotCommentLike::where('user_id', Auth::id())
            ->where('spot_comment_id', $comment_id)
            ->delete();

        // $count = SpotCommentLike::where('spot_comment_id', $comment_id)->count();

        // return response()->json(['count' => $count]);
        return redirect()->back();
    }

    public function getLikesJson($comment_id){
        $comment = SpotComment::with('spotcommentlikes.user')->findOrFail($comment_id);

        $authUser = Auth::user();

        $users = $comment->spotcommentlikes->map(function ($like) use ($authUser) {
            $user = $like->user;
            return [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $user->avatar,
                'is_own' => $authUser && $authUser->id === $user->id,
                'is_followed' => $authUser && $authUser->follows->contains('followed_id', $user->id),
            ];
        });

        return response()->json($users);
    }

    public function getCommentModalHtml($comment_id){
    $comment = SpotComment::with('spotcommentlikes.user')->findOrFail($comment_id);
    return view('spots.comment.modals.spot-comment-likes', compact('comment'))->render();
    }



}