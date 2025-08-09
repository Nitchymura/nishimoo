<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Business;
use App\Models\BusinessPromotion;
use Illuminate\Support\Str;

class BusinessPromotionController extends Controller
{
    private $business_promotion;
    private $business;
    private $user;

    public function __construct(BusinessPromotion $business_promotion, Business $business, User $user){
        $this->business_promotion = $business_promotion;
        $this->business = $business;
        $this->user = $user;
    }

    public function create(){
        $all_businesses = $this->business->where('user_id', Auth::user()->id)->latest()->get();
        return view('businessusers.posts.promotions.create')->with('all_businesses',$all_businesses);
    }

    public function store(Request $request){
        //validation
        $request->validate([
            'title' => 'required',
            'business_id' => 'required',
            'introduction' => 'required|max:2000',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:1024',
        ]);

        $this->business_promotion->title = $request->title;
        $this->business_promotion->introduction = $request->introduction;
        $this->business_promotion->promotion_start = $request->promotion_start;
        $this->business_promotion->promotion_end = $request->promotion_end;
        $this->business_promotion->display_start = $request->display_start;
        $this->business_promotion->display_end = $request->display_end;
        $this->business_promotion->business_id = $request->business_id;
        $this->business_promotion->user_id = Auth::user()->id;
        $this->business_promotion->image = "data:photo/".$request->image->extension().";base64,".base64_encode (file_get_contents($request->image)); 
        
        // 画像をストレージに保存
        // $imagePath = $request->file('image')->store('images/promotions', 'public');
        // $this->business_promotion->image = '/' . $imagePath;

        $this->business_promotion->save();

        return redirect()->route('profile.header', ['id' => $this->business_promotion->user_id, 'tab' => 'promotions']);
    }

    public function edit($id){
        $business_promotion_a = $this->business_promotion->findOrFail($id);
        $all_businesses = $this->business->where('user_id', Auth::user()->id)->latest()->get();
        return view('businessusers.posts.promotions.edit')->with('business_promotion', $business_promotion_a)->with('all_businesses', $all_businesses);
    }

    public function update(Request $request, $business_promotion_id){
        $request->validate([
            'title' => 'required',
            // 'business_id' => 'required',
            'introduction' => 'required|max:2000',
            'image' => 'max:1048|mimes:jpeg,jpg,png,gif',
        ]);


        $business_promotion_a = $this->business_promotion->findOrFail($business_promotion_id);
        $business_promotion_a->title = $request->title;
        $business_promotion_a->introduction = $request->introduction;
        $business_promotion_a->promotion_start = $request->promotion_start;
        $business_promotion_a->promotion_end = $request->promotion_end;
        $business_promotion_a->display_start = $request->display_start;
        $business_promotion_a->display_end = $request->display_end;
        // $business_promotion_a->business_id = $request->business_id;
        // $business_promotion_a->user_id = Auth::user()->id;

        if($request->image){
            $business_promotion_a->image = "data:image/".$request->image->extension().";base64,".base64_encode(file_get_contents($request->image));
        }

        // if($request->hasFile('image')){
        //     // 既存の画像があれば削除
        //     if ($business_promotion_a->image && !Str::startsWith($business_promotion_a->image, 'data:')) {
        //         $oldPath = ltrim($business_promotion_a->image, '/');
        //         Storage::disk('public')->delete($oldPath);
        //     }
            
        //     // 新しい画像を保存
        //     $imagePath = $request->file('image')->store('images/promotions', 'public');
        //     $business_promotion_a->image = '/' . $imagePath;
        // }
        $business_promotion_a->save();

        //redirect to Show Post
        return redirect()->route('profile.header', ['id' => $business_promotion_a->user_id, 'tab' => 'promotions']);
    }

    public function show($id){
        //get the data of 1 post where ID = $id
        $business_promotion_a = $this->business_promotion->findOrFail($id);
        
        return view('businessusers.posts.promotions.show')->with('business_promotion', $business_promotion_a);
    }

    public function deactivate($id){
        $this->business_promotion->destroy($id);
        return redirect()->back();
    }

    public function activate($id){
        $this->business_promotion->onlyTrashed()->findOrFail($id)->restore();
        return redirect()->back();
    }

    public function delete($id){
        // $this->post->destroy($id);
        $this->business_promotion->findOrFail($id)->forceDelete();

        return redirect()->route('profile.header',Auth::user()->id);
    }
}
