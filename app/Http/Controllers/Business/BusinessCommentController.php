<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Business;
use App\Models\BusinessComment;



class BusinessCommentController extends Controller
{
    private $user;
    private $business;
    private $business_comment;

    public function __construct(BusinessComment $business_comment, Business $business, User $user){
        $this->business_comment = $business_comment;
        $this->business = $business;
        $this->user = $user;

    }

    public function showAllComments($id){
        $business = $this->business->findOrFail($id);
        $comments = $this->business_comment->where('business_id', $id)->get();
        return view('businessusers.posts.businesses.comments.show')
            ->with('business', $business)
            ->with('comments', $comments);
    }


    public function addComment(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'business_id' => 'required|exists:businesses,id'
        ]);
        
        $this->business_comment->create([
            'user_id' => Auth::id(),
            'business_id' => $request->business_id,
            'content' => $request->content,
            'rating' => $request->rating ?? 0,
        ]);
        
        return redirect()->back()->with('success');
    }
    public function showAllReviews(Request $request, $id)
    {
        $user_a = $this->user->findOrFail($id);
    
        $query = BusinessComment::with('userRelation', 'businessRelation');
    
        // ★ 自分のBusinessのレビューだけ取得
        $query->whereHas('businessRelation.user', function ($q) {
            $q->where('id', Auth::id());
        });
    
        if ($request->filled('business_id')) {
            $query->where('business_id', $request->business_id);
        }
    
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
    
        if ($request->filled('min_rating')) {
            $query->where('rating', '>=', $request->min_rating);
        }
    
        if ($request->filled('sort_date')) {
            $query->orderBy('created_at', $request->sort_date === 'oldest' ? 'asc' : 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }
    
        $business_comments = $query->paginate(11); // ← simplePaginateでもOK
    
        // 表示されているレビューに登場するユーザー・スポット一覧
        $from_users = $business_comments->pluck('userRelation')->unique('id');
        $from_businesses = $business_comments->pluck('businessRelation')->unique('id');
    
        return view('businessusers.profiles.reviews.allreviews', compact('business_comments', 'from_businesses', 'from_users'))
            ->with('user', $user_a);
    }
    

    public function showReview($id)
    {
        $business_comment_a = $this->business_comment->findOrFail($id);
        // $user_a = $this->user->findOrFail($id);
        return view('businessusers.profiles.reviews.showreview')->with('business_comment', $business_comment_a);
    }

    // public function show(Request $request, $id)
    // {
    //     $query = BusinessComment::query();
    //     $user_a = $this->user->findOrFail($id);

    //     if ($request->filled('business_id')) {
    //         $query->where('business_id', $request->business_id);
    //     }

    //     $business_comments = $query->with('businessRelation', 'userRelation')->latest()->paginate(10);
    //     $businesses = Business::all(); // Spot一覧取得

    //     return view('businessusers.profiles.reviews.indexreview', compact('business_comments', 'businesses'))->with('user', $user_a);
    // }

    public function showIndex(Request $request)
    {
        $query = BusinessComment::with('userRelation', 'businessRelation');

        if ($request->filled('business_id')) {
            $query->where('business_id', $request->business_id);
        }  
        
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

            // ★ ここで絞り込み（4以上など）
        if ($request->filled('min_rating')) {
            $query->where('rating', '>=', $request->min_rating);
        }

        if ($request->filled('sort_date')) {
            if ($request->sort_date === 'latest') {
                $query->orderBy('created_at', 'desc');
            } elseif ($request->sort_date === 'oldest') {
                $query->orderBy('created_at', 'asc');
            }
        } else {
            // デフォルトは新しい順
            $query->orderBy('created_at', 'desc');
        }
    
        $business_comments = $query->latest()->paginate(11);
        // 表示されているレビューに登場するユーザー一覧（重複なし）
        $from_users = BusinessComment::whereIn('id', $business_comments->pluck('id'))
            ->with('userRelation')
            ->get()
            ->pluck('userRelation')
            ->unique('id');
        $from_businesses = BusinessComment::whereIn('id', $business_comments->pluck('id'))
            ->with('businessRelation')
            ->get()
            ->pluck('businessRelation')
            ->unique('id');

        return view('businessusers.profiles.reviews.indexreview', compact('business_comments', 'from_users', 'from_businesses'));
    }

    public function deleteReview($id){
        $this->business_comment->destroy($id);
        return redirect()->back();
    }

    public function deactivateBusinessComment($id)
    {
        $this->business_comment->destroy($id);
        return redirect()->back();
    }

    public function activateBusinessComment($id)
    {
        $this->business_comment->onlyTrashed()->findOrFail($id)->restore();
        //restore() -- restores a soft-deleted record
        //  onlyTrashed() -- get only soft-deleted records
        return redirect()->back();
    }

    public function store(Request $request, $business_id)
    {
        $request->validate([
            'content' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        BusinessComment::create([
            'business_id' => $business_id,
            'user_id' => auth()->id(),
            'content' => $request->content,
            'rating' => $request->rating,
        ]);

        return redirect()->back();
    }


}




