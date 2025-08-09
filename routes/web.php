<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\FollowController;
use App\Http\Middleware\PageViewMiddleware;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;

use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\BusinessesController;
use App\Http\Controllers\Admin\PostsController;
use App\Http\Controllers\Admin\CommentsController;
use App\Http\Controllers\Admin\BusinessInfoCategoryController;
use App\Http\Controllers\Admin\BusinessInfoController;

use App\Http\Controllers\Business\ProfileController;
use App\Http\Controllers\Business\PhotoController;
use App\Http\Controllers\Business\BusinessController;
use App\Http\Controllers\Business\BusinessLikeController;
use App\Http\Controllers\Business\BusinessCommentController;
use App\Http\Controllers\Business\BusinessCommentLikeController;
use App\Http\Controllers\Business\BusinessPromotionController;

use App\Http\Controllers\Quest\QuestController;
use App\Http\Controllers\Quest\QuestLikeController;
use App\Http\Controllers\Quest\QuestBodyController;
use App\Http\Controllers\Quest\QuestCommentController;
use App\Http\Controllers\Quest\QuestCommentLikeController;

use App\Http\Controllers\Spot\SpotController;
use App\Http\Controllers\Spot\SpotLikeController;
use App\Http\Controllers\Spot\SpotCommentController;
use App\Http\Controllers\Spot\SpotCommentLikeController;

use App\Http\Controllers\TouristProfileController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home/posts/all', [HomeController::class, 'showAll'])->name('posts.all');
Route::get('/home', [HomeController::class, 'index'])->name('home'); // for showing a home page
Route::get('/search', [HomeController::class, 'search'])->name('search'); // for showing a search page
Route::post('/sort', [HomeController::class, 'sort'])->name('sort'); //for using on search page
Route::get('/faq', [Homecontroller::class, 'showFAQ'])->name('faq'); // for showing FAQ page


//PROFILES
Route::group(['prefix' => '/profile', 'as' => 'profile.'], function () {
    Route::get('/{id}', [ProfileController::class, 'showProfile'])->name('header');
    Route::get('/{id}/edit', [ProfileController::class, 'edit'])->name('edit');
    Route::delete('/avatar', [ProfileController::class, 'deleteAvatar'])->name('avatar.delete');
    Route::delete('/header', [ProfileController::class, 'deleteHeader'])->name('header.delete');
    Route::patch('/{id}/update', [ProfileController::class, 'update'])->name('update');
    Route::delete('/{id}/deactivate', [ProfileController::class, 'deactivate'])->name('deactivate');
    // Route::patch('/business/profile/{id}/promotions', [ProfileController::class, 'showPromotions'])->name('promotions.show');
    Route::get('/{id}/followers', [ProfileController::class, 'followers'])->name('followers');
    Route::get('/change-password/{id}', [ProfileController::class, 'changePassword'])->name('change-password');
    Route::patch('/update-password',[ProfileController::class, 'updatePassword'])->name('update-password');
});

//FOLLOWS
Route::post('/follow/store/{user_id}', [FollowController::class, 'storeFollow'])->name('store.follow');
Route::delete('/follow/delete/{user_id}', [FollowController::class, 'deleteFollow'])->name('delete.follow');

//PROMOTION
Route::group(['prefix' => '/promotion', 'as' => 'promotions.'], function () {
    Route::get('/create', [BusinessPromotionController::class, 'create'])->name('create');
    Route::get('/{id}/edit', [BusinessPromotionController::class, 'edit'])->name('edit');
    Route::patch('/{id}/update', [BusinessPromotionController::class, 'update'])->name('update');
    Route::post('/store', [BusinessPromotionController::class, 'store'])->name('store');
    Route::get('/show/{id}', [BusinessPromotionController::class, 'show'])->name('show');
    Route::get('/confirm', [BusinessPromotionController::class, 'confirm'])->name('confirm');
    Route::delete('/{id}/deactivate', [BusinessPromotionController::class, 'deactivate'])->name('deactivate');
    Route::patch('/{id}/activate', [BusinessPromotionController::class, 'activate'])->name('activate');
    Route::delete('/{id}/delete', [BusinessPromotionController::class, 'delete'])->name('delete');
});

