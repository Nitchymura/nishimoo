<?php

namespace App\Http\Controllers\Spot;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use App\Models\Spot;
use App\Models\User;
use App\Models\SpotLike;

class SpotLikeController extends Controller
{
    private $spot;
    private $user;
    private $spot_like;

    public function __construct(Spot $spot, User $user, SpotLike $spot_like)
    {
        $this->spot = $spot;
        $this->user = $user;
        $this->spot_like = $spot_like;
    }

    public function show($spot_id){
        $spot = Spot::findOrFail($spot_id);

        try {
            DB::table('spot_likes')->insert([
                'user_id' => Auth::id(), 
                'spot_id' => $spot_id
            ]);
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
        }

        return response()->json(['status' => 'success']);
    }

    public function store(Request $request){
        $spot_id = $request->input('spot_id'); // またはルートから渡すなら引数にする
        $spot = Spot::findOrFail($spot_id);

        $this->spot_like
            ->where('user_id', Auth::id())
            ->where('spot_id', $spot_id)
            ->delete();

            return redirect()->back();
    }

    public function getLikesJson($spotId){
        $spot = Spot::with('likes.user')->findOrFail($spotId);
        $authUser = auth()->user(); //エラー出るけどこのままで大丈夫らしい！

        $likes = $spot->likes->map(function ($like) use ($authUser) {
            $user = $like->user;
            return [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $user->avatar,
                'is_own' => $authUser && $authUser->id === $user->id,
                'is_followed' => $authUser && $authUser->follows->contains('followed_id', $user->id),
            ];
        });

        return response()->json($likes);
    }

    public function getModalHtml($spot_id){
        $spot = Spot::with('likes.user')->findOrFail($spot_id);
        return view('spots.likes-modal', compact('spot'))->render();
    }


    public function storeSpotLike($spot_id){
    $this->spot_like->user_id = Auth::id();
    $this->spot_like->spot_id = $spot_id;
    $this->spot_like->save();

    // フォーム送信時なら redirect に変更
    return redirect()->back();
}

    public function storeSpotLikeJson($spot_id){
        // すでにLikeがあれば重複を防止（必要なら）
        SpotLike::firstOrCreate([
            'user_id' => Auth::id(),
            'spot_id' => $spot_id,
        ]);
    
        return response()->json([
            'message' => 'Liked',
            'spot_id' => $spot_id,
        ]);
    }
    
    public function deleteSpotLike($spot_id)
{
    SpotLike::where('user_id', Auth::id())
        ->where('spot_id', $spot_id)
        ->delete();

    return redirect()->back();
}


    public function deleteSpotLikeJson($spot_id){
        SpotLike::where('user_id', Auth::id())
            ->where('spot_id', $spot_id)
            ->delete();
    
        return response()->json([
            'message' => 'Unliked',
            'spot_id' => $spot_id,
        ]);
    }
    
}

