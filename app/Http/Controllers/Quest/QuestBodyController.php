<?php
namespace App\Http\Controllers\Quest;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Quest;
use App\Models\QuestBody;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File; // これを上に追加
use App\Models\Spot;
use App\Models\Business;

class QuestBodyController extends Controller
{
    private $questbody;
    private $quest;

    public function __construct(Quest $quest, QuestBody $questbody){
        // $this->quest = new Quest();
        $this->questbody = $questbody;
        $this->quest = $quest;
    }

    ///================================================================Store QuestBody
    public function storeQuestBody(Request $request){
        try {
            $validated = $request->validate([
                'day_number' => 'required|integer',
                'introduction' => 'required|string',
                'business_title' => 'nullable|max:30',
                'is_agenda' => 'nullable|in:0,1',
                'images.*' => 'nullable|image|max:2048|mimes:jpg,jpeg,png,gif',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            dd("🔥 バリデーションエラー:", $e->errors());
        }

        $quest_id = $request->input('quest_id');

        $questbody = new QuestBody();
        $questbody->quest_id = $quest_id;
        $questbody->day_number = $request->day_number;
        $questbody->introduction = $request->introduction;
        $questbody->business_title = $request->business_title;
        $questbody->is_agenda = $request->is_agenda ?? "1";

        if ($request->spot_business_type === 'spot') {
            $questbody->spot_id = (int) $request->spot_business_id;
        } elseif ($request->spot_business_type === 'business') {
            $questbody->business_id = (int) $request->spot_business_id;
        }

        // ✅ 画像保存（複数対応）
        $imageDataList = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // ファイルの中身を読み込む
                $fileContent = file_get_contents($image->getRealPath());
                
                // MIMEタイプを取得（例: image/jpeg）
                $mimeType = $image->getMimeType();
                
                // base64エンコードしてデータURL形式にする
                $base64Image = 'data:' . $mimeType . ';base64,' . base64_encode($fileContent);

                // 配列に格納
                $imageDataList[] = $base64Image;
            }
        } else {
            dd("📷 画像が届いてない", $request->allFiles());
        }
        // $imageDataList = [];
        // if ($request->hasFile('images')) {
        //     foreach ($request->file('images') as $image) {
        //         $filename = time() . '_' . $image->getClientOriginalName();
        //         $filePath = $image->storeAs('images/quests', $filename, 'public');
        //         $imageDataList[] = '/' . ltrim($filePath, '/');

        //     }
        // }else{
        //     dd("📷 画像が届いてない", $request->allFiles());
        // }

        $questbody->image = json_encode($imageDataList);
        $questbody->save();

        // 🔁 dayList 生成（helper使わずロールに応じて処理）
        $quest = Quest::withTrashed()->findOrFail($quest_id);
        $roleId = Auth::user()->role_id;
        $dayList = [];
        $dayNumber = 1;

        if ($roleId == 1 && $quest->start_date && $quest->end_date) {
            $start = \Carbon\Carbon::parse($quest->start_date);
            $end = \Carbon\Carbon::parse($quest->end_date);
            $current = $start->copy();

            while ($current->lte($end)) {
                $dayList[] = [
                    'number' => $dayNumber,
                    'date' => $current->format('Y-m-d'),
                ];
                $current->addDay();
                $dayNumber++;
            }
        } elseif ($roleId == 2 && $quest->duration) {
            for ($i = 1; $i <= $quest->duration; $i++) {
                $dayList[] = [
                    'number' => $i,
                    'date' => null,
                ];
            }
        }
        // 🔄 questBodies取得
        $questBodies = QuestBody::with(['spot', 'business'])
            ->where('quest_id', $quest_id)
            ->orderBy('day_number', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'quest_id' => $quest_id,
        ]);
    }

    public function updateQuestBody(Request $request, $id){
        try {
            $validated = $request->validate([
                'day_number' => 'required|integer',
                'introduction' => 'required|string',
                'business_title' => 'nullable|max:30',
                'is_agenda' => 'nullable|in:0,1',
                'images.*' => 'nullable|image|max:2048|mimes:jpg,jpeg,png,gif',
                'existing_images' => 'nullable|array',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }

        $questbody = QuestBody::findOrFail($id);
        $questbody->day_number = $request->day_number;
        $questbody->introduction = $request->introduction;
        $questbody->business_title = $request->business_title;
        $questbody->is_agenda = $request->is_agenda ?? "1";

        // Spot or Business の紐付け
        if ($request->spot_business_type === 'spot') {
            $questbody->spot_id = (int) $request->spot_business_id;
            $questbody->business_id = null;
        } elseif ($request->spot_business_type === 'business') {
            $questbody->business_id = (int) $request->spot_business_id;
            $questbody->spot_id = null;
        }

        // 画像処理
        $existingImages = $request->input('existing_images', []);
        $newImageList = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image && $image->isValid()) {
                    // ファイルの中身を取得
                    $fileContent = file_get_contents($image->getRealPath());

                    // MIMEタイプを取得
                    $mimeType = $image->getMimeType();

                    // base64エンコードしてデータURL形式にする
                    $base64Image = 'data:' . $mimeType . ';base64,' . base64_encode($fileContent);

                    // 配列に格納
                    $newImageList[] = $base64Image;
                }
            }
        }
        // $existingImages = $request->input('existing_images', []);
        // $newImageList = [];

        // if ($request->hasFile('images')) {
        //     foreach ($request->file('images') as $image) {
        //         if ($image && $image->isValid()) {
        //             $filename = time() . '_' . $image->getClientOriginalName();
        //             $filePath = $image->storeAs('images/quests', $filename, 'public');
        //             $newImageList[] = '/' . ltrim($filePath, '/');

        //         }
        //     }
        // }

        $mergedImages = array_unique(array_merge($existingImages, $newImageList));

        if (count($mergedImages) === 0) {
            return response()->json([
                'status' => 'error',
                'message' => '画像が1枚以上必要です。',
            ], 400);
        }

        $questbody->image = json_encode($mergedImages);
        $questbody->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Quest body updated!',
            'quest_id' => $questbody->quest_id,
        ]);
}


    public function deleteQuestBody($id){
        $questbody = QuestBody::findOrFail($id);
        $quest_id = $questbody->quest_id;

        $questbody->delete();

        // 🔁 編集ページに戻る
        return redirect()->route('quest.edit', ['quest_id' => $quest_id])
            ->with('success', 'Quest body deleted!');
    }

    public function toggleAgenda(Request $request, $id){
        $request->validate([
            'is_agenda' => 'required|boolean',
        ]);

        $questbody = QuestBody::findOrFail($id);
        $questbody->is_agenda = $request->is_agenda;
        $questbody->save();

        return response()->json([
            'message' => 'is_agenda updated successfully',
            'is_agenda' => $questbody->is_agenda,
        ]);
    }

    public function getAllQuestBodies($questId){
        $questBodies = QuestBody::with(['spot', 'business']) // ← 🔥リレーション読み込み
            ->where('quest_id', $questId)
            ->orderBy('day_number', 'asc')
            ->get();
    
        return view('quests.quest-body-partial', compact('questBodies'))->render();
    }

    public function deleteQuestBodyImage(Request $request){
        $request->validate([
            'questbody_id' => 'required|integer',
            'image_path' => 'required|string',
        ]);

        $questBody = QuestBody::findOrFail($request->questbody_id);
        $images = json_decode($questBody->image, true);
        $updatedImages = [];

        foreach ($images as $img) {
            if ($img === $request->image_path) {
                $fullPath = public_path('storage/' . ltrim($img, '/'));
                if (File::exists($fullPath)) {
                    File::delete($fullPath);
                }
            } else {
                $updatedImages[] = $img;
            }
        }

        $questBody->image = json_encode($updatedImages);
        $questBody->save();

        return response()->json(['message' => 'Image deleted successfully']);
    }

    
//=================================================================Search
    public function getMyBusinesses(){
        $userId = Auth::id();

        $businesses = Business::where('user_id', $userId)
            ->select('id', 'name')
            ->get()
            ->map(function ($business) {
                return [
                    'id' => $business->id,
                    'name' => $business->name,
                    'type' => 'business',
                ];
            });
            // dd($businesses);
        return response()->json($businesses);
    }

    public function searchAjax(Request $request){
        $query = $request->query('query');

        $spots = Spot::where('title', 'like', "%{$query}%")
            ->select('id', 'title')
            ->get()
            ->map(function ($spot) {
                return [
                    'id' => $spot->id,
                    'name' => $spot->title,
                    'type' => 'spot',
                ];
            });

        $businesses = Business::where('name', 'like', "%{$query}%")
                            ->select('id', 'name')
                            ->get()
                            ->map(function ($business) {
                                return [
                                    'id' => $business->id,
                                    'name' => $business->name,
                                    'type' => 'business',
                                ];
                            });

        // 通常のコレクションを使用してマージ
        $results = collect($spots)->merge($businesses);

        return response()->json($results);

    }
}