//BUSINESS
Route::group(['prefix' => '/business', 'as' => 'businesses.'], function () {
    Route::get('/create', [BusinessController::class, 'create'])->name('create');
    Route::get('/{id}/edit', [BusinessController::class, 'edit'])->name('edit');
    Route::patch('/{id}/update', [BusinessController::class, 'update'])->name('update');
    Route::post('/store', [BusinessController::class, 'store'])->name('store');
    Route::resource('businesses', BusinessController::class);
    Route::post('photos/{business}/store', [PhotoController::class, 'store'])->name('photos.store');
    Route::get('photos/edit/{business}', [PhotoController::class, 'edit'])->name('photos.edit');
    Route::patch('photos/{business}/update', [PhotoController::class, 'update'])->name('photos.update');
    Route::delete('/photo/{id}', [PhotoController::class, 'destroy'])->name('photo.destroy');
    Route::delete('/{id}/deactivate', [BusinessController::class, 'deactivate'])->name('deactivate');
    Route::patch('/{id}/activate', [BusinessController::class, 'activate'])->name('activate');
    Route::delete('/mainimage', [BusinessController::class, 'deleteMainImage'])->name('mainimage.delete');
    // Business Comments
    Route::post('/{spot_id}/comment/store', [BusinessCommentController::class, 'store'])->name('comment.store');
    Route::delete('/{spot_id}/comment/{comment_id}/destroy', [BusinessCommentController::class, 'destroy'])->name('comment.destroy');
});
Route::get('/business/{id}', [BusinessController::class, 'show'])->middleware(PageViewMiddleware::class)->name('business.show');


// BUSINESS REVIEW(from business side)
Route::group(['prefix' => '/business/reviews', 'as' => 'business.reviews.'], function () {
    Route::get('/all/{id}', [BusinessCommentController::class, 'showAllReviews'])->name('all');
    Route::get('/{id}', [BusinessCommentController::class, 'showReview'])->name('show');
    Route::get('/{id}/review/index', [BusinessCommentController::class, 'showIndex'])->name('indexreview');
});

// BUSINESS COMMENT(from tourist side)
Route::group(['prefix' => '/business/comments', 'as' => 'business.comments.'], function () {
    Route::get('/all/{id}', [BusinessCommentController::class, 'showAllComments'])->name('showcomments');
    Route::post('/store/{id}', [BusinessCommentController::class, 'addComment'])->name('addcomment');
    Route::post('/{comment_id}/like', [BusinessCommentLikeController::class, 'store'])->name('like.store');
    Route::delete('/{comment_id}/unlike', [BusinessCommentLikeController::class, 'destroy'])->name('like.delete');
});

//LIKES BUSINESS
Route::post('/home/like/business/{business_id}/store', [BusinessLikeController::class, 'storeBusinessLike'])->name('businesses.like.store');
Route::delete('/home/like/business/{business_id}/delete', [BusinessLikeController::class, 'deleteBusinessLike'])->name('businesses.like.delete');


//LIKE QUEST
Route::post('/home/like/quest/{quest_id}/store', [QuestLikeController::class, 'storeQuestLike'])->name('quests.like.store');
Route::delete('/home/like/quest/{quest_id}/delete', [QuestLikeController::class, 'deleteQuestLike'])->name('quests.like.delete');

// Post
// Route::get('/home/posts/all', [HomeController::class, 'showAll'])->name('posts.all');
Route::get('/home/posts/followings', [HomeController::class, 'showFollowings'])->name('posts.followings');
Route::get('/home/posts/quests', [HomeController::class, 'showQuests'])->name('posts.quests');
Route::get('/home/posts/spots', [HomeController::class, 'showSpots'])->name('posts.spots');
Route::get('/home/posts/locations', [HomeController::class, 'showLocations'])->name('posts.locations');
Route::get('/home/posts/events', [HomeController::class, 'showEvents'])->name('posts.events');
Route::get('/home/posts/others', [HomeController::class, 'showOthers'])->name('posts.others');
Route::get('/home/posts/travels', [HomeController::class, 'showTravels'])->name('posts.travels');

