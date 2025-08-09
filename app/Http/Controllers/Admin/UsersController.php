<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Business;

class UsersController extends Controller
{
    private $user;
    private $business;
    
    public function __construct(User $user, Business $business){
        $this->user = $user;
        $this->business = $business;
    }

    public function indexBusiness(Request $request){
        $query = User::where('role_id', '!=', 3);
        $sort = $request->input('sort', 'latest');

        if ($sort === 'latest') {
            $query->orderBy('created_at', 'desc');
        } else { // oldest or default
            $query->orderBy('created_at', 'asc');
        }

        $users = $query->where('role_id', 2)->withTrashed()->paginate(10);

        return view('admin.users.all_business_users', compact('users'));       
    } 

    public function indexApplied(Request $request){
        $sort = $request->input('sort', 'latest');   
        $query = $this->user
            ->withTrashed()
            ->where('role_id', '!=', 3)
            ->whereIn('official_certification', [2, 3]);
    
        if ($sort === 'latest') {
            $query->orderBy('updated_at', 'desc');
        } else {
            $query->orderBy('updated_at', 'asc');
        }
    
        $applied_users = $query->paginate(10);
    
        return view('admin.users.applied_users', compact('applied_users'));
    }



    public function indexTourists(Request $request){
        $query = User::where('role_id', 1);
        $sort = $request->input('sort', 'latest');

        if ($sort === 'latest') {
            $query->orderBy('created_at', 'desc');
        } else { // oldest or default
            $query->orderBy('created_at', 'asc');
        }

        $users = $query->withTrashed()->paginate(10);

        return view('admin.users.all_tourists', compact('users'));       
    } 


    public function certify(Request $request, User $user){
    $action = $request->input('action');

    switch ($action) {
        case 'approve':
            $user->official_certification = 3;
            break;
        case 'reject':
        case 'revoke':
            $user->official_certification = 1;
            break;
        default:
            abort(400, 'Invalid action.');
    }
    $user->save();

    return redirect()->back();
    }
    
    public function adminReview($id){
        $user_a = $this->user->findOrFail($id);
        return view('admin.users.foradminreview')->with('user',$user_a);

    }

    public function deactivate($id){
        $this->user->destroy($id);
        return redirect()->back();
    }

    public function activate($id){
        $this->user->onlyTrashed()->findOrFail($id)->restore();
        //restore() -- restores a soft-deleted record
        //  onlyTrashed() -- get only soft-deleted records
        return redirect()->back();
    }


}
