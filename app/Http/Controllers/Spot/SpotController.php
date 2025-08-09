<?php

namespace App\Http\Controllers\Spot;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


use App\Models\Spot;
use App\Models\User;

class SpotController extends Controller
{
    private $spot;
    private $user;

    public function __construct(Spot $spot, User $user)
    {
        $this->spot = $spot;
        $this->user = $user;
    }

    public function show($id){
        $spot = $this->spot->findOrFail($id);
        $user = $this->user->findOrFail($spot->user_id);

        // メイン画像URL化
        // $spot->main_image = Storage::url($spot->main_image);

        // // 追加：images（複数）のURL化
        // $imagePaths = json_decode($spot->images, true) ?? [];
        // $spot->images = array_map(function ($path) {
        //     return Storage::url($path);
        // }, $imagePaths);

        return view('spots.show')
            ->with('spot', $spot)
            ->with('user', $user);
    }

    public function create(){
        $spot = new Spot();
        $editData = session('spot_edit_data', []);

        foreach (['title', 'introduction', 'geo_location', 'geo_lat', 'geo_lng'] as $key) {
            if (isset($editData[$key])) {
                $spot->$key = $editData[$key];
            }
        }

        if (!empty($editData['main_image_path'])) {
            $spot->main_image = $editData['main_image_path'];
        }

        if (!empty($editData['existing_images']) || !empty($editData['image_paths'])) {
            $spot->images = json_encode(array_merge(
                $editData['existing_images'] ?? [],
                $editData['image_paths'] ?? []
            ));
        }

        return view('spots.create')->with('spot', $spot);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'introduction' => 'required|string',
            'main_image' => 'required|image|mimes:jpeg,jpg,png,gif|max:1048',
            'images.*' => 'image|mimes:jpeg,jpg,png,gif|max:1048',
            'images' => 'array|max:6',
        ]);
    
        // ✅ メイン画像（Base64エンコード）
        $mainImageFile = $request->file('main_image');
        $mainImageContent = file_get_contents($mainImageFile->getRealPath());
        $mainImageEncoded = 'data:image/' . $mainImageFile->getClientOriginalExtension() . ';base64,' . base64_encode($mainImageContent);
    
        // ✅ その他画像（Base64エンコード）
        $imageBase64s = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $content = file_get_contents($image->getRealPath());
                $imageBase64s[] = 'data:image/' . $image->getClientOriginalExtension() . ';base64,' . base64_encode($content);
            }
        }
    
        // ✅ Spot作成
        $spot = new Spot();
        $spot->user_id = Auth::id();
        $spot->title = $request->title;
        $spot->introduction = $request->introduction;
        $spot->main_image = $mainImageEncoded;
        $spot->geo_location = $request->geo_location;
        $spot->geo_lat = $request->geo_lat;
        $spot->geo_lng = $request->geo_lng;
        $spot->images = json_encode($imageBase64s);
        $spot->save();
    
        return redirect()->route('spot.show', $spot->id);
    }
    

    public function showEdit($id){    
        $spot = $this->spot->findOrFail($id);
        $user = $this->user->findOrFail($spot->user_id);

        if ($spot->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
    
        $editData = session('spot_edit_data', []);
    
        // セッションの値で上書き（ある場合のみ）
        foreach (['title', 'introduction', 'geo_location', 'geo_lat', 'geo_lng'] as $key) {
            if (isset($editData[$key])) {
                $spot->$key = $editData[$key];
            }
        }
    
        if (!empty($editData['main_image_path'])) {
            $spot->main_image = $editData['main_image_path'];
        }
    
        if (!empty($editData['existing_images']) || !empty($editData['image_paths'])) {
            $spot->images = json_encode(array_merge(
                $editData['existing_images'] ?? [],
                $editData['image_paths'] ?? []
            ));
        }
    
        return view('spots.edit')
            ->with('spot', $spot)
            ->with('user', $user);
    }
    

    public function update(Request $request, $id)
    {
        $spot = $this->spot->findOrFail($id);
    
        if ($request->input('from_confirm') === 'true') {
            $data = session('spot_edit_data', []);
    
            $spot->title = $data['title'] ?? $spot->title;
            $spot->introduction = $data['introduction'] ?? $spot->introduction;
            $spot->geo_lat = $data['geo_lat'] ?? $spot->geo_lat;
            $spot->geo_lng = $data['geo_lng'] ?? $spot->geo_lng;
            $spot->geo_location = $data['geo_location'] ?? $spot->geo_location;
    
            if (!empty($data['main_image_path']) && str_starts_with($data['main_image_path'], 'data:image/')) {
                $spot->main_image = $data['main_image_path'];
            }
    
            $existingImages = $data['existing_images'] ?? json_decode($spot->images, true) ?? [];
            $newImages = $data['image_paths'] ?? [];
            $spot->images = json_encode(array_merge($existingImages, $newImages));
    
            $spot->save();
    
            session()->forget('spot_edit_data');
            session()->forget('spot_id');
    
            return redirect()->route('spot.show', $spot->id);
        }
    
        $request->validate([
            'title' => 'required',
            'introduction' => 'required',
            'main_image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:1048',
            'images.*' => 'image|mimes:jpeg,jpg,png,gif|max:1048',
            'images' => 'array|max:6',
        ]);
    
        if ($request->hasFile('main_image')) {
            $file = $request->file('main_image');
            $content = file_get_contents($file->getRealPath());
            $spot->main_image = 'data:image/' . $file->getClientOriginalExtension() . ';base64,' . base64_encode($content);
        }
    
        $newImageBase64s = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $content = file_get_contents($image->getRealPath());
                $newImageBase64s[] = 'data:image/' . $image->getClientOriginalExtension() . ';base64,' . base64_encode($content);
            }
        }
    
        $existingImages = $request->input('existing_images', []);
        $allImages = array_merge($existingImages, $newImageBase64s);
    
        $spot->title = $request->title;
        $spot->introduction = $request->introduction;
        $spot->geo_location = $request->filled('geo_location') ? $request->geo_location : $spot->geo_location;
        $spot->geo_lat = $request->filled('geo_lat') ? $request->geo_lat : $spot->geo_lat;
        $spot->geo_lng = $request->filled('geo_lng') ? $request->geo_lng : $spot->geo_lng;
        $spot->images = json_encode($allImages);
        $spot->save();
    
        return redirect()->route('profile.header', $spot->user_id);
    }
    

    public function deactivate($id){
        $this->spot->destroy($id);
        return redirect()->back();
    }

    public function activate($id){
        $this->spot->onlyTrashed()->findOrFail($id)->restore();
        return redirect()->back();
    }

}

