<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BusinessInfoCategory;

class BusinessInfoCategoryController extends Controller
{
    private $business_info_category;

    public function __construct(BusinessInfoCategory $business_info_category){
        $this->business_info_category = $business_info_category;
    }

    public function index(){
        $all_categories = $this->business_info_category->orderBy('id')->paginate(10);

        return view('admin.businesses.categories.categories_index')->with('all_categories', $all_categories);
    }

    public function store(Request $request){
        $request->validate([
            'name' =>'required|max:50|unique:business_info_category,name',
        ]);

        $this->business_info_category->name = $request->name; 
        
        $this->business_info_category->save();


        //redirect to previous page
        return redirect()->back();
    }

    public function delete($id){
        // $this->post->destroy($id);
        $this->business_info_category->findOrFail($id)->forceDelete();

        return redirect()->back();
    }

    public function update(Request $request, $id){
        $request->validate([
            'category_name'.$id => 'required|max:50|unique:business_info_category,name,'.$id
        ],[
            "category_name.$id.required" => 'The name is required.',
            "category_name.$id.max" => 'Maximum of 50 characters only.',
            "category_name.$id.unique" => 'The name already exists.'
        ]);

        $category_a = $this->business_info_category->findOrFail($id);

        $category_a->name = $request->input('category_name'.$id);

        $category_a->save();

        return redirect()->back();
    }
}


