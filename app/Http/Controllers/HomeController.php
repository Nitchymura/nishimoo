<?php

namespace App\Http\Controllers;

use App\Models\FAQ;
use App\Models\Spot;
use App\Models\User;
use App\Models\Quest;
use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class HomeController extends Controller

{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $user;
    private $business;
    private $quest;
    private $spot;
    private $faq;

    public function __construct(User $user, Business $business, Quest $quest, Spot $spot, FAQ $faq)
    {
        $this->user = $user;
        $this->business = $business;
        $this->quest = $quest;
        $this->spot = $spot;
        $this->faq = $faq;
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index(){

        $popular_quests     = Quest::with('views')->whereHas('views')->get()
                        ->sortByDesc(fn($quest) => $quest->views->sum('views') ?? 0)->take(9)->values();

        $popular_spots      = Spot::with('views')->whereHas('views')->get()
                        ->sortByDesc(fn($spot) => $spot->views->sum('views') ?? 0)->take(9)->values();

        $popular_locations  = Business::with('views')->whereHas('views')->where('category_id' ,'1')->get()
                        ->sortByDesc(fn($location) => $location->views->sum('views') ?? 0)->take(9)->values();
        
        $popular_events     = Business::with('views')->whereHas('views')->where('category_id' ,'2')->get()
                        ->sortByDesc(fn($event) => $event->views->sum('views') ?? 0)->take(9)->values();
        
        // $popular_follwings  = $popular_quests->concat($popular_spots)->concat($popular_locations)->concat($popular_events)
        //                 ->sortByDesc(fn($post) => $post->views->sum('views') ?? 0)->take(9)->values();
        
        $followedUserIds = Auth::check()
                ? Auth::user()->followings->pluck('id')->toArray()
                : [];
    
        $popular_follwings = collect()
            ->concat($popular_quests)
            ->concat($popular_spots)
            ->concat($popular_locations)
            ->concat($popular_events)
            ->filter(fn($post) => $post->user && in_array($post->user->id, $followedUserIds))
            ->sortByDesc(fn($post) => $post->views->sum('views') ?? 0)
            ->take(9)
            ->values();

        return view('home.home')
                ->with('popular_quests', $popular_quests)
                ->with('popular_spots', $popular_spots)
                ->with('popular_locations', $popular_locations)
                ->with('popular_events', $popular_events)
                ->with('popular_follwings', $popular_follwings);
    }
    

    // Search Result
    public function search(Request $request){

        $quests = Quest::where('title', 'like', "%{$request->search}%")
                        ->orWhere('introduction', 'like', "%{$request->search}%")
                        ->latest()
                        ->get();

        $spots = Spot::where('title', 'like', "%{$request->search}%")
                        ->orWhere('introduction', 'like', "%{$request->search}%")
                        ->latest()
                        ->get();

        $business_locations = Business::where('category_id', '1')
                                ->where(function ($query) use ($request) {
                                    $query->where('name', 'like', "%{$request->search}%")
                                          ->orWhere('introduction', 'like', "%{$request->search}%");
                                })
                                ->latest()
                                ->get();

        $business_events = Business::where('category_id', '2')
                                ->where(function ($query) use ($request) {
                                    $query->where('name', 'like', "%{$request->search}%")
                                          ->orWhere('introduction', 'like', "%{$request->search}%");
                                })
                                ->latest()
                                ->get();
        $business_others = Business::where('category_id', '6')
                                ->where(function ($query) use ($request) {
                                    $query->where('name', 'like', "%{$request->search}%")
                                          ->orWhere('introduction', 'like', "%{$request->search}%");
                                })
                                ->latest()
                                ->get();

        $all = $quests->concat($spots)->concat($business_locations)->concat($business_events)->concat($business_others);

        $currentPage = LengthAwarePaginator::resolveCurrentPage('all_page');
        $perPage = 9;
        $currentItems = $all->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $all_posts = new LengthAwarePaginator(
            $currentItems,
            $all->count(),
            $perPage,
            $currentPage,
            ['path' => url('/search'), 'pageName' => 'all_page']
        );

        return view('home.search')
                ->with(compact('quests', 'spots', 'business_locations', 'business_events', 'business_others','all_posts', 'request'));
    }

    public function sort(Request $request){

        $category = $request->input('data-category');
        $sort = $request->input('sort');
        $page = $request->input('page', 1);
        $pageName = $category . '_page';

        switch ($category) {
            case 'spot':
                $query = Spot::select('spots.*')
                    ->selectSub(function ($subquery) {
                        $subquery->select('views')
                            ->from('page_views')
                            ->whereColumn('page_views.page_id', 'spots.id')
                            ->where('page_views.page_type', Spot::class);
                    }, 'views_count')
                    ->where(function ($q) use ($request) {
                        $q->where('title', 'like', "%{$request->search}%")
                        ->orWhere('introduction', 'like', "%{$request->search}%");
                    });
                break;

            case 'quest':
                $query = Quest::select('quests.*')
                    ->selectSub(function ($subquery) {
                        $subquery->select('views')
                            ->from('page_views')
                            ->whereColumn('page_views.page_id', 'quests.id')
                            ->where('page_views.page_type', Quest::class);
                    }, 'views_count')
                    ->where(function ($q) use ($request) {
                        $q->where('title', 'like', "%{$request->search}%")
                        ->orWhere('introduction', 'like', "%{$request->search}%");
                    });
                break;

            case 'location':
            case 'event':
            case 'others' :
                if ($category === 'location') {
                    $category_id = 1;
                } elseif ($category === 'event') {
                    $category_id = 2;
                } elseif ($category === 'others') {
                    $category_id = 6;
                }
                $query = Business::select('businesses.*')
                    ->selectSub(function ($subquery) {
                        $subquery->select('views')
                            ->from('page_views')
                            ->whereColumn('page_views.page_id', 'businesses.id')
                            ->where('page_views.page_type', Business::class);
                    }, 'views_count')
                    ->where('category_id', $category_id)
                    ->where(function ($q) use ($request) {
                        $q->where('name', 'like', "%{$request->search}%")
                        ->orWhere('introduction', 'like', "%{$request->search}%");
                    });
                break;

            default:
                return response()->json([
                    'html' => $this->renderAllSorted($sort, $request)
                ]);
        }

        if ($sort === 'likes') {
            $query = $query->withCount('likes')->orderByDesc('likes_count');
        } elseif ($sort === 'views') {
            $query = $query->orderByDesc('views_count');
        } elseif ($sort === 'comments') {
            $query = $query->withCount('comments')->orderByDesc('comments_count');
        } elseif ($sort === 'oldest') {
            $query = $query->oldest();
        } else {
            $query = $query->latest();
        }

        $posts = $query->paginate(9, ['*'], $pageName, $page)
                    ->appends($request->query());

        return response()->json([
            'html' => view('home.search-result-body-list', compact('posts'))->render()
        ]);
    }

    private function renderAllSorted($sort, Request $request){
        
        $spots = Spot::select('spots.*')
            ->selectSub(function ($subquery) {
                $subquery->select('views')
                    ->from('page_views')
                    ->whereColumn('page_views.page_id', 'spots.id')
                    ->where('page_views.page_type', Spot::class);
            }, 'views_count')
            ->withCount(['likes', 'comments'])
            ->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                ->orWhere('introduction', 'like', "%{$request->search}%");
            })
            ->get();

        $quests = Quest::select('quests.*')
            ->selectSub(function ($subquery) {
                $subquery->select('views')
                    ->from('page_views')
                    ->whereColumn('page_views.page_id', 'quests.id')
                    ->where('page_views.page_type', Quest::class);
            }, 'views_count')
            ->withCount(['likes', 'comments'])
            ->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                ->orWhere('introduction', 'like', "%{$request->search}%");
            })
            ->get();

        $locations = Business::select('businesses.*')
            ->selectSub(function ($subquery) {
                $subquery->select('views')
                    ->from('page_views')
                    ->whereColumn('page_views.page_id', 'businesses.id')
                    ->where('page_views.page_type', Business::class);
            }, 'views_count')
            ->withCount(['likes', 'comments'])
            ->where('category_id', 1)
            ->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('introduction', 'like', "%{$request->search}%");
            })
            ->get();

        $events = Business::select('businesses.*')
            ->selectSub(function ($subquery) {
                $subquery->select('views')
                    ->from('page_views')
                    ->whereColumn('page_views.page_id', 'businesses.id')
                    ->where('page_views.page_type', Business::class);
            }, 'views_count')
            ->withCount(['likes', 'comments'])
            ->where('category_id', 2)
            ->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('introduction', 'like', "%{$request->search}%");
            })
            ->get();

        $others = Business::select('businesses.*')
            ->selectSub(function ($subquery) {
                $subquery->select('views')
                    ->from('page_views')
                    ->whereColumn('page_views.page_id', 'businesses.id')
                    ->where('page_views.page_type', Business::class);
            }, 'views_count')
            ->withCount(['likes', 'comments'])
            ->where('category_id', 6)
            ->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('introduction', 'like', "%{$request->search}%");
            })
            ->get();

        $all = $spots->concat($quests)->concat($locations)->concat($events)->concat($others);

        if ($sort === 'likes') {
            $sorted = $all->sortByDesc('likes_count');
        } elseif ($sort === 'views') {
            $sorted = $all->sortByDesc('views_count');
        } elseif ($sort === 'comments') {
            $sorted = $all->sortByDesc('comments_count');
        } elseif ($sort === 'oldest') {
            $sorted = $all->sortBy('created_at');
        } else {
            $sorted = $all->sortByDesc('created_at');
        }

        $currentPage = $request->input('all_page', 1);
        $perPage = 9;
        $currentItems = $sorted->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $posts = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $sorted->count(),
            $perPage,
            $currentPage,
            ['path' => url('/search'), 'pageName' => 'all_page']
        );

        return view('home.search-result-body-list', ['posts' => $posts])->render();
    }


