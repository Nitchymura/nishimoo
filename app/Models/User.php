<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    const ADMIN_ROLE_ID = 3;
    const USER_ROLE_ID_1 = 1;
    const USER_ROLE_ID_2 = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'header',
        'introduction',
        // 'website_url',
        // 'zip',
        // 'address',
        // 'phonenumber',
        'instagram',
        'facebook',
        'x',
        'tiktok',
        'role_id',
        'official_certification'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function businesses()
    {
        return $this->hasMany(Business::class)->withTrashed()->latest();
    }

    public function businessesVisible()
    {
        return $this->hasMany(Business::class)->latest();
    }

    public function businessPromotions()
    {
        return $this->hasMany(BusinessPromotion::class)->withTrashed()->latest();
    }

    public function businessPromotionsVisible()
    {
        return $this->hasMany(BusinessPromotion::class)->latest();
    }

    public function businessComments()
    {
        return $this->hasMany(BusinessComment::class)->withTrashed()->latest();
    }

    public function businessCommentLikes()
    {
        return $this->hasMany(BusinessCommentLike::class);
    }

    public function questComments()
    {
        return $this->hasMany(QuestComment::class)->withTrashed()->latest();
    }

    public function questCommentLikes()
    {
        return $this->hasMany(QuestCommentLike::class);
    }

    public function spotComments()
    {
        return $this->hasMany(SpotComment::class)->withTrashed()->latest();
    }

    public function spottCommentLikes()
    {
        return $this->hasMany(SpotCommentLike::class);
    }

    public function businessLikes()
    {
        return $this->hasMany(BusinessLike::class);
    }

    public function questLikes()
    {
        return $this->hasMany(QuestLike::class);
    }

    public function spotLikes()
    {
        return $this->hasMany(SpotLike::class);
    }

    //user has manyu follows (user follows many users)
    public function follows()
    {
        return $this->hasMany(Follow::class, 'follower_id');
    }

    //user has many followers
    public function followers()
    {
        return $this->hasMany(Follow::class, 'followed_id');
    }

    //return true if $this user is followed by Auth user
    public function isFollowed()
    {
        if (!auth()->check()) {
            return false;
        }

        return $this->followers()->where('follower_id', Auth::user()->id)->exists();
    }

    public function isFollowing(User $user)
    {
        return $this->followings()->where('followed_id', $user->id)->exists();
    }

    public function followings()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'followed_id');
    }

    public function isMutualFollow(User $user): bool
    {
        $authUser = Auth::user();

        if (!$authUser) {
            return false;
        }

        return $authUser->isFollowing($user) && $user->isFollowing($authUser);
    }

    public function quests()
    {
        return $this->hasMany(Quest::class)->withTrashed()->latest();
    }

    public function questsVisible()
    {
        return $this->hasMany(Quest::class)->latest();
    }

    public function spots()
    {
        return $this->hasMany(Spot::class)->withTrashed()->latest();
    }

    public function spotsVisible()
    {
        return $this->hasMany(Spot::class)->latest();
    }
}
