<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'company_name',
        'email',
        'password',
        'phone',
        'phone_verified_at',
        'role',
        'is_client',
        'status',
        'approved_at',
        'last_login_at',
        'last_login_ip',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'password' => 'hashed',
        'approved_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'pending',
        'role' => 'user',
        'is_client' => false,
    ];

    public function uploads(): HasMany
    {
        return $this->hasMany(Upload::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function contactGroups(): HasMany
    {
        return $this->hasMany(ContactGroup::class);
    }

    public function smsCampaigns(): HasMany
    {
        return $this->hasMany(SmsCampaign::class);
    }

    public function smsMessages(): HasMany
    {
        return $this->hasMany(SmsMessage::class);
    }

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function otps(): HasMany
    {
        return $this->hasMany(UserOtp::class, 'phone_number', 'phone');
    }

    public function adminMessages(): HasMany
    {
        return $this->hasMany(AdminMessage::class, 'user_id');
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isClient(): bool
    {
        return $this->is_client === true;
    }


    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function recordLogin(string $ipAddress = null): void
    {
        $this->update([
            'last_login_at' => now(),
        ]);
    }



    public function smsSenderIds(): HasMany
    {
        return $this->hasMany(SmsSenderId::class);
    }


    public function hasSenderIds(): bool
    {
        return $this->smsSenderIds()->exists();
    }
}
