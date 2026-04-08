<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'email_verified_at',
        'mobile',
        'mobile_verified_at',
        'password',
        'mobile_verified_code',
        'email_verified_code',
        'role',
        'image',
        'balance',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
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
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    public function claims()
    {
        return $this->hasMany(CouponClaim::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function news()
    {
        return $this->hasMany(News::class, 'author_id');
    }

    public function cardItems()
    {
        return $this->hasMany(CardItem::class, 'card_item_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function billingAddress()
    {
        return $this->hasOne(BillingAddress::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function topUpReviews()
    {
        return $this->hasMany(TopUpReview::class);
    }

    public function userCards()
    {
        return $this->hasMany(UserCard::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function supports()
    {
        return $this->hasMany(Support::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}
