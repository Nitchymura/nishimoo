<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BusinessLike;
use App\Models\User;
use App\Models\Business;

class BusinessLikeController extends Controller
{
    private $business_like;
    private $user;
    private $business;

    public function __construct(BusinessLike $business_like, Business $business, User $user){
        $this->business_like = $business_like;
        $this->business = $business;
        $this->user = $user;
    }

    
    public function storeBusinessLike($business_id){
        $this->business_like->user_id = Auth::user()->id;
        $this->business_like->business_id = $business_id; //post we are liking
        $this->business_like->save();

        //go to previous page
        return redirect()->back();
    }
    
    public function deleteBusinessLike($business_id){
        //delete()
        $this->business_like->where('user_id', Auth::user()->id)
                    ->where('business_id', $business_id)
                    ->delete();

        return redirect()->back();
    }

    public function storeLocationLike($business_id){
        $this->business_like->user_id = Auth::user()->id;
        $this->business_like->business_id = $business_id; //post we are liking
        $this->business_like->save();

        //go to previous page
        return redirect()->back();
    }
    
    public function deleteLocationLike($business_id){
        //delete()
        $this->business_like->where('user_id', Auth::user()->id)
                    ->where('business_id', $business_id)
                    ->delete();

        return redirect()->back();
    }

    public function storeEventLike($business_id){
        $this->business_like->user_id = Auth::user()->id;
        $this->business_like->business_id = $business_id; //post we are liking
        $this->business_like->save();

        //go to previous page
        return redirect()->back();
    }

    public function deleteEventLike($business_id){
        //delete()
        $this->business_like->where('user_id', Auth::user()->id)
                    ->where('business_id', $business_id)
                    ->delete();

        return redirect()->back();
    }
}