//Allshow
    public function showAll(Request $request){
    $sort = $request->get('sort', 'likes_count');
    $perPage = 9;
    $currentPage = LengthAwarePaginator::resolveCurrentPage();
    // Spots
    $spots = Spot::with('user')
    ->withCount(['spotLikes as likes_count'])
    ->withCount(['spotComments as comments_count'])
    ->withSum(['pageViews as views_sum' => function ($query) {
        $query->where('page_type', 'App\\Models\\Spot');
    }], 'views')
    ->get()
    ->map(function ($item) {
        return [
            'id' => $item->id,
            'user' => $item->user,
            'user_id' => $item->user_id,
            'user_name' => optional($item->user)->name,
            'user_official_certification' => optional($item->user)->official_certification,
            'avatar' => optional($item->user)->avatar,
            'title' => $item->title,
            'introduction' => $item->introduction,
            'main_image' => $item->main_image,
            'category_id' => null,
            'tab_id' => 1,
            'official_certification' => null,
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at,
            'likes_count' => $item->likes_count, 
            'comments_count' => $item->comments_count,
            'views_sum' => $item->views_sum,
            'is_liked' => $item->isLiked(),  
            'type' => 'spots',                  
        ];
    });

    // Quests
    $quests = Quest::with('user')
    ->withCount(['questLikes as likes_count'])
    ->withCount(['questComments as comments_count'])
    ->withSum(['pageViews as views_sum' => function ($query) {
        $query->where('page_type', 'App\\Models\\Quest');
    }], 'views')
    ->get()
    ->map(function ($item) {
        return [
            'id' => $item->id,
            'user' => $item->user,
            'user_id' => $item->user_id,
            'user_name' => optional($item->user)->name,
            'user_official_certification' => optional($item->user)->official_certification,
            'avatar' => optional($item->user)->avatar,
            'title' => $item->title,
            'introduction' => $item->introduction,
            'main_image' => $item->main_image,
            'category_id' => null,
            'tab_id' => 2,
            'official_certification' => null,
            'start_date' => $item->start_date,
            'end_date' => $item->end_date,
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at,
            'likes_count' => $item->likes_count,
            'comments_count' => $item->comments_count,
            'views_sum' => $item->views_sum,
            'is_liked' => $item->isLiked(),
            'type' => 'quests',  
        ];
    });


    // Businesses -> Location
    $locations = Business::where('category_id', 1)
    ->withCount(['businessLikes as likes_count'])
    ->withCount(['businessComments as comments_count'])
    ->withSum(['pageViews as views_sum' => function ($query) {
        $query->where('page_type', 'App\\Models\\Business');
    }], 'views')
    ->with([
        'photos' => function ($q) {
            $q->orderBy('priority')->limit(1);
        },
        'user' // ← ユーザー情報も一緒に取得
    ])
    ->get()
    ->map(function ($item) {
        return [
            'id' => $item->id,
            'user' => $item->user,
            'user_id' => $item->user_id,
            'user_name' => optional($item->user)->name,
            'user_official_certification' => optional($item->user)->official_certification,
            'avatar' => optional($item->user)->avatar,
            'title' => $item->name,
            'introduction' => $item->introduction,
            'main_image' => $item->main_image,
            'category_id' => $item->category_id,
            'tab_id' => 3,
            'official_certification' => $item->official_certification,
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at,
            'likes_count' => $item->likes_count, // ← 追加
            'comments_count' => $item->comments_count,
            'views_sum' => $item->views_sum,
            'is_liked' => $item->isLiked(),     // ← 追加
            'type' => 'businesses', 
        ];
    });

    // Businesses -> Event
    $events = Business::where('category_id', 2)
    ->withCount(['businessLikes as likes_count'])
    ->withCount(['businessComments as comments_count'])
    ->withSum(['pageViews as views_sum' => function ($query) {
        $query->where('page_type', 'App\\Models\\Business');
    }], 'views')
    ->with([
        'photos' => function ($q) {
            $q->orderBy('priority')->limit(1);
        },
        'user' // ← ユーザー情報も一緒に取得
    ])
    ->get()
    ->map(function ($item) {
        return [
            'id' => $item->id,
            'user' => $item->user,
            'user_id' => $item->user_id,
            'user_name' => optional($item->user)->name,
            'user_official_certification' => optional($item->user)->official_certification,
            'avatar' => optional($item->user)->avatar,
            'title' => $item->name,
            'introduction' => $item->introduction,
            'main_image' => $item->main_image,
            'category_id' => $item->category_id,
            'tab_id' => 4,
            'official_certification' => $item->official_certification,
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at,
            'likes_count' => $item->likes_count, // ← 追加
            'comments_count' => $item->comments_count,
            'views_sum' => $item->views_sum,
            'is_liked' => $item->isLiked(),     // ← 追加
            'type' => 'businesses', 
        ];
    });

    $others = Business::where('category_id', 6)
    ->withCount(['businessLikes as likes_count'])
    ->withCount(['businessComments as comments_count'])
    ->withSum(['pageViews as views_sum' => function ($query) {
        $query->where('page_type', 'App\\Models\\Business');
    }], 'views')
    ->with([
        'photos' => function ($q) {
            $q->orderBy('priority')->limit(1);
        },
        'user' // ← ユーザー情報も一緒に取得
    ])
    ->get()
    ->map(function ($item) {
        return [
            'id' => $item->id,
            'user' => $item->user,
            'user_id' => $item->user_id,
            'user_name' => optional($item->user)->name,
            'user_official_certification' => optional($item->user)->official_certification,
            'avatar' => optional($item->user)->avatar,
            'title' => $item->name,
            'introduction' => $item->introduction,
            'main_image' => $item->main_image,
            'category_id' => $item->category_id,
            'tab_id' => 6,
            'official_certification' => $item->official_certification,
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at,
            'likes_count' => $item->likes_count, // ← 追加
            'comments_count' => $item->comments_count,
            'views_sum' => $item->views_sum,
            'is_liked' => $item->isLiked(),     // ← 追加
            'type' => 'businesses', 
        ];
    });

    // 全部まとめる
    $all = $quests->concat($spots)->concat($locations)->concat($events)->concat($others);

        // 並び替え
        $all = match($sort) {
            'oldest' => $all->sortBy('created_at'),
            'comments'  => $all->sortByDesc('comments_count'),
            'views'  => $all->sortByDesc('views_sum'),
            'likes' => $all->sortByDesc('likes_count'),
            default  => $all->sortByDesc('created_at'), 
        };
    
        $all = $all->values(); // キーをリセット（重要）
    
        // ページネーション
        $paginated = new LengthAwarePaginator(
            $all->forPage($currentPage, $perPage),
            $all->count(),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(), // ← クエリを保持！（sort=likes など）
            ]
        );

        switch ($sort) {
            case 'oldest':
                $all = $all->sortBy('created_at')->values();
                break;
            case 'latest':
            default:    
                $all = $all->sortByDesc('created_at')->values();
                break;                           
            case 'comments':
                $all = $all->sortByDesc('comments_count')->values();
                break;
            case 'views':
                $all = $all->sortByDesc('views_sum')->values();
                break;
            case 'likes':                          
                $all = $all->sortByDesc('likes_count')->values();
                break;    
        }

        return view('home.posts.all', [
            'all' => $paginated,
            'sort' => $sort, // Blade側で現在の並び順を表示するため
        ]);

    }

