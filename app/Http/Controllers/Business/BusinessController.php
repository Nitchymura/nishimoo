<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Business;
use App\Models\BusinessDetail;
use App\Models\BusinessComment;
use App\Models\BusinessInfoCategory;
use App\Models\BusinessHour;
use App\Models\Photo;
use App\Models\Quest;
use App\Models\BusinessPromotion;
use App\Http\Controllers\Business\PhotoController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BusinessController extends Controller
{
    private $user;
    private $business;
    private $business_promotion;
    private $business_hour;
    private $photo;
    private $business_detail;
    private $business_info_category;
    private $quest;

    public function __construct(Photo $photo, Business $business, User $user, 
        BusinessPromotion $business_promotion,BusinessDetail $business_detail, BusinessHour $business_hour, BusinessInfoCategory $business_info_category, Quest $quest)
    {
        $this->user = $user;
        $this->photo = $photo;
        $this->business = $business;
        $this->business_promotion = $business_promotion;
        $this->business_detail = $business_detail;
        $this->business_hour = $business_hour;
        $this->business_info_category = $business_info_category;
        $this->quest = $quest;
    }

    public function create(){
        $all_businesses = $this->business->where('user_id', Auth::user()->id)->latest()->get();
        $categories = BusinessInfoCategory::with('businessInfos')->get();
        
        return view('businessusers.posts.businesses.add')
            ->with('all_businesses', $all_businesses)
            ->with('categories', $categories);
    }

// public function store(Request $request){
//     $request->validate([
//         'main_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', 
//         'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'       
//     ]);

//     $this->business->category_id = $request->category_id;
//     $this->business->user_id = Auth::user()->id;
//     $this->business->name = $request->name;
//     $this->business->term_start = $request->term_start;
//     $this->business->term_end = $request->term_end;
//     $this->business->introduction = $request->introduction;

//     // main_imageをBase64エンコードして保存
//     if ($request->hasFile('main_image')) {
//         $mainImage = $request->file('main_image');
//         $fileContent = file_get_contents($mainImage->getRealPath());
//         $mimeType = $mainImage->getMimeType();
//         $this->business->main_image = 'data:' . $mimeType . ';base64,' . base64_encode($fileContent);
//     }

//     $current_cert = $this->business->official_certification;

//     $this->business->save();

//     // PhotoController の store を呼び出して写真をBase64で保存
//     $photoController = new PhotoController();
//     if ($request->hasFile('photos')) {
//         $photoController->store($request, $this->business->id); // PhotoControllerのstoreを呼び出し
//     }

//     return redirect()->route('profile.header', Auth::id());
// }


    public function store(Request $request){
        $request->validate([
            'main_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', 
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'       
        ]);

        $this->business->category_id = $request->category_id;
        $this->business->user_id = Auth::user()->id;
        $this->business->name = $request->name;
        $this->business->term_start = $request->term_start;
        $this->business->term_end = $request->term_end;
        $this->business->introduction = $request->introduction;
        
        $this->business->main_image = "data:image/".$request->main_image->extension().";base64,".base64_encode (file_get_contents($request->main_image)); 

        $current_cert = $this->business->official_certification;

        $this->business->save();

        // PhotoController の store を呼び出して写真を保存
        if ($request->hasFile('photos')) {
    foreach ($request->file('photos') as $i => $photo) {
        if ($photo) {
            // 画像ファイルの内容を読み込む
            $fileContent = file_get_contents($photo->getRealPath());

            // MIMEタイプを取得（例：image/jpeg）
            $mimeType = $photo->getMimeType();

            // Base64エンコードしてデータURLを作成
            $base64Image = 'data:' . $mimeType . ';base64,' . base64_encode($fileContent);

            // 優先度の設定
            $priority = $request->input("priorities.$i") ?? ($i + 1);

            // データベースに保存
            Photo::create([
                'business_id' => $this->business->id,
                'image' => $base64Image,  // Base64形式で保存
                'priority' => $priority,
            ]);
        }
    }
}
    
        return redirect()->route('profile.header', Auth::id());
    }

    public function edit($id){
        $business_a = $this->business->with('photos')->findOrFail($id);
        $businessHours = $business_a->businessHours->keyBy('day_of_week');
        $businessDetail = $business_a->businessDetails;
        $categories = BusinessInfoCategory::with('businessInfos')->get();
        
        return view('businessusers.posts.businesses.edit')
            ->with('business', $business_a)
            ->with('businessHours', $businessHours)
            ->with('categories', $categories);
    }

    public function update(Request $request, $id){
        $request->validate([
            'main_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', 
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',        
            'introduction' => 'max:1000',
        ]);

        $business_a = $this->business->findOrFail($id);

        $business_a->category_id = $request->category_id;
        $business_a->user_id = Auth::user()->id;
        $business_a->name = $request->name;

        
    if($request->main_image){
        $business_a->main_image = "data:image/".$request->main_image->extension().";base64,".base64_encode(file_get_contents($request->main_image));
    }

        $business_a->term_start = $request->term_start;
        $business_a->term_end = $request->term_end;
        $business_a->introduction = $request->introduction;

        $current_cert = $business_a->official_certification;

        if ($current_cert == 3) {
            if ($request->has('official_certification')) {
                // チェックあり → 特別な認定を外して普通の認定に戻す
                $business_a->official_certification = 2;
            } else {
                // チェックなし → 認定全部外す
                $business_a->official_certification = 1;
            }
        } else {
            if ($request->has('official_certification')) {
                $business_a->official_certification = 2;
            } else {
                $business_a->official_certification = 1;
            }
        }

        $business_a->save();        
        
        $photoController = app(PhotoController::class);
        $photoController->update($request, $business_a);

        
    return redirect()->route('profile.header', $business_a->user_id);
    }

    public function deleteMainImage(Request $request)
    {
        $business_a = Business::where('user_id', Auth::id())->firstOrFail();
    
        if ($business_a->main_image) {
            $business_a->main_image = null;
            $business_a->save();
    
            return response()->json(['message' => 'Main Image deleted'], 200);
        }
    
        return response()->json(['message' => 'No Main Image found'], 404);
    }

    public function show($id)
    {
        try {
            $business = $this->business->findOrFail($id);
            // $quest = $this->quest->findOrFail($id);
            $business_promotion = BusinessPromotion::where('business_id', $id)->get();
            $business_hour = $this->business_hour->where('business_id', $id)->get();
            $business_photos = $this->photo->where('business_id', $id)->orderBy('priority')->get(); 
            $business_info_category = BusinessInfoCategory::with(['businessInfos' => function($query) use ($id) {
                $query->with(['businessDetails' => function($query) use ($id) {
                    $query->where('business_id', $id);
                }]);
            }])->get();
            $business_comments = BusinessComment::with(['user', 'BusinessCommentlikes'])
            ->where('business_id', $id)
            ->latest() // created_at の新しい順
            ->paginate(5); // 5件ずつ

            return view('businessusers.posts.businesses.show')
                    ->with('business', $business)
                    ->with('business_hour', $business_hour)
                    ->with('business_comments', $business_comments)
                    ->with('business_info_category', $business_info_category)
                    ->with('business_promotions', $business_promotion)
                    ->with('business_photos', $business_photos);
                    // ->with('quests', $quest);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('business.show', $id)->with('error', 'ビジネス情報が見つかりませんでした。');
        }
    }

    public function deactivate($id){
        $this->business->destroy($id);
        return redirect()->back();
    }

    public function activate($id){
        $this->business->onlyTrashed()->findOrFail($id)->restore();
        return redirect()->back();
    }

}