// Spot 
Route::group(['prefix' => '/spot', 'as' => 'spots.'], function () {
    Route::get('/create', [SpotController::class, 'create'])->name('create');
    Route::post('/store', [SpotController::class, 'store'])->name('store');
    Route::get('/{id}', [SpotController::class, 'show'])->middleware(PageViewMiddleware::class)->name('show');
    Route::post('/confirm/{spot_id?}', [SpotController::class, 'showConfirm'])->name('confirm');
    Route::get('/confirm/{spot_id?}', [SpotController::class, 'showConfirm'])->name('confirm.get');

    Route::get('/edit/{spot_id}', [SpotController::class, 'showEdit'])->name('edit');
    Route::patch('/update/{spot_id}', [SpotController::class, 'update'])->name('update');
    Route::delete('/{id}/deactivate', [SpotController::class, 'deactivate'])->name('deactivate');
    Route::patch('/{id}/activate', [SpotController::class, 'activate'])->name('activate');
    
    // Spot Likes
    Route::get('/{spot}/likes/json', [SpotLikeController::class, 'getLikesJson']);
    // フォーム送信用（redirect back）
    Route::post('/{spot_id}/like', [SpotLikeController::class, 'storeSpotLike'])->name('like.store');
    Route::delete('/{spot_id}/unlike', [SpotLikeController::class, 'deleteSpotLike'])->name('like.delete');

    // AJAX用（JSONレスポンス）
    Route::post('/{spot_id}/like/json', [SpotLikeController::class, 'storeSpotLikeJson'])->name('like.store.json');
    Route::delete('/{spot_id}/unlike/json', [SpotLikeController::class, 'deleteSpotLikeJson'])->name('like.delete.json');
    Route::get('/{spot}/likes/modal', [SpotLikeController::class, 'getModalHtml']);

    // Spot Comments
    Route::post('/{spot_id}/comment/store', [SpotCommentController::class, 'store'])->name('comment.store');
    Route::delete('/{spot_id}/comment/{comment_id}/destroy', [SpotCommentController::class, 'destroy'])->name('comment.destroy');
    Route::get('/comment/{comment}/likes/modal', [SpotCommentLikeController::class, 'getCommentModalHtml']);

    // Spot Comment Likes
    Route::post('/comment/{comment_id}/like', [SpotCommentLikeController::class, 'like'])->name('comment.like.store');
    Route::delete('/comment/{comment_id}/unlike', [SpotCommentLikeController::class, 'unlike'])->name('comment.like.delete');
    Route::get('/comment/{comment_id}/likes/json', [SpotCommentLikeController::class, 'getLikesJson']);
});
Route::get('spot/{id}', [SpotController::class, 'show'])->middleware(PageViewMiddleware::class)->name('spot.show');

// password reset
Route::get('/password/reset', [ForgotPasswordController::class, 'show'])->name('password.request');

// register business
Route::get('/register/business', [RegisterController::class, 'registerBusiness'])->name('register.business');
Route::post('/store/business', [App\Http\Controllers\Auth\RegisterController::class, 'storeBusiness'])->name('register.business.submit');
Route::get('/register/tourist', [App\Http\Controllers\Auth\RegisterController::class, 'registerTourist'])->name('register.tourist');
Route::post('/store/tourist', [App\Http\Controllers\Auth\RegisterController::class, 'storeTourist'])->name('register.submit');


//ADMIN
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'admin'], function () {
    // Route::get('/main', [UsersController::class, 'showLists'])->name('main');
    Route::get('/users/business', [UsersController::class, 'indexBusiness'])->name('users.business');
    Route::get('/users/applied', [UsersController::class, 'indexApplied'])->name('users.applied');
    Route::get('/users/{id}/review', [UsersController::class, 'adminReview'])->name('users.review');
    //admin/users                                                   admin.users
    Route::post('/admin/users/{user}/certify', [UsersController::class, 'certify'])->name('users.certify');
    Route::delete('/users/{id}/deactivate', [UsersController::class, 'deactivate'])->name('users.deactivate');
    Route::patch('/users/{id}/activate', [UsersController::class, 'activate'])->name('users.activate');
    Route::get('/users/tourists', [UsersController::class, 'indexTourists'])->name('users.tourists');

    Route::get('/posts/business', [BusinessesController::class, 'index'])->name('posts');
    Route::get('/posts/applied', [BusinessesController::class, 'indexApplied'])->name('posts.applied');
    Route::get('/posts/{id}/review', [BusinessesController::class, 'adminReview'])->name('posts.review');
    //admin/users                                                   admin.users
    Route::post('/admin/posts/{post}/certify', [BusinessesController::class, 'certify'])->name('posts.certify');
    Route::delete('/posts/{id}/deactivate', [BusinessesController::class, 'deactivate'])->name('posts.deactivate');
    Route::patch('/posts/{id}/activate', [BusinessesController::class, 'activate'])->name('posts.activate');
    Route::get('/posts/tourists', [PostsController::class, 'indexTourists'])->name('posts.tourists');
    Route::get('/comments', [CommentsController::class, 'indexComments'])->name('comments');
    Route::delete('/{id}/business/comment/deactivate', [BusinessCommentController::class, 'deactivateBusinessComment'])->name('deactivate.business.comment');
    Route::patch('/{id}/business/comment/activate', [BusinessCommentController::class, 'activateBusinessComment'])->name('activate.business.comment');
    Route::delete('/{id}/spot/comment/deactivate', [SpotCommentController::class, 'deactivateSpotComment'])->name('deactivate.spot.comment');
    Route::patch('/{id}/spot/comment/activate', [SpotCommentController::class, 'activateSpotComment'])->name('activate.spot.comment');
    Route::delete('/{id}/quest/comment/deactivate', [QuestCommentController::class, 'deactivateQuestComment'])->name('deactivate.quest.comment');
    Route::patch('/{id}/quest/comment/activate', [QuestCommentController::class, 'activateQuestComment'])->name('activate.quest.comment');
    //Business Info Castegory
    Route::get('/business/categories', [BusinessInfoCategoryController::class, 'index'])->name('categories');
    Route::delete('/business/categories/{id}/edit', [BusinessInfoCategoryController::class, 'delete'])->name('categories.delete');
    Route::post('/business/categories/store', [BusinessInfoCategoryController::class, 'store'])->name('categories.store');
    Route::patch('/business/categories/{id}/update', [BusinessInfoCategoryController::class, 'update'])->name('categories.update');
    //Business Info
    Route::get('/business/details', [BusinessInfoController::class, 'index'])->name('details');
    Route::delete('/business/details/{id}/edit', [BusinessInfoController::class, 'delete'])->name('details.delete');
    Route::post('/business/details/store', [BusinessInfoController::class, 'store'])->name('details.store');
    Route::patch('/business/details/{id}/update', [BusinessInfoController::class, 'update'])->name('details.update');
});

