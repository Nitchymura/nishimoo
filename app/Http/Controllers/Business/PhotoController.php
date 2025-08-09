<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Photo;
use App\Models\Business;
use Illuminate\Support\Str;

class PhotoController extends Controller
{
    private $photo;
    private $business;

    public function __construct(Photo $photo, Business $business){
        $this->photo = $photo;
        $this->business = $business;
    }

    public function store(Request $request, $business_id){
        // Validation...
        $request->validate([
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $i => $photo) {
                if ($photo && $photo->isValid()) {
                    // ファイルの中身を読み込む
                    $fileContent = file_get_contents($photo->getRealPath());
        
                    // MIMEタイプを取得（例：image/jpeg）
                    $mimeType = $photo->getMimeType();
        
                    // Base64にエンコードしてデータURLを作成
                    $base64Image = 'data:' . $mimeType . ';base64,' . base64_encode($fileContent);
        
                    // 優先度の設定
                    $priority = $request->input("priorities.$i") ?? ($i + 1);
        
                    // データベースに保存
                    Photo::create([
                        'business_id' => $business_id,
                        'image' => $base64Image, // ← ここがBase64データ
                        'priority' => $priority,
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Photos uploaded successfully');
    }

    // update メソッドの実装
    public function update(Request $request, $business)
    {


        $deletePhotos = $request->input('delete_photos', []);
    
        $deletedPhotoIds = []; // ← 削除したIDを記録する
    
        // ① まず削除対象を処理
        foreach ($deletePhotos as $i => $shouldDelete) {
            $existingPhotoId = $request->input("existing_photos.$i");
    
            if ($shouldDelete == '1' && $existingPhotoId) {
                $photoRecord = Photo::find($existingPhotoId);
                if ($photoRecord) {
                    $photoRecord->delete();
                    $deletedPhotoIds[] = $existingPhotoId; // 削除記録に追加
                }
            }
        }
    
        // ② 次にアップロードファイルを処理
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $i => $photo) {
                if ($photo && $photo->isValid()) {
                    $fileContent = file_get_contents($photo->getRealPath());
                    $mimeType = $photo->getMimeType();
                    $base64Image = 'data:' . $mimeType . ';base64,' . base64_encode($fileContent);
                    $priority = $request->input("priorities.$i") ?? ($i + 1);
    
                    $existingPhotoId = $request->input("existing_photos.$i");
    
                    // ★ 削除されたIDならスキップする
                    if (in_array($existingPhotoId, $deletedPhotoIds)) {
                        // 削除済みなので何もしない
                        continue;
                    }
    
                    if ($existingPhotoId) {
                        // 既存レコードが残っていれば update
                        $photoRecord = Photo::find($existingPhotoId);
                        if ($photoRecord) {
                            $photoRecord->update([
                                'image' => $base64Image,
                                'priority' => $priority,
                            ]);
                        }
                    } else {
                        // 新規登録
                        Photo::create([
                            'business_id' => $business->id,
                            'image' => $base64Image,
                            'priority' => $priority,
                        ]);
                    }
                }
            }
        }
    
        return true;
    }
    


    public function edit($business_id)
    {
        // 優先度順に既存の写真を取得
        $photos = $this->photo->where('business_id', $business_id)
                              ->orderBy('priority')
                              ->get();
                              
        $business = $this->business->findOrFail($business_id);

        return view('businessusers.posts.businesses.photos.edit')
            ->with('photos', $photos)
            ->with('business', $business);
    }

    // public function destroy($id)
    // {
    //     $photo = $this->photo->findOrFail($id);

    //     // 画像ファイル削除のコードは不要！（Base64なので物理ファイルなし）
    //     // もし古いデータ（物理ファイルパス形式）がある場合だけ消す
    //     if ($photo->image && !Str::startsWith($photo->image, 'data:')) {
    //         $imagePath = ltrim($photo->image, '/');
    //         Storage::disk('public')->delete($imagePath);
    //     }

    //     // データベースから削除
    //     $photo->delete();

    //     return redirect()->back()->with('success', 'Photo deleted successfully');
    // }

    public function destroy($id)
{
    $photo = $this->photo->findOrFail($id);
    $photo->delete();

    return response()->json(['message' => 'Photo deleted successfully']);
}
}