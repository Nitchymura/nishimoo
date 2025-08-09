<?php
namespace App\Http\Controllers\Quest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\QuestComment;
use App\Models\QuestCommentLike;
use App\Models\QuestBody;

class QuestCommentController extends Controller
{
    private $quest_comment;

    public function __construct(QuestComment $quest_comment){
        $this->quest_comment = $quest_comment;
    }

    //coment
    public function toggleCommentLike($commentId) { 
        $user = Auth::user(); 
        $comment = QuestComment::with('questCommentLikes.user')->findOrFail($commentId);
    
        $like = $comment->questCommentLikes()
                        ->where('user_id', $user->id)
                        ->first();
    
        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            QuestCommentLike::create([
                'quest_comment_id' => $comment->id,
                'user_id' => $user->id,
            ]);
            $liked = true;
        }
    
        // もう一度最新のいいねを取得（削除後の反映）
        $likes = $comment->questCommentLikes()->with('user')->get();
    
        $users = $likes->map(function ($like) use ($user) {
            $likedUser = $like->user;
            return [
                'id' => $likedUser->id,
                'name' => $likedUser->name,
                'avatar' => $likedUser->avatar ? asset('storage/' . $likedUser->avatar) : null,
                'isOwn' => $user->id === $likedUser->id,
                'isFollowing' => $user->follows->contains('followed_id', $likedUser->id),
            ];
        });
    
        return response()->json([
            'liked' => $liked,
            'like_count' => $likes->count(),
            'quest_id' => $comment->quest_id,
            'users' => $users,
        ]);
    }
    
    public function getCommentLikes($commentId){
        $comment = QuestComment::with('QuestCommentlikes.user')->findOrFail($commentId);
    
        $users = $comment->QuestCommentlikes->map(function ($like) {
            $user = $like->user;
            $auth = Auth::user();
    
            return [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $user->avatar,
                'is_own' => $auth && $auth->id === $user->id,
                'is_followed' => $auth ? $auth->follows->contains('followed_id', $user->id) : false,
            ];
        });
    
        return response()->json($users);
    }
    
    
    public function getCommentStats($questId) { 
        $comments = QuestComment::with('questCommentLikes') ->where('quest_id', $questId) ->get();
        $data = $comments->map(function ($comment) {
            return [
                'id' => $comment->id,
                'like_count' => $comment->questCommentLikes->count(),
                'liked_by_auth_user' => $comment->questCommentLikes->contains('user_id', Auth::id())
            ];
        });
        
        return response()->json([
            'comment_count' => $comments->count(),
            'comment_stats' => $data,
        ]);
    }
    
    public function storeQuestComment(Request $request, $quest_id){
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);
    
        $comment = new QuestComment();
        $comment->quest_id = $quest_id;
        $comment->user_id = Auth::id();
        $comment->content = $request->input('content');
        $comment->save();
    
        return redirect()->back()->with('message', 'Comment posted!');
    }
    
    public function deleteQuestComment($id){
        $comment = QuestComment::findOrFail($id);
    
        // コメントの所有者だけが削除できる
        if ($comment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
    
        $comment->delete();
    
        // 正しいルート名 'show' にリダイレクト
        return redirect()->route('quest.show', $comment->quest_id)
                         ->with('message', 'Comment deleted.');
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
