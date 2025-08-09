<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BusinessInfo;
use App\Models\BusinessInfoCategory;

class BusinessInfoController extends Controller
{
    private $business_info;
    private $business_info_category;

    public function __construct(BusinessInfo $business_info, BusinessInfoCategory $business_info_category){
        $this->business_info = $business_info;
        $this->business_info_category = $business_info_category;
    }

    public function index(){
        $all_info = BusinessInfo::with('category')
            ->orderBy('business_info_category_id')
            ->orderBy('id')
            ->paginate(10);
        // $all_info = $this->business_info->orderBy('id')->paginate(10);
        $categories = $this->business_info_category->all();
        // $categories = BusinessInfoCategory::all();

        return view('admin.businesses.info.info_index', compact('all_info', 'categories'));
    }

    public function store(Request $request){
        $request->validate([
            'name' =>'required|max:50|unique:business_info,name',
        ]);

        $this->business_info->name = $request->name; 
        $this->business_info->business_info_category_id = $request->business_info_category_id;        
        $this->business_info->save();


        //redirect to previous page
        return redirect()->back();
    }

    public function delete($id){
        // $this->post->destroy($id);
        $this->business_info->findOrFail($id)->forceDelete();

        return redirect()->back();
    }

    public function update(Request $request, $id){
        $request->validate([
            'info_name'.$id => 'required|max:50|unique:business_info,name,'.$id
        ],[
            "info_name.$id.required" => 'The name is required.',
            "info_name.$id.max" => 'Maximum of 50 characters only.',
            "info_name.$id.unique" => 'The name already exists.',
            'business_info_category_id' => 'required|exists:business_info_categories,id',
        ]);

        $info_a = $this->business_info->findOrFail($id);

        $info_a->name = $request->input('info_name'.$id);
        $info_a->business_info_category_id = $request->input('business_info_category_id');

        $info_a->save();

        return redirect()->back();
    }

}
