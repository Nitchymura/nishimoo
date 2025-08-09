<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Business;
use App\Models\User;

class BusinessesController extends Controller
{
    private $business;
    private $user;
    
    public function __construct(Business $business, User $user){
        $this->business = $business;
        $this->user = $user;
    }

    public function index(Request $request){
        $query = Business::query();
        $sort = $request->input('sort', 'latest');

    // 並び替え（updated_atがあればそれ、なければcreated_at）
    if ($sort === 'latest') {
        $query->orderByRaw('COALESCE(updated_at, created_at) DESC');
    } else {
        $query->orderByRaw('COALESCE(updated_at, created_at) ASC');
    }

    $posts = $query->withTrashed()->paginate(10);

        return view('admin.posts.all_business_posts', compact('posts'));       
    } 

    public function indexApplied(Request $request){
        $sort = $request->input('sort', 'latest');
    
        $query = $this->business
            ->withTrashed()
            ->whereIn('official_certification', [2, 3]);
    
        if ($sort === 'latest') {
            $query->orderBy('updated_at', 'desc');
        } else {
            $query->orderBy('updated_at', 'asc');
        }
    
        $applied_posts = $query->paginate(10);
    
        return view('admin.posts.applied_posts',compact('applied_posts'));
    }

    public function certify(Request $request, $id){
        $business = Business::findOrFail($id); // ←これで確実に取得   
        $action = $request->input('action');
    
        switch ($action) {
            case 'approve':
                $business->official_certification = 3;
                break;
            case 'reject':
            case 'revoke':
                $business->official_certification = 1;
                break;
            default:
                abort(400, 'Invalid action.');
        }
    
        $business->save();
    
        return redirect()->back();
    }
    
    public function deactivate($id){
        $this->business->destroy($id);
        return redirect()->back();
    }

    public function activate($id){
        $this->business->onlyTrashed()->findOrFail($id)->restore();
        //restore() -- restores a soft-deleted record
        //  onlyTrashed() -- get only soft-deleted records
        return redirect()->back();
    }
}
