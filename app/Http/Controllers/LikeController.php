<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\QuestLike;
use App\Models\SpotLike;
use App\Models\BusinessLike;

class LikeController extends Controller
{
    public function store($type, $id){
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'ログインしてません'], 401);
        }

        switch($type){
            case 'quest':
                QuestLike::create([
                    'user_id'       => $user->id,
                    'quest_id'      => $id,
                ]);
                break;

            case 'spot':
                SpotLike::create([
                    'user_id'       => $user->id,
                    'spot_id'       => $id,
                ]);
                break;

            case 'business':
                BusinessLike::create([
                    'user_id'       => $user->id,
                    'business_id'   => $id,
                ]);
                break;

            default:
                abort(404);
        }

        $likesCount = match ($type) {
            'quest'    => QuestLike::where('quest_id', $id)->count(),
            'spot'     => SpotLike::where('spot_id', $id)->count(),
            'business' => BusinessLike::where('business_id', $id)->count(),
            default    => 0,
        };

        return response()->json([
            'message' => 'Liked',
            'likes_count' => $likesCount
        ]);
    }

    public function destroy($type, $id){
        $user = Auth::user();

        switch ($type) {
            case 'quest':
                QuestLike::where('user_id', $user->id)->where('quest_id', $id)->delete();
                break;

            case 'spot':
                SpotLike::where('user_id', $user->id)->where('spot_id', $id)->delete();
                break;

            case 'business':
                BusinessLike::where('user_id', $user->id)->where('business_id', $id)->delete();
                break;

            default:
                abort(404);
        }

        $likesCount = match ($type) {
            'quest'    => QuestLike::where('quest_id', $id)->count(),
            'spot'     => SpotLike::where('spot_id', $id)->count(),
            'business' => BusinessLike::where('business_id', $id)->count(),
            default    => 0,
        };

        return response()->json([
            'message' => 'Unliked',
            'likes_count' => $likesCount
        ]);
    }
}
