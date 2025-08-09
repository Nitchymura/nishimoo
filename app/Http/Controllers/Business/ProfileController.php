<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Business;
use App\Models\BusinessPromotion;
use App\Models\Quest;
use App\Models\Spot;
use App\Models\Photo;
use App\Models\BusinessComment;
use App\Models\SpotComment;
use App\Models\QuestComment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class ProfileController extends Controller
{
    private $user;
    private $business;
    private $business_promotion;
    private $quest;
    private $business_comment;
    private $spot_comment;
    private $quest_comment;
    private $spot;

    public function __construct(User $user, Business $business, BusinessPromotion $business_promotion, Quest $quest, BusinessComment $business_comment, Spot $spot, SpotComment $spot_comment, QuestComment $quest_comment){
        $this->user = $user;
        $this->business = $business;
        $this->business_promotion = $business_promotion;
        $this->quest = $quest;
        $this->business_comment = $business_comment;
        $this->spot_comment = $spot_comment;
        $this->quest_comment = $quest_comment;
        $this->spot = $spot;
        $this->middleware('auth');
    }

    public function edit($id){
        return view('businessusers.profiles.edit');
    }

    public function update(Request $request){
    $request->validate([
        'avatar' => 'max:1048|mimes:jpeg,jpg,png,gif',
        'header' => 'max:1048|mimes:jpeg,jpg,png,gif',
        'name' =>'required|max:50|unique:users,name,'.Auth::user()->id,
        'email' => 'required|max:50|email',
        //UPDATING: unique:<table>,<column>,<id of updated row>
        // CREATING: unique:<table>,<column>
        'introduction' => 'required_if:official_certification,2|max:1000'
        // 'phonenumber' => 'required_if:official_certification,2|max:20'
        // 'zip' => 'required_if:official_certification,2|max:9',
        // 'address' => 'required_if:official_certification,2|max:255'
    ], [
        'introduction.required_if' => 'Required for official certification badge'
        // 'phonenumber.required_if' => 'Required for official certification badge',
        // 'zip.required_if' => 'Required for official certification badge',
        // 'address.required_if' => 'Required for official certification badge',
    ]);
    $user_a = $this->user->findOrFail(Auth::user()->id);

    $user_a->name = $request->name;
    $user_a->email = $request->email;
    $user_a->introduction = $request->introduction;
    $user_a->website_url = $request->website_url;
    // $user_a->zip = $request->zip;
    // $user_a->address = $request->address;
    // $user_a->phonenumber = $request->phonenumber;
    $user_a->instagram = $request->instagram;
    $user_a->facebook = $request->facebook;
    $user_a->x = $request->x;
    $user_a->tiktok = $request->tiktok;

    if($request->header){
        $user_a->header = "data:image/".$request->header->extension().";base64,".base64_encode(file_get_contents($request->header));
    }
    if($request->avatar){
        $user_a->avatar = "data:image/".$request->avatar->extension().";base64,".base64_encode(file_get_contents($request->avatar));
    }
   

    $current_cert = $user_a->official_certification;

    if ($current_cert == 3) {
        if ($request->has('official_certification')) {
            // チェックあり → 特別な認定を外して普通の認定に戻す
            $user_a->official_certification = 2;
        } else {
            // チェックなし → 認定全部外す
            $user_a->official_certification = 1;
        }
    } else {
        if ($request->has('official_certification')) {
            $user_a->official_certification = 2;
        } else {
            $user_a->official_certification = 1;
        }
    }

    $user_a->save();

    return redirect()->route('profile.header',Auth::user()->id);

    }

    public function deleteAvatar(Request $request)
{
    $user = Auth::user();

    if ($user->avatar) {
        $user->avatar = null;
        $user->save();

        return response()->json(['message' => 'Avatar deleted'], 200);
    }

    return response()->json(['message' => 'No avatar found'], 404);
}

    public function deleteHeader()
    {
        $user = Auth::user();
        $user->header = null;
        $user->save();

        return response()->json(['message' => 'Header deleted']);
    }

    public function changePassword($id){
        return view('businessusers.profiles.change_password');
    }

    public function updatePassword(Request $request){
        //check if wrong old password
        $user_a = $this->user->findOrFail(Auth::user()->id); //get data of Auth user
        if(!Hash::check($request->old_password, $user_a->password)){
            //validation error
            return redirect()->back()->with('wrong_password_error', 'Wrong current password. Please try again.');
        }

        //new password the same as old password
        if($request->new_password == $request->old_password){
            //validation error
            return redirect()->back()->with('same_password_error', 'New password cannot be the same as current. Please try again.');
        }

        //new password confirmation (not the same)
        $request->validate([
            'new_password' => 'required|min:8|confirmed|alpha_num'
        ]);

        //update password
        $user_a->password = Hash::make($request->new_password);
        $user_a->save();

        return redirect()->back()->with('success_password_change', 'Password successfully changed!');
    }

    protected function getPaginatedBusinesses(Request $request, $id){
    $perPage = 3;
    $currentPage = LengthAwarePaginator::resolveCurrentPage();
    $sort = $request->get('sort', 'latest');

    // ロケーションとイベントを取得・マージ（今までと同じ）
    $locations = Business::where('category_id', 1)
        ->where('user_id', $id)
        ->withCount(['businessLikes as likes_count', 'businessComments as comments_count'])
        ->withSum(['pageViews as views_sum' => function ($query) {
            $query->where('page_type', 'App\\Models\\Business');
        }], 'views')
        ->withTrashed()
        ->get()
        ->map(fn($item) => [
            'id' => $item->id,
                'user' => $item->user,
                'user_id' => $item->user_id,
                'title' => $item->name,
                'introduction' => $item->introduction,
                'main_image' => $item->main_image,
                'category_id' => 1,
                'tab_id' => 1,
                'duration' => $item->duration,
                'official_certification' => $item->official_certification,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'likes_count' => $item->likes_count, // ← 追加
                'comments_count' => $item->comments_count,
                'views_sum' => $item->views_sum,
                'is_liked' => $item->isLiked(),
                'is_trashed' => method_exists($item, 'trashed') ? $item->trashed() : false,
                'type' => 'businesses', 
        ]);

    $events = Business::where('category_id', 2)
        ->where('user_id', $id)
        ->withCount(['businessLikes as likes_count', 'businessComments as comments_count'])
        ->withSum(['pageViews as views_sum' => function ($query) {
            $query->where('page_type', 'App\\Models\\Business');
        }], 'views')
        ->withTrashed()
        ->get()
        ->map(fn($item) => [
            'id' => $item->id,
            'user' => $item->user,
            'user_id' => $item->user_id,
            'title' => $item->name,
            'introduction' => $item->introduction,
            'main_image' => $item->main_image,
            'category_id' => 2,
            'tab_id' => 2,
            'duration' => $item->duration,
            'official_certification' => $item->official_certification,
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at,
            'likes_count' => $item->likes_count, // ← 追加
            'comments_count' => $item->comments_count,
            'views_sum' => $item->views_sum,
            'is_liked' => $item->isLiked(),
            'is_trashed' => method_exists($item, 'trashed') ? $item->trashed() : false,
            'type' => 'businesses', 
        ]);

        $others = Business::where('category_id', 6)
        ->where('user_id', $id)
        ->withCount(['businessLikes as likes_count', 'businessComments as comments_count'])
        ->withSum(['pageViews as views_sum' => function ($query) {
            $query->where('page_type', 'App\\Models\\Business');
        }], 'views')
        ->withTrashed()
        ->get()
        ->map(fn($item) => [
            'id' => $item->id,
            'user' => $item->user,
            'user_id' => $item->user_id,
            'title' => $item->name,
            'introduction' => $item->introduction,
            'main_image' => $item->main_image,
            'category_id' => 6,
            'tab_id' => 6,
            'duration' => $item->duration,
            'official_certification' => $item->official_certification,
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at,
            'likes_count' => $item->likes_count, // ← 追加
            'comments_count' => $item->comments_count,
            'views_sum' => $item->views_sum,
            'is_liked' => $item->isLiked(),
            'is_trashed' => method_exists($item, 'trashed') ? $item->trashed() : false,
            'type' => 'businesses', 
        ]);

    $businesses = $locations->concat($events)->concat($others);

    // ソート（必要に応じて拡張）
    $businesses = match($sort) {
        'latest' => $businesses->sortByDesc('created_at'),
        default  => $businesses->sortByDesc('created_at'),
    };

    // ページネーション
    return new LengthAwarePaginator(
        $businesses->forPage($currentPage, $perPage),
        $businesses->count(),
        $perPage,
        $currentPage,
        [
            'path' => $request->url(),
            'query' => $request->query(),
        ]
    );
}

protected function getPaginatedQuests(Request $request, $id){
    $perPage = 3;
    $currentPage = LengthAwarePaginator::resolveCurrentPage();
    $sort = $request->get('sort', 'latest');

    // ロケーションとイベントを取得・マージ（今までと同じ）
    $quests = Quest::with('user')
        ->where('user_id', $id)
        ->withCount(['questLikes as likes_count'])
        ->withCount(['questComments as comments_count'])
        ->withSum(['pageViews as views_sum' => function ($query) {
            $query->where('page_type', 'App\\Models\\Quest');
        }], 'views')
        ->withTrashed()
        ->get()
        // ->paginate(6)
        ->map(fn($item) => [
            'id' => $item->id,
                'user' => $item->user,
                'user_id' => $item->user_id,
                'title' => $item->title,
                'introduction' => $item->introduction,
                'main_image' => $item->main_image,
                'category_id' => null,
                'tab_id' => 4,
                'duration' => $item->duration,
                'start_date' => $item->start_date,
                'end_date' => $item->end_date,
                'official_certification' => null,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'likes_count' => $item->likes_count, // ← 追加
                'comments_count' => $item->comments_count,
                'views_sum' => $item->views_sum,
                'is_liked' => $item->isLiked(),
                'is_trashed' => method_exists($item, 'trashed') ? $item->trashed() : false,
                'type' => 'quests', 
        ]);


    // ソート（必要に応じて拡張）
    $quests = match($sort) {
        'latest' => $quests->sortByDesc('created_at'),
        default  => $quests->sortByDesc('created_at'),
    };

    // ページネーション
    return new LengthAwarePaginator(
        $quests->forPage($currentPage, $perPage),
        $quests->count(),
        $perPage,
        $currentPage,
        [
            'path' => $request->url(),
            'query' => $request->query(),
        ]
    );
}

protected function getPaginatedPromotions(Request $request, $id){
    $perPage = 3;
    $currentPage = LengthAwarePaginator::resolveCurrentPage();
    $sort = $request->get('sort', 'latest');
    $business_comments = DB::table('business_comments')
        ->join('businesses', 'business_comments.business_id', '=', 'businesses.id')
        ->where('businesses.user_id', $id)
        ->select('business_comments.*') 
        ->get();

    // ロケーションとイベントを取得・マージ（今までと同じ）
    $business_promotions = BusinessPromotion::with('user', 'business')
        ->where('user_id', $id)
        ->withTrashed()
        ->get()
        ->map(fn($item) => [
            'id' => $item->id,
            'user' => $item->user,
            'user_id' => $item->user_id,
            'business_name' => optional($item->business)->name,
            'title' => $item->title,
            'introduction' => $item->introduction,
            'main_image' => $item->image,
            'category_id' => null,
            'tab_id' => 3,
            'duration' => null,
            'official_certification' => null,
            'promotion_start' => $item->promotion_start,
            'promotion_end' => $item->promotion_end,
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at,
            'likes_count' => null,
            'comments_count' => null,
            // 'views_count' => $item->views_count,
            'is_liked' => null,
            'is_trashed' => method_exists($item, 'trashed') ? $item->trashed() : false,
            'type' => 'promotions', 
        ]);


    // ソート（必要に応じて拡張）
    $business_promotions = match($sort) {
        'latest' => $business_promotions->sortByDesc('created_at'),
        default  => $business_promotions->sortByDesc('created_at'),
    };

    // ページネーション
    return new LengthAwarePaginator(
        $business_promotions->forPage($currentPage, $perPage),
        $business_promotions->count(),
        $perPage,
        $currentPage,
        [
            'path' => $request->url(),
            'query' => $request->query(),
        ]
    );
}
protected function getPaginatedSpots(Request $request, $id){
    $perPage = 3;
    $currentPage = LengthAwarePaginator::resolveCurrentPage();
    $sort = $request->get('sort', 'latest');

    $spots = Spot::with('user')
        ->withCount(['spotLikes as likes_count'])
        ->withCount(['spotComments as comments_count'])
        ->withSum(['pageViews as views_sum' => function ($query) {
            $query->where('page_type', 'App\\Models\\Spot');
        }], 'views')
        ->where('user_id', $id)
        ->withTrashed()
        ->get()
        ->map(fn($item) => [
            'id' => $item->id,
            'user' => $item->user,
            'user_id' => $item->user_id,
            'business_name' => null,
            'title' => $item->title,
            'introduction' => $item->introduction,
            'main_image' => $item->main_image,
            'category_id' => null,
            'tab_id' => 5,
            'duration' => null,
            'official_certification' => $item->official_certification,
            'promotion_start' => null,
            'promotion_end' => null,
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at,
            'likes_count' => $item->likes_count,
            'comments_count' => $item->comments_count,
            'views_sum' => $item->views_sum,
            'is_liked' => $item->isLiked(),
            'is_trashed' => method_exists($item, 'trashed') ? $item->trashed() : false,
            'type' => 'spots', 
        ]);
    // ソート（必要に応じて拡張）
    $spots = match($sort) {
        'latest' => $spots->sortByDesc('created_at'),
        default  => $spots->sortByDesc('created_at'),
    };


    // ページネーション
    return new LengthAwarePaginator(
        $spots->forPage($currentPage, $perPage),
        $spots->count(),
        $perPage,
        $currentPage,
        [
            'path' => $request->url(),
            'query' => $request->query(),
        ]
    );
}

protected function getPaginatedLikedPosts(Request $request, $id){
    $perPage = 3;
    $currentPage = LengthAwarePaginator::resolveCurrentPage();
    $sort = $request->get('sort', 'latest');

    $user_a = $this->user->findOrFail($id);

    $likedBusinessIds = $user_a->businessLikes()->pluck('business_id')->toArray();
    $locations = Business::where('category_id', 1)
        ->whereIn('id', $likedBusinessIds)
        ->withCount(['businessLikes as likes_count', 'businessComments as comments_count'])
        ->withSum(['pageViews as views_sum' => function ($query) {
            $query->where('page_type', 'App\\Models\\Business');
        }], 'views')
        ->get()
        ->map(fn($item) => [
            'id' => $item->id,
                'user' => $item->user,
                'user_id' => $item->user_id,
                'title' => $item->name,
                'introduction' => $item->introduction,
                'main_image' => $item->main_image,
                'category_id' => 1,
                'tab_id' => 1,
                'duration' => $item->duration,
                'official_certification' => $item->official_certification,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'likes_count' => $item->likes_count, // ← 追加
                'comments_count' => $item->comments_count,
                'views_sum' => $item->views_sum,
                'is_liked' => $item->isLiked(),
                'is_trashed' => method_exists($item, 'trashed') ? $item->trashed() : false,
                'type' => 'businesses', 
        ]);

    $events = Business::where('category_id', 2)
        ->whereIn('id', $likedBusinessIds)
        ->withCount(['businessLikes as likes_count', 'businessComments as comments_count'])
        ->withSum(['pageViews as views_sum' => function ($query) {
            $query->where('page_type', 'App\\Models\\Business');
        }], 'views')
        ->get()
        ->map(fn($item) => [
            'id' => $item->id,
                'user' => $item->user,
                'user_id' => $item->user_id,
                'title' => $item->name,
                'introduction' => $item->introduction,
                'main_image' => $item->main_image,
                'category_id' => 2,
                'tab_id' => 2,
                'duration' => $item->duration,
                'official_certification' => $item->official_certification,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'likes_count' => $item->likes_count, // ← 追加
                'comments_count' => $item->comments_count,
                'views_sum' => $item->views_sum,
                'is_liked' => $item->isLiked(),
                'is_trashed' => method_exists($item, 'trashed') ? $item->trashed() : false,
                'type' => 'businesses', 
        ]);
    $others = Business::where('category_id', 6)
        ->whereIn('id', $likedBusinessIds)
        ->withCount(['businessLikes as likes_count', 'businessComments as comments_count'])
        ->withSum(['pageViews as views_sum' => function ($query) {
            $query->where('page_type', 'App\\Models\\Business');
        }], 'views')
        ->get()
        ->map(fn($item) => [
            'id' => $item->id,
                'user' => $item->user,
                'user_id' => $item->user_id,
                'title' => $item->name,
                'introduction' => $item->introduction,
                'main_image' => $item->main_image,
                'category_id' => 6,
                'tab_id' => 6,
                'duration' => $item->duration,
                'official_certification' => $item->official_certification,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'likes_count' => $item->likes_count, // ← 追加
                'comments_count' => $item->comments_count,
                'views_sum' => $item->views_sum,
                'is_liked' => $item->isLiked(),
                'is_trashed' => method_exists($item, 'trashed') ? $item->trashed() : false,
                'type' => 'businesses', 
        ]);

    $likedSpotIds = $user_a->spotLikes()->pluck('spot_id')->toArray();
    $spots = Spot::with('user')
        ->whereIn('id', $likedSpotIds)
        ->withCount(['spotLikes as likes_count'])
        ->withCount(['spotComments as comments_count'])
        ->withSum(['pageViews as views_sum' => function ($query) {
            $query->where('page_type', 'App\\Models\\Spot');
        }], 'views')
        ->get()
        ->map(fn($item) => [
            'id' => $item->id,
            'user' => $item->user,
            'user_id' => $item->user_id,
            'business_name' => null,
            'title' => $item->title,
            'introduction' => $item->introduction,
            'main_image' => $item->main_image,
            'category_id' => null,
            'tab_id' => 5,
            'duration' => null,
            'official_certification' => $item->official_certification,
            'promotion_start' => null,
            'promotion_end' => null,
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at,
            'likes_count' => $item->likes_count,
            'comments_count' => $item->comments_count,
            'views_sum' => $item->views_sum,
            'is_liked' => $item->isLiked(),
            'is_trashed' => method_exists($item, 'trashed') ? $item->trashed() : false,
            'type' => 'spots', 
        ]);

        $likedQuestIds = $user_a->questLikes()->pluck('quest_id')->toArray();
        $quests = Quest::with('user')
        ->whereIn('id', $likedQuestIds)
        ->withCount(['questLikes as likes_count'])
        ->withCount(['questComments as comments_count'])
        ->withSum(['pageViews as views_sum' => function ($query) {
            $query->where('page_type', 'App\\Models\\Quest');
        }], 'views')
        ->withTrashed()
        ->get()
        ->map(fn($item) => [
            'id' => $item->id,
                'user' => $item->user,
                'user_id' => $item->user_id,
                'title' => $item->title,
                'introduction' => $item->introduction,
                'main_image' => $item->main_image,
                'category_id' => null,
                'tab_id' => 4,
                'duration' => $item->duration,
                'start_date' => $item->start_date,
                'end_date' => $item->end_date,
                'official_certification' => null,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'likes_count' => $item->likes_count, // ← 追加
                'comments_count' => $item->comments_count,
                'views_sum' => $item->views_sum,
                'is_liked' => $item->isLiked(),
                'is_trashed' => method_exists($item, 'trashed') ? $item->trashed() : false,
                'type' => 'quests', 
        ]);

    $likedPosts = $locations->concat($events)->concat($spots)->concat($quests)->concat($others);

    // ソート（必要に応じて拡張）
    $likedPosts = match($sort) {
        'latest' => $likedPosts->sortByDesc('created_at'),
        default  => $likedPosts->sortByDesc('created_at'),
    };

    // ページネーション
    return new LengthAwarePaginator(
        $likedPosts->forPage($currentPage, $perPage),
        $likedPosts->count(),
        $perPage,
        $currentPage,
        [
            'path' => $request->url(),
            'query' => $request->query(),
        ]
    );

    }
    protected function getPaginatedLikedQuests(Request $request, $id){
        $perPage = 3;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $sort = $request->get('sort', 'latest');
    
        $user_a = $this->user->findOrFail($id);
    
        
        $likedQuestIds = $user_a->questLikes()->pluck('quest_id')->toArray();
        $likedQuests = Quest::with('user')
        ->whereIn('id', $likedQuestIds)
        ->withCount(['questLikes as likes_count'])
        ->withCount(['questComments as comments_count'])
        ->withSum(['pageViews as views_sum' => function ($query) {
            $query->where('page_type', 'App\\Models\\Quest');
        }], 'views')
        ->get()
        ->map(fn($item) => [
            'id' => $item->id,
            'user' => $item->user,
            'user_id' => $item->user_id,
            'title' => $item->title,
            'introduction' => $item->introduction,
            'main_image' => $item->main_image,
            'category_id' => null,
            'tab_id' => 4,
            'duration' => $item->duration,
            'start_date' => $item->start_date,
            'end_date' => $item->end_date,
            'official_certification' => null,
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at,
            'likes_count' => $item->likes_count, // ← 追加
            'comments_count' => $item->comments_count,
            'views_sum' => $item->views_sum,
            'is_liked' => $item->isLiked(),
            'is_trashed' => method_exists($item, 'trashed') ? $item->trashed() : false,
            'type' => 'quests', 
        ]);

    // ソート（必要に応じて拡張）
    $likedQuests = match($sort) {
        'latest' => $likedQuests->sortByDesc('created_at'),
        default  => $likedQuests->sortByDesc('created_at'),
    };

    // ページネーション
    return new LengthAwarePaginator(
        $likedQuests->forPage($currentPage, $perPage),
        $likedQuests->count(),
        $perPage,
        $currentPage,
        [
            'path' => $request->url(),
            'query' => $request->query(),
        ]
    );

    }
    

    protected function getPaginatedLikedBusinesses(Request $request, $id){
        $perPage = 3;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $sort = $request->get('sort', 'latest');
    
        $user_a = $this->user->findOrFail($id);
    
        $likedBusinessIds = $user_a->businessLikes()->pluck('business_id')->toArray();
        $locations = Business::where('category_id', 1)
            ->whereIn('id', $likedBusinessIds)
            ->withCount(['businessLikes as likes_count', 'businessComments as comments_count'])
            ->withSum(['pageViews as views_sum' => function ($query) {
                $query->where('page_type', 'App\\Models\\Business');
            }], 'views')
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                    'user' => $item->user,
                    'user_id' => $item->user_id,
                    'title' => $item->name,
                    'introduction' => $item->introduction,
                    'main_image' => $item->main_image,
                    'category_id' => 1,
                    'tab_id' => 1,
                    'duration' => $item->duration,
                    'official_certification' => $item->official_certification,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'likes_count' => $item->likes_count, // ← 追加
                    'comments_count' => $item->comments_count,
                    'views_sum' => $item->views_sum,
                    'is_liked' => $item->isLiked(),
                    'is_trashed' => method_exists($item, 'trashed') ? $item->trashed() : false,
                    'type' => 'businesses', 
            ]);
    
        $events = Business::where('category_id', 2)
            ->whereIn('id', $likedBusinessIds)
            ->withCount(['businessLikes as likes_count', 'businessComments as comments_count'])
            ->withSum(['pageViews as views_sum' => function ($query) {
                $query->where('page_type', 'App\\Models\\Business');
            }], 'views')
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'user' => $item->user,
                'user_id' => $item->user_id,
                'title' => $item->name,
                'introduction' => $item->introduction,
                'main_image' => $item->main_image,
                'category_id' => 2,
                'tab_id' => 2,
                'duration' => $item->duration,
                'official_certification' => $item->official_certification,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'likes_count' => $item->likes_count, // ← 追加
                'comments_count' => $item->comments_count,
                'views_sum' => $item->views_sum,
                'is_liked' => $item->isLiked(),
                'is_trashed' => method_exists($item, 'trashed') ? $item->trashed() : false,
                'type' => 'businesses', 
            ]);
        $others = Business::where('category_id', 6)
            ->whereIn('id', $likedBusinessIds)
            ->withCount(['businessLikes as likes_count', 'businessComments as comments_count'])
            ->withSum(['pageViews as views_sum' => function ($query) {
                $query->where('page_type', 'App\\Models\\Business');
            }], 'views')
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'user' => $item->user,
                'user_id' => $item->user_id,
                'title' => $item->name,
                'introduction' => $item->introduction,
                'main_image' => $item->main_image,
                'category_id' => 6,
                'tab_id' => 6,
                'duration' => $item->duration,
                'official_certification' => $item->official_certification,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'likes_count' => $item->likes_count, // ← 追加
                'comments_count' => $item->comments_count,
                'views_sum' => $item->views_sum,
                'is_liked' => $item->isLiked(),
                'is_trashed' => method_exists($item, 'trashed') ? $item->trashed() : false,
                'type' => 'businesses', 
            ]);
       
        $likedBusinesses = $locations->concat($events)->concat($others);
    
        // ソート（必要に応じて拡張）
        $likedBusinesses = match($sort) {
            'latest' => $likedBusinesses->sortByDesc('created_at'),
            default  => $likedBusinesses->sortByDesc('created_at'),
        };
    
        // ページネーション
        return new LengthAwarePaginator(
            $likedBusinesses->forPage($currentPage, $perPage),
            $likedBusinesses->count(),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );
    
        }
    
    protected function getPaginatedLikedSpots(Request $request, $id){
        $perPage = 3;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $sort = $request->get('sort', 'latest');
    
        $user_a = $this->user->findOrFail($id);
    
        $likedSpotIds = $user_a->spotLikes()->pluck('spot_id')->toArray();
        $likedSpots = Spot::with('user')
            ->whereIn('id', $likedSpotIds)
            ->withCount(['spotLikes as likes_count'])
            ->withCount(['spotComments as comments_count'])
            ->withSum(['pageViews as views_sum' => function ($query) {
                $query->where('page_type', 'App\\Models\\Spot');
            }], 'views')
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'user' => $item->user,
                'user_id' => $item->user_id,
                'business_name' => null,
                'title' => $item->title,
                'introduction' => $item->introduction,
                'main_image' => $item->main_image,
                'category_id' => null,
                'tab_id' => 5,
                'duration' => null,
                'official_certification' => $item->official_certification,
                'promotion_start' => null,
                'promotion_end' => null,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'likes_count' => $item->likes_count,
                'comments_count' => $item->comments_count,
                'views_sum' => $item->views_sum,
                'is_liked' => $item->isLiked(),
                'is_trashed' => method_exists($item, 'trashed') ? $item->trashed() : false,
                'type' => 'spots', 
            ]);
    
        // ソート（必要に応じて拡張）
        $likedSpots = match($sort) {
            'latest' => $likedSpots->sortByDesc('created_at'),
            default  => $likedSpots->sortByDesc('created_at'),
        };
    
        // ページネーション
        return new LengthAwarePaginator(
            $likedSpots->forPage($currentPage, $perPage),
            $likedSpots->count(),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );
    
        }
    
    protected function getPaginatedComments(Request $request, $id){
        $perPage = 5;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $sort = $request->get('sort', 'latest');
    
        $user_a = $this->user->findOrFail($id);

        $business_comments = DB::table('business_comments')
        ->join('businesses', 'business_comments.business_id', '=', 'businesses.id')
        ->where('businesses.user_id', $id)
        ->select('business_comments.*') 
        ->get();
    
        $businesses = BusinessComment::with('user', 'business')
        ->where('user_id', $id)
            ->withCount(['businessCommentLikes as likes_count'])
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'user' => $item->user,
                'user_id' => $item->user_id,
                'business_id' => optional($item->business)->id,
                'posted_user_id' => optional($item->business)->user_id,
                'user_name' => optional($item->user)->name,
                'title' => optional($item->business)->name,
                'main_image' => optional($item->business)->main_image,
                'comment' => $item->content,
                'rating' => $item->rating,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'likes_count' => $item->likes_count, // ← 追加
                // 'views_count' => $item->views_count,
                'is_liked' => $item->isLiked(),
                'is_trashed' => method_exists($item, 'trashed') ? $item->trashed() : false,
                'type' => 'businesses', 
            ]);

        $spots = SpotComment::with('user','spot')
        ->where('user_id', $id)
            ->withCount(['spotCommentLikes as likes_count'])
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'user' => $item->user,
                'user_id' => $item->user_id,
                'spot_id' => optional($item->spot)->id,
                'posted_user_id' => optional($item->spot)->user_id,
                'user_name' => optional($item->user)->name,
                'title' => optional($item->spot)->title,
                'main_image' => optional($item->spot)->main_image,
                'comment' => $item->content,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'likes_count' => $item->likes_count, 
                'rating' => null,
                'is_liked' => $item->isLiked(),
                'is_trashed' => method_exists($item, 'trashed') ? $item->trashed() : false,
                'type' => 'spots', 
            ]);
    
            $quests = QuestComment::with('user','quest')
            ->where('user_id', $id)
            ->withCount(['questCommentLikes as likes_count'])
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'user' => $item->user,
                'user_id' => $item->user_id,
                'quest_id' => optional($item->quest)->id,
                'posted_user_id' => optional($item->quest)->user_id,
                'user_name' => optional($item->user)->name,
                'title' => optional($item->quest)->title,
                'main_image' => optional($item->quest)->main_image,
                'comment' => $item->content,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'likes_count' => $item->likes_count, 
                'rating' => null,
                'is_liked' => $item->isLiked(),
                'is_trashed' => method_exists($item, 'trashed') ? $item->trashed() : false,
                'type' => 'quests', 
            ]);
    
        $commentedPosts = $businesses->concat($spots)->concat($quests);
    
        // ソート（必要に応じて拡張）
        $commentedPosts = match($sort) {
            'latest' => $commentedPosts->sortByDesc('created_at'),
            default  => $commentedPosts->sortByDesc('created_at'),
        };
    
        // ページネーション
        return new LengthAwarePaginator(
            $commentedPosts->forPage($currentPage, $perPage),
            $commentedPosts->count(),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );
    
        }
    


    public function showProfile(Request $request, $id){
        $user_a = $this->user->findOrFail($id);
        $all_businesses = $this->business->withTrashed()->where('user_id', $user_a->id)->latest()->get();
        $business_comments = DB::table('business_comments')
        ->join('businesses', 'business_comments.business_id', '=', 'businesses.id')
        ->where('businesses.user_id', $id)
        ->select('business_comments.*') 
        ->get();
        
        if ($user_a->role_id == 1) {
            $tab = $request->get('tab', 'quests'); // ← 観光ユーザー（例）
        } elseif ($user_a->role_id == 2) {
            $tab = $request->get('tab', 'businesses'); // ← ビジネスユーザー（例）
        } else {
            $tab = $request->get('tab', 'default'); // ← その他のロールがあれば fallback
        }
        $section = $request->get('section', null); // ← これがポイント

        $businesses = $this->getPaginatedBusinesses($request, $id);
        $business_promotions = $this->getPaginatedPromotions($request, $id);
        $quests = $this->getPaginatedQuests($request, $id);
        $spots = $this->getPaginatedSpots($request, $id);
        $likedPosts = $this->getPaginatedLikedPosts($request, $id);
        $likedBusinesses = $this->getPaginatedLikedBusinesses($request, $id);
        $likedSpots = $this->getPaginatedLikedSpots($request, $id);
        $likedQuests = $this->getPaginatedLikedQuests($request, $id);
        $commentedPosts = $this->getPaginatedComments($request, $id);
        $followers = $user_a->followers()->paginate(5);
        $follows = $user_a->follows()->paginate(5);

        
        return view('businessusers.profiles.header_modify', compact('all_businesses', 'business_comments', 'businesses','business_promotions', 'quests', 'spots','tab','section','followers','follows','likedPosts','likedBusinesses', 'likedSpots', 'likedQuests', 'commentedPosts'))->with('user', $user_a)->with('activeTab', $tab);
    }



    public function deactivate($id){
        $user = User::findOrFail($id);
        $user->delete();
    
        Auth::logout(); // セッションからログアウト
        request()->session()->invalidate(); // セッション無効化
        request()->session()->regenerateToken(); // CSRFトークン再生成
    
        return redirect()->route('home'); // ゲストのトップページにリダイレクト
    }
    // public function deactivate($id){
    //     $this->business->destroy($id);
    //     return view('register.tourist');
    // }
}
