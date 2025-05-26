<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Reservation extends Model
{
    use HasRoles;
    protected $fillable = [
        'status',
        'user_id',
        'event_id',
    ];
    
    /**
     * Get the event that owns the reservation.
     */
    public function event(){
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the user that owns the reservation.
     */
    public function user(){
        return $this->belongsTo(User::class);
    }
}
