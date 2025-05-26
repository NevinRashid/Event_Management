<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Casts\Attribute;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasRoles, HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'phone_number',
        'email',
        'password',
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
    /**
     * Interact with the user's first_name and last_name.
     */
    public function fullName(): Attribute
    {
        return Attribute::make(
            get : fn() => $this->first_name.''.$this->last_name
        );
    }

    /**
     * Interact with the user's first name.
     */
    protected function firstName(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
            set: fn (string $value) => strtolower($value),
        );
    }

    /**
     * Interact with the user's last name.
     */
    protected function lastName(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
            set: fn (string $value) => strtolower($value),
        );
    }

    /**
     * The attendingEvents that belong to the user.
     */
    public function attendingEvents(){
        return $this->belongsToMany(Event::class,'reservations','user_id','event_id');
    }

    /**
     * Get the organizedEvents for the user.
     */
    public function organizedEvents(){
        return $this->hasMany(Event::class,'organizer_id');
    }

    /**
     * Get the created for the user.
     */
    public function createdEvents(){
        return $this->hasMany(Event::class,'created_by');
    }

    /**
     * Get the reservations for the user.
     */
    public function reservations(){
        return $this->hasMany(Reservation::class);
    }
}
