<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class EventType extends Model
{
    use HasRoles;
    protected $fillable = [
        'name',
        'description',
    ];
    /**
     * Get the events for the event type.
     */
    public function events(){
        return $this->hasMany(Event::class);
    }
}