public function showQuests(Request $request){
    $sort = $request->get('sort', 'likes_count');
    $perPage = 6;
    $currentPage = LengthAwarePaginator::resolveCurrentPage();
    // Quests
    $quests = Quest::with('user')
    ->withCount(['questLikes as likes_count'])
    ->withCount(['questComments as comments_count'])
    ->withSum(['pageViews as views_sum' => function ($query) {
        $query->where('page_type', 'App\\Models\\Quest');
    }], 'views')
    ->get()
    ->map(function ($item) {
        return [
            'id' => $item->id,
            'user' => $item->user,
            'user_id' => $item->user_id,
            'user_name' => optional($item->user)->name,
            'user_official_certification' => optional($item->user)->official_certification,
            'avatar' => optional($item->user)->avatar,
            'title' => $item->title,
            'introduction' => $item->introduction,
            'main_image' => $item->main_image,
            'category_id' => null,
            'tab_id' => 2,
            'official_certification' => null,
            'start_date' => $item->start_date,
            'end_date' => $item->end_date,
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at,
            'likes_count' => $item->likes_count, // ← 追加
            'comments_count' => $item->comments_count,
            'views_sum' => $item->views_sum,
            'is_liked' => $item->isLiked(),     // ← 追加
            'type' => 'quests', 
        ];
    });

    $quests = match($sort) {
        'oldest' => $quests->sortBy('created_at'),
        'comments'  => $quests->sortByDesc('comments_count'),
        'views'  => $quests->sortByDesc('views_sum'),
        'likes' => $quests->sortByDesc('likes_count'),
        default  => $quests->sortByDesc('created_at'), 
    };

    $quests = $quests->values(); // キーをリセット（重要）

    // ページネーション
    $paginated = new LengthAwarePaginator(
        $quests->forPage($currentPage, $perPage),
        $quests->count(),
        $perPage,
        $currentPage,
        [
            'path' => $request->url(),
            'query' => $request->query(), // ← クエリを保持！（sort=likes など）
        ]
    );

    switch ($quests) {
        case 'oldest':
            $quests = $quests->sortBy('created_at')->values();
            break;
        case 'latest':
        default:              
            $quests = $quests->sortByDesc('created_at')->values();
            break;                           
        case 'comments':
            $quests = $quests->sortByDesc('comments_count')->values();
            break;
        case 'views':
            $quests = $quests->sortByDesc('views_sum')->values();
            break;
        case 'likes':             
            $quests = $quests->sortByDesc('likes_count')->values();
            break;  
    }

    return view('home.posts.quests', [
        'quests' => $paginated,
        'sort' => $sort, // Blade側で現在の並び順を表示するため
    ]);
}

    public function showSpots(Request $request){
        $sort = $request->get('sort', 'likes_count');
        $perPage = 6;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        // Spots
        $spots = Spot::with('user')
        ->withCount(['spotLikes as likes_count'])
        ->withCount(['spotComments as comments_count'])
        ->withSum(['pageViews as views_sum' => function ($query) {
            $query->where('page_type', 'App\\Models\\Spot');
        }], 'views')
        ->get()
        ->map(function ($item) {
            return [
                'id' => $item->id,
                'user' => $item->user,
                'user_id' => $item->user_id,
                'user_name' => optional($item->user)->name,
                'user_official_certification' => optional($item->user)->official_certification,
                'avatar' => optional($item->user)->avatar,
                'title' => $item->title,
                'introduction' => $item->introduction,
                'main_image' => $item->main_image,
                'category_id' => null,
                'tab_id' => 1,
                'official_certification' => null,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'likes_count' => $item->likes_count, 
                'comments_count' => $item->comments_count,
                'views_sum' => $item->views_sum,
                'is_liked' => $item->isLiked(), 
                'type' => 'spots', 
            ];
        });

        $spots = match($sort) {
            'oldest' => $spots->sortBy('created_at'),
            'comments'  => $spots->sortByDesc('comments_count'),
            'views'  => $spots->sortByDesc('views_sum'),
            'likes' => $spots->sortByDesc('likes_count'),
            default  => $spots->sortByDesc('created_at'), 
            };
    
        $spots = $spots->values(); // キーをリセット（重要）

            // ページネーション
        $paginated = new LengthAwarePaginator(
            $spots->forPage($currentPage, $perPage),
            $spots->count(),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(), // ← クエリを保持！（sort=likes など）
            ]
        );
    
        switch ($sort) {
            case 'oldest':
                $spots = $spots->sortBy('created_at')->values();
                break;
            case 'latest':
            default:                   
                $spots = $spots->sortByDesc('created_at')->values();
                break;                           
            case 'comments':
                $spots = $spots->sortByDesc('comments_count')->values();
                break;
            case 'views':
                $spots = $spots->sortByDesc('views_sum')->values();
                break;
            case 'likes':            
                $spots = $spots->sortByDesc('likes_count')->values();
                break;  
        }
    
        return view('home.posts.spots', [
            'spots' => $paginated,
            'sort' => $sort, // Blade側で現在の並び順を表示するため
        ]);
    }

    public function showLocations(Request $request){
        $sort = $request->get('sort', 'likes_count');
        $perPage = 6;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        // Locations
        $locations = Business::where('category_id', 1)
        ->withCount(['businessLikes as likes_count'])
        ->withCount(['businessComments as comments_count'])
        ->withSum(['pageViews as views_sum' => function ($query) {
            $query->where('page_type', 'App\\Models\\Business');
        }], 'views')
        ->with([
            'photos' => function ($q) {
                $q->orderBy('priority')->limit(1);
            },
            'user' // ← ユーザー情報も一緒に取得
        ])
        ->get()
        ->map(function ($item) {
            return [
                'id' => $item->id,
                'user' => $item->user,
                'user_id' => $item->user_id,
                'user_name' => optional($item->user)->name,
                'user_official_certification' => optional($item->user)->official_certification,
                'avatar' => optional($item->user)->avatar,
                'title' => $item->name,
                'introduction' => $item->introduction,
                'main_image' => $item->main_image,
                'category_id' => $item->category_id,
                'tab_id' => 3,
                'official_certification' => $item->official_certification,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'likes_count' => $item->likes_count, // ← 追加
                'comments_count' => $item->comments_count,
                'views_sum' => $item->views_sum,
                'is_liked' => $item->isLiked(),     // ← 追加
                'type' => 'businesses', 
            ];
        });
        
        $locations = match($sort) {
            'oldest' => $locations->sortBy('created_at'),
            'comments'  => $locations->sortByDesc('comments_count'),
            'views'  => $locations->sortByDesc('views_sum'),
            'likes' => $locations->sortByDesc('likes_count'),
            default  => $locations->sortByDesc('created_at'), 
        };
    
        $locations = $locations->values(); // キーをリセット（重要）
    
        // ページネーション
        $paginated = new LengthAwarePaginator(
            $locations->forPage($currentPage, $perPage),
            $locations->count(),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(), // ← クエリを保持！（sort=likes など）
            ]
        );

        switch ($sort) {
            case 'oldest':
                $locations = $locations->sortBy('created_at')->values();
                break;
            case 'latest':
            default:                 
                $locations = $locations->sortByDesc('created_at')->values();
                break;                           
            case 'comments':
                $locations = $locations->sortByDesc('comments_count')->values();
                break;
            case 'views':
                $locations = $locations->sortByDesc('views_sum')->values();
                break;
            case 'likes':
               
                $locations = $locations->sortByDesc('likes_count')->values();
                break;  
        }

        return view('home.posts.locations', [
            'locations' => $paginated,
            'sort' => $sort, // Blade側で現在の並び順を表示するため
        ]);
    }

    public function showEvents(Request $request){
        $sort = $request->get('sort', 'likes_count');
        $perPage = 6;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        // Locations
        $events = Business::where('category_id', 2)
        ->withCount(['businessLikes as likes_count'])
        ->withCount(['businessComments as comments_count'])
        ->withSum(['pageViews as views_sum' => function ($query) {
            $query->where('page_type', 'App\\Models\\Business');
        }], 'views')
        ->with([
            'photos' => function ($q) {
                $q->orderBy('priority')->limit(1);
            },
            'user' // ← ユーザー情報も一緒に取得
        ])
        ->get()
        ->map(function ($item) {
            return [
                'id' => $item->id,
                'user' => $item->user,
                'user_id' => $item->user_id,
                'user_name' => optional($item->user)->name,
                'user_official_certification' => optional($item->user)->official_certification,
                'avatar' => optional($item->user)->avatar,
                'title' => $item->name,
                'introduction' => $item->introduction,
                'main_image' => $item->main_image,
                'category_id' => $item->category_id,
                'tab_id' => 4,
                'official_certification' => $item->official_certification,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'likes_count' => $item->likes_count, // ← 追加
                'comments_count' => $item->comments_count,
                'views_sum' => $item->views_sum,
                'is_liked' => $item->isLiked(),     // ← 追加
                'type' => 'businesses', 
            ];
        });

        $events = match($sort) {
            'oldest' => $events->sortBy('created_at'),
            'comments'  => $events->sortByDesc('comments_count'),
            'views'  => $events->sortByDesc('views_sum'),
            'likes' => $events->sortByDesc('likes_count'),
            default  => $events->sortByDesc('created_at'), 
        };
    
        $events = $events->values(); // キーをリセット（重要）

        // ページネーション
        $paginated = new LengthAwarePaginator(
            $events->forPage($currentPage, $perPage),
            $events->count(),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(), // ← クエリを保持！（sort=likes など）
            ]
        );
    
        switch ($sort) {
            case 'oldest':
                $events = $events->sortBy('created_at')->values();
                break;
            case 'latest':
            default:                 
                $events = $events->sortByDesc('created_at')->values();
                break;                           
            case 'comments':
                $events = $events->sortByDesc('comments_count')->values();
                break;
            case 'views':
                $events = $events->sortByDesc('views_sum')->values();
                break;
            case 'likes':               
                $events = $events->sortByDesc('likes_count')->values();
                break;  
        }
    
        return view('home.posts.events', [
            'events' => $paginated,
            'sort' => $sort, // Blade側で現在の並び順を表示するため
        ]);
    }

    public function showOthers(Request $request){
        $sort = $request->get('sort', 'likes_count');
        $perPage = 6;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        // Locations
        $others = Business::where('category_id', 6)
        ->withCount(['businessLikes as likes_count'])
        ->withCount(['businessComments as comments_count'])
        ->withSum(['pageViews as views_sum' => function ($query) {
            $query->where('page_type', 'App\\Models\\Business');
        }], 'views')
        ->with([
            'photos' => function ($q) {
                $q->orderBy('priority')->limit(1);
            },
            'user' // ← ユーザー情報も一緒に取得
        ])
        ->get()
        ->map(function ($item) {
            return [
                'id' => $item->id,
                'user' => $item->user,
                'user_id' => $item->user_id,
                'user_name' => optional($item->user)->name,
                'user_official_certification' => optional($item->user)->official_certification,
                'avatar' => optional($item->user)->avatar,
                'title' => $item->name,
                'introduction' => $item->introduction,
                'main_image' => $item->main_image,
                'category_id' => $item->category_id,
                'tab_id' => 6,
                'official_certification' => $item->official_certification,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'likes_count' => $item->likes_count, // ← 追加
                'comments_count' => $item->comments_count,
                'views_sum' => $item->views_sum,
                'is_liked' => $item->isLiked(),     // ← 追加
                'type' => 'businesses', 
            ];
        });

        $others = match($sort) {
            'oldest' => $others->sortBy('created_at'),
            'comments'  => $others->sortByDesc('comments_count'),
            'views'  => $others->sortByDesc('views_sum'),
            'likes' => $others->sortByDesc('likes_count'),
            default  => $others->sortByDesc('created_at'), 
        };
    
        $others = $others->values(); // キーをリセット（重要）

        // ページネーション
        $paginated = new LengthAwarePaginator(
            $others->forPage($currentPage, $perPage),
            $others->count(),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(), // ← クエリを保持！（sort=likes など）
            ]
        );
    
        switch ($sort) {
            case 'oldest':
                $others = $others->sortBy('created_at')->values();
                break;
            case 'latest':
            default:                 
                $others = $others->sortByDesc('created_at')->values();
                break;                           
            case 'comments':
                $others = $others->sortByDesc('comments_count')->values();
                break;
            case 'views':
                $others = $others->sortByDesc('views_sum')->values();
                break;
            case 'likes':               
                $others = $others->sortByDesc('likes_count')->values();
                break;  
        }
    
        return view('home.posts.others', [
            'others' => $paginated,
            'sort' => $sort, // Blade側で現在の並び順を表示するため
        ]);
    }

    public function showFAQ(){

        $faqs = $this->faq->orderBy('id')->get();
        
        return view('home.faq')->with('faqs', $faqs);
    }
}
