<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Quest;
use App\Models\Spot;
use Illuminate\Pagination\LengthAwarePaginator;

class PostsController extends Controller
{
    private $quest;
    private $spot;
    private $user;
    
    public function __construct(Quest $quest, Spot $spot, User $user){
        $this->quest = $quest;
        $this->spot = $spot;
        $this->user = $user;

    }

    //Allshow
    public function indexTourists(Request $request){
        $sort = $request->get('sort', 'created_at');
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        // Spots
        $spots = Spot::with('user')
        ->withTrashed()
        ->get()
        ->map(function ($item) {
            return [
                'id' => $item->id,
                'user' => $item->user,
                'user_id' => $item->user_id,
                'user_name' => optional($item->user)->name,
                'title' => $item->title,
                'main_image' => $item->main_image,
                'category_id' => null,
                'tab_id' => 1,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'is_trashed' => method_exists($item, 'trashed') ? $item->trashed() : false,
                'type' => 'spots',                  
            ];
        });
    
        // Quests
        $quests = Quest::with('user')
        ->withTrashed()
        ->get()
        ->map(function ($item) {
            return [
                'id' => $item->id,
                'user' => $item->user,
                'user_id' => $item->user_id,
                'user_name' => optional($item->user)->name,
                'title' => $item->title,
                'main_image' => $item->main_image,
                'category_id' => null,
                'tab_id' => 1,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'is_trashed' => method_exists($item, 'trashed') ? $item->trashed() : false,
                'type' => 'quests',  
            ];
        });

        // 全部まとめる
        $posts = $quests->concat($spots);

        $posts = match($sort) {
            'oldest' => $posts->sortBy('created_at'),
            default  => $posts->sortByDesc('created_at'), 
        };

        $paginated = new LengthAwarePaginator(
            $posts->forPage($currentPage, $perPage),
            $posts->count(),
            $perPage,
            $currentPage,
            [
                'query' => $request->query(), // ← クエリを保持！（sort=likes など）
            ]

        );

        switch ($sort) {
            case 'oldest':
                $posts = $posts->sortBy('created_at')->values();
                break;
            case 'latest':
            default:                
                $posts = $posts->sortByDesc('created_at')->values();
                break;    
        }
   
            return view('admin.posts.other_posts', [
                'posts' => $paginated,
                'sort' => $sort,
            ]);
    
        }
}
