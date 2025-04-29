<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasAvatar
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nip',
        'name',
        'email',
        'password',
        'phone',
        'office_id',
        'avatar_url',
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

    public function getFilamentAvatarUrl(): ?string
{
    $avatarPath = $this->avatar_url ?? $this->avatar ?? null;

    if ($avatarPath && Storage::disk('public')->exists($avatarPath)) {
        return asset('storage/' . $avatarPath);
    }

    return 'https://ui-avatars.com/api/?name=' . urlencode($this->name);
}

    protected static $pendingOfficeIds = [];

    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id');
    }

    public function createdVendors()
    {
        return $this->hasMany(Vendor::class, 'created_by');
    }

    public function updatedVendors()
    {
        return $this->hasMany(Vendor::class, 'updated_by');
    }

    public function createdOffices()
    {
        return $this->hasMany(Office::class, 'created_by');
    }

    public function updatedOffices()
    {
        return $this->hasMany(Office::class, 'updated_by');
    }

    public static function setPendingOfficeIds(array $officeIds)
    {
        static::$pendingOfficeIds = $officeIds;
    }

    public static function applyPendingOfficeIds()
    {
        foreach (static::$pendingOfficeIds as $userId => $officeId) {
            if ($officeId) {
                static::where('id', $userId)->update(['office_id' => $officeId]);
            }
        }
        static::$pendingOfficeIds = [];
    }
}
