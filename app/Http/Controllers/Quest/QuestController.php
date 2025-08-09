<?php

namespace App\Http\Controllers\Quest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\QuestBody;
use App\Models\Quest;
use App\Models\Spot;
use App\Models\Business;
use Carbon\Carbon;
use App\Models\User;
use App\Models\QuestLike;

use Illuminate\Support\Facades\Http; 

use App\Models\Follow;


class QuestController extends Controller{
    private $questbody;
    private $quest;
    private $user;
    private $business;

    public function __construct(Quest $quest, User $user, Business $business){
        $this->quest = $quest;
        $this->user = $user;
        $this->business = $business;
    }
    
// =============================================================Add Quest
    public function showAddQuest(){
        return view('quests.add-quest');
    }    

    public function storeQuest(Request $request){
        $user = Auth::user();
    
        // 共通バリデーションルール
        $rules = [
            'title' => 'required|string|max:30',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'duration' => 'nullable|integer|min:1|max:30',
            'introduction' => 'required|string|max:500',
            'main_image' => 'required|file|max:1048|mimes:jpg,jpeg,png,gif',
            'is_public' => 'nullable|in:0,1',
        ];
    
        // ロールに応じた追加バリデーション
        if ($user->role_id == 1) {
            $rules['start_date'] = 'required|date';
            $rules['end_date'] = 'required|date|after_or_equal:start_date';
        } elseif ($user->role_id == 2) {
            $rules['duration'] = 'required|integer|min:1|max:30';
        }
    
        // バリデーション実行
        $validated = $request->validate($rules);
    
        // モデル保存処理
        $quest = new Quest();
        $quest->title = $request->title;
        $quest->user_id = $user->id;
        $quest->start_date = $request->start_date;
        $quest->end_date = $request->end_date;
        $quest->duration = $request->duration;
        $quest->introduction = $request->introduction;
        $quest->is_public = $request->is_public ?? 0;  // デフォルト値設定
    
        // 画像処理
        $quest->main_image = "data:image/".$request->main_image->extension().";base64,".base64_encode(file_get_contents($request->main_image));
        
        // if ($request->hasFile('main_image')) {
        //     $fileName = time() . '_' . $request->main_image->getClientOriginalName();
        //     $filePath = $request->main_image->storeAs('images/quests', $fileName, 'public');
        //     $quest->main_image = '/' . $filePath; // 先頭にスラッシュを追加
        // }        
    
        $quest->save();
    
        return redirect()->route('quest.edit', ['quest_id' => $quest->id]);
    }
    

    // quest_idに基づいてデータを取得
    public function getSpotsByQuestId($questId){
        $spots = Spot::where('quest_id', $questId)->get();
        return response()->json($spots); // JSONとしてレスポンス
    }

   
    public function deleteQuest($id){
        // $this->post->destroy($id);
        $this->quest->findOrFail($id)->forceDelete();

        return redirect()->route('quest.add');
    }

    function getBorderColorClass($day){
        $classes = ['border-quest-red', 'border-quest-navy', 'border-quest-green', 'border-quest-blue'];
        return $classes[($day - 1) % count($classes)];
    }

    function getColorClass($day){
        $classes = ['color-red', 'color-navy', 'color-green', 'color-Blue'];
        return $classes[($day - 1) % count($classes)];
    }

//================================================================Edit Quest
    