// =============================== QuestController
Route::prefix('/quest')->name('quest.')->controller(QuestController::class)->group(function () {
    //SHOW - ADD QUEST 
    Route::get('/add', 'showAddQuest')->name('add');
    Route::post('/store', 'storeQuest')->name('store');
    //SHOW - EDIT QUEST
    Route::get('/{quest_id}/edit', 'showQuestEdit')->name('edit');
    Route::put('/{quest_id}/update', 'updateQuest')->name('update');
    //SHOW - CONFIRM QUEST
    Route::get('/confirm/{quest_id}', 'showConfirmQuest')->name('confirm');
    //VIEW QUEST
    Route::get('/{quest_id}', 'showViewQuest')->middleware(PageViewMiddleware::class)->name('show');
    //RESTORE - UNHIEDE
    Route::match(['post', 'patch'], '/{quest_id}/restore', 'restore')->name('restore');
    //SOFT DELETE - HIHE (back to Confirm--> change later redirect to MyPage)
    Route::delete('/{quest_id}/hide', 'softDelete')->name('softDelete');

    Route::get('/{id}/likes/html', 'getModalHtml')->name('likes.modal');
    Route::post('/{id}/toggle-like', 'toggleLike')->name('toggle-like');
    Route::get('/{id}/likes', 'getLikes')->name('likes');
    Route::delete('/delete/{questId}', 'deleteQuest')->name('delete');
    Route::post('/follow/{userId}', [QuestController::class, 'toggleFollow'])->name('quest.toggleFollow');
});



// =============================== QuestBodyController
Route::prefix('/questbody')->name('questbody.')->controller(QuestBodyController::class)->group(function () {
    Route::post('/store', 'storeQuestBody')->name('store');
    Route::put('/update/{id}', 'updateQuestBody')->name('update');
    Route::delete('/delete/{id}', 'deleteQuestBody')->name('delete');
    Route::post('/image/delete', 'deleteImage')->name('image.delete');
    Route::post('/agenda/{id}', 'toggleAgenda')->name('toggleAgenda');
    Route::get('/getAllQuestBodies/{questId}', 'getAllQuestBodies')->name('getAllQuestBody');

    // ✅補助機能（QuestBody関連）
    Route::get('/user/searchbusinesses', 'getMyBusinesses')->name('mybusinesses');
    Route::get('/search/Ajax',  'searchAjax')->name('search');
});

// =============================== QuestCommentController
Route::prefix('/questcomment')->name('questcomment.')->controller(QuestCommentController::class)->group(function () {
    Route::post('/store/{quest_id}', 'storeQuestComment')->name('store');
    Route::delete('/delete/{id}', 'deleteQuestComment')->name('delete');
    Route::post('/{comment_id}/toggle-like', 'toggleCommentLike')->name('toggleLike');
    Route::get('/{commentId}/likes', 'getCommentLikes')->name('likes');

    Route::get('/{quest_id}/stats', 'getQuestCommentStats')->name('stats');
});


//Like to each post without page refresh (AJAX)
Route::prefix('like')->group(function () {
    Route::post('{type}/{id}/store', [LikeController::class, 'store'])->name('like.store');
    Route::delete('{type}/{id}/delete', [LikeController::class, 'destroy'])->name('like.delete');
});

// Follow to other user without page refresh
Route::post('/follow/{user_id}/store', [FollowController::class, 'follow'])->name('follow.store');
Route::delete('/follow/{user_id}/delete', [FollowController::class, 'unfollow'])->name('follow.delete');