    public function showQuestEdit($quest_id){
        $user = Auth::user();
        $quest = Quest::withTrashed()
        ->with(['questBodies.spot', 'questBodies.business'])
        ->findOrFail($quest_id);

        // 💥 自分のじゃなかったら403エラー
        if (Auth::id() !== $quest->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // dayList 作成（これは今のままでOK）
        $dayList = [];
        $dayNumber = 1;

        if ($user->role_id == 1 && $quest->start_date && $quest->end_date) {
            $start = \Carbon\Carbon::parse($quest->start_date);
            $end = \Carbon\Carbon::parse($quest->end_date);
            $current = $start->copy();

            while ($current->lte($end)) {
                $dayList[] = [
                    'number' => $dayNumber,
                    'date' => $current->format('Y-m-d'),
                ];
                $current->addDay();
                $dayNumber++;
            }
        } elseif ($user->role_id == 2 && $quest->duration) {
            for ($i = 1; $i <= $quest->duration; $i++) {
                $dayList[] = [
                    'number' => $i,
                    'date' => null,
                ];
            }
        }

        // 🔁 questBodies に色クラスを付与してBladeに渡す
        $coloredBodies = $quest->questBodies->map(function ($body) {
            $colorClasses = ['red', 'navy', 'green', 'Blue'];
            $index = ($body->day_number - 1) % count($colorClasses);
            $color = $colorClasses[$index];

            $body->border_class = "border-quest-{$color}";
            $body->color_class = "color-{$color}";
            return $body;
        });

        return view('quests.edit-quest', [
            'quest' => $quest,
            'dayList' => $dayList,
            'questBodies' => $coloredBodies,
        ]);
    }

    public function updateQuest(Request $request, $quest_id){
        $quest = Quest::withTrashed()->find($quest_id);

        if (!$quest) {
            return response()->json(['error' => 'Quest not found'], 404);
        }

        try {
            $validated = $request->validate([
                'title' => 'required|string|max:30',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'duration' => 'nullable|integer|min:1|max:30',
                'introduction' => 'nullable|string|max:500',
                'main_image' => 'nullable|file|max:1048|mimes:jpg,jpeg,png,gif',
                'is_public' => 'nullable|in:0,1',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        $quest->title = $request->title;
        $quest->introduction = $request->introduction;
        $quest->is_public = $request->is_public ?? "0";

        $roleId = Auth::user()->role_id;

        if ($roleId == 1) {
            // ✅ 一般ユーザー
            $quest->start_date = $request->start_date;
            $quest->end_date = $request->end_date;
            $quest->duration = null;
        } elseif ($roleId == 2) {
            // ✅ 企業ユーザー
            $quest->duration = $request->duration;
            $quest->start_date = null;
            $quest->end_date = null;
        }

        if($request->main_image){
            $quest->main_image = "data:image/".$request->main_image->extension().";base64,".base64_encode(file_get_contents($request->main_image));
        }
        // if ($request->hasFile('main_image')) {
        //     $fileName = time() . '_' . $request->main_image->getClientOriginalName();
        //     $filePath = $request->main_image->storeAs('images/quests', $fileName, 'public');
        //     $quest->main_image = '/' . $filePath; // ← storeと同様に '/' を追加
        // }        

        $quest->save();
        $quest->refresh();

        // ✅ $dayList を role に応じて生成（helpers使わない）
        $dayList = [];
        $dayNumber = 1;

        if ($roleId == 1 && $quest->start_date && $quest->end_date) {
            $start = \Carbon\Carbon::parse($quest->start_date);
            $end = \Carbon\Carbon::parse($quest->end_date);
            $current = $start->copy();

            while ($current->lte($end)) {
                $dayList[] = [
                    'number' => $dayNumber,
                    'date' => $current->format('Y-m-d'),
                ];
                $current->addDay();
                $dayNumber++;
            }
        } elseif ($roleId == 2 && $quest->duration) {
            for ($i = 1; $i <= $quest->duration; $i++) {
                $dayList[] = [
                    'number' => $i,
                    'date' => null,
                ];
            }
        }

        // クエストボディ取得
        $questBodies = QuestBody::with(['spot', 'business'])
            ->where('quest_id', $quest_id)
            ->orderBy('day_number', 'asc')
            ->get();

            return redirect()->route('quest.edit', ['quest_id' => $quest_id]);
    }

// =============================================================Confirm Quest
    public function showConfirmQuest($id){
        $quest = Quest::withTrashed()->with(['questBodies.spot', 'questBodies.business'])->findOrFail($id);
        $user = $quest->user;
        $allBodies = $quest->questBodies ?? collect();

        // 💥 自分のじゃなかったら403エラー
        if (Auth::id() !== $quest->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // 色クラスを付与
        $coloredBodies = $allBodies->map(function ($body) {
            $colorClasses = ['red', 'navy', 'green', 'Blue'];
            $index = ($body->day_number - 1) % count($colorClasses);
            $color = $colorClasses[$index];

            $body->border_class = "border-quest-{$color}";
            $body->color_class = "color-{$color}";
            return $body;
        });

        $agendaBodies = $coloredBodies->where('is_agenda', 1)->sortBy('day_number');
        $questBodies = $coloredBodies->sortBy('day_number');

        // 地図用ロケーション作成
        $locations = [];
        foreach ($questBodies as $body) {
            // dd($body->spot?->geo_lat, $body->spot?->geo_lng);

            if ($body->spot && $body->spot->geo_lat && $body->spot->geo_lng) {
                $locations[] = [
                    'lat' => $body->spot->geo_lat,
                    'lng' => $body->spot->geo_lng,
                    'title' => $body->spot->title ?? 'Spot'
                    
                ];
            } elseif ($body->business && $body->business->address_1) {
                $coords = self::getLatLngFromAddress($body->business->address_1);
                if ($coords) {
                    $locations[] = [
                        'lat' => $coords['lat'],
                        'lng' => $coords['lng'],
                        'title' => $body->business->name ?? 'Business'
                    ];
                }
            }
        }

        return view('quests.confirm-quest', [
            'quest_a' => $quest,
            'agenda_bodys' => $agendaBodies,
            'questBodies' => $questBodies,
            'locations' => $locations, // 🔥 追加
        ]);
    }

    //Follow, Following

    public function toggleFollow($id){
        $authUser = Auth::user();

        if ($authUser->id == $id) {
            return response()->json(['error' => 'You cannot follow yourself.'], 400);
        }

        $follow = Follow::where('follower_id', $authUser->id)
                        ->where('followed_id', $id)
                        ->first();

        $isFollowing = false;

        if ($follow !== null) {
            $deleted = Follow::where('follower_id', $authUser->id)
                            ->where('followed_id', $id)
                            ->delete();
        } else {
            Follow::create([
                'follower_id' => $authUser->id,
                'followed_id' => $id,
            ]);
            $isFollowing = true;
        }

        return response()->json([
            'status' => 'ok',
            'is_following' => $isFollowing,
        ]);
    }


    //Like Button
    public function toggleLike($id){
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $quest = Quest::findOrFail($id);
        $like = $quest->questLikes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            QuestLike::create([
                'quest_id' => $quest->id,
                'user_id' => $user->id,
            ]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'like_count' => $quest->questLikes()->count(),
        ]);
    }
    

    //Like Modal
    public function getLikes($id){
        $quest = Quest::with('questLikes.user')->findOrFail($id); // ✅ リレーション名修正

        $authUser = Auth::user();

        $likeUsers = $quest->questLikes->map(function ($like) use ($authUser) {
            $user = $like->user;

            return [
                'id' => $user->id ?? null,
                'name' => $user->name ?? 'Unknown',
                'avatar' => $user->avatar ?? null,
                'is_own' => $authUser && $authUser->id === ($user->id ?? null),
                'is_following' => $authUser && $user ? $authUser->follows->contains('followed_id', $user->id) : false,
            ];
        });

        return response()->json($likeUsers);
    }

//===============================================================View Quest
    public function showViewQuest($id){
        $quest = Quest::with(['questBodies.spot', 'questBodies.business'])->findOrFail($id);
        $user = $quest->user;
        $allBodies = $quest->questBodies ?? collect();

        // 色クラスを付与
        $coloredBodies = $allBodies->map(function ($body) {
            $colorClasses = ['red', 'navy', 'green', 'Blue'];
            $index = ($body->day_number - 1) % count($colorClasses);
            $color = $colorClasses[$index];

            $body->border_class = "border-quest-{$color}";
            $body->color_class = "color-{$color}";
            return $body;
        });

        $agendaBodies = $coloredBodies->where('is_agenda', 1)->sortBy('day_number');
        $questBodies = $coloredBodies->sortBy('day_number');

        // 地図用ロケーション作成
        $locations = [];
        foreach ($questBodies as $body) {
            if ($body->spot && $body->spot->geo_lat && $body->spot->geo_lng) {
                $locations[] = [
                    'lat' => $body->spot->geo_lat,
                    'lng' => $body->spot->geo_lng,
                    'title' => $body->spot->title ?? 'Spot'
                ];
            } elseif ($body->business && $body->business->address_1) {
                $coords = self::getLatLngFromAddress($body->business->address_1);
                if ($coords) {
                    $locations[] = [
                        'lat' => $coords['lat'],
                        'lng' => $coords['lng'],
                        'title' => $body->business->name ?? 'Business'
                    ];
                }
            }
        }

        return view('quests.view-quest', [
            'quest_a' => $quest,
            'agenda_bodys' => $agendaBodies,
            'questBodies' => $questBodies,
            'locations' => $locations, // 🔥 追加
        ]);
    }

    public function getModalHtml($questId){
        $quest = Quest::with('likes.user')->findOrFail($questId);
        return view('quests.modals.quest.likes-modal', [
            'quest_a' => $quest  // ← ここで quest_a として渡す！
        ])->render();
    }


//===============================================================LatLng
    private static function getLatLngFromAddress($address){
        $apiKey = env('GOOGLE_MAPS_API_KEY');
        $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
            'address' => $address,
            'key' => $apiKey
        ]);
    
        $json = $response->json();
    
        if ($json['status'] === 'OK') {
            $location = $json['results'][0]['geometry']['location'];
            return [
                'lat' => $location['lat'],
                'lng' => $location['lng']
            ];
        }

    
        return null;
    }
    

//===============================================================RESTORE
public function restore($quest_id){
    $quest = Quest::withTrashed()->findOrFail($quest_id);

    if ($quest->trashed()) {
        $quest->restore();
    }

    $previousUrl = url()->previous();

    // URLに 'confirm' が含まれていれば quest.show にリダイレクト
    if (str_contains($previousUrl, '/quest/confirm')) {
        return redirect()->route('quest.show', $quest_id);
    }

    // URLに 'profile' が含まれていれば profile.header にリダイレクト
    if (str_contains($previousUrl, '/profile')) {
        return redirect()->route('profile.header', Auth::id());
    }

    // それ以外の場合は fallback として quest.show に
    return redirect()->route('quest.show', $quest_id);
}

//===============================================================SOFT DELETE
    public function softDelete($quest_id){
        $quest = Quest::withTrashed()->findOrFail($quest_id);
        $quest->delete();

        // ユーザーのロールIDによってリダイレクトを振り分け

        return redirect()->route('profile.header', Auth::user()->id);

    }
//==============================================================PROFILE USE
    public function deactivate($id){
        $this->quest->destroy($id);
        return redirect()->back();
    }

    public function activate($id){
        $this->quest->onlyTrashed()->findOrFail($id)->restore();
        return redirect()->back();
    }

}

