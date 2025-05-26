<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Location extends Model
{
    use HasRoles;
    protected $fillable = [
        'name',
        'address',
        'city',
        'capacity',
        'path'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'capacity' => 'integer',
        ];
    }

    /**
     * Get all of the images for the location.
     * 
     * @return Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function images(){
        return $this->morphMany(Image::class , 'imageable');
    }

    /**
     * Bring the latest image for each location
     */
    public function latestImage()
    {
        return $this->morphOne(Image::class , 'imageable')->ofMany('created_at','max');       
    }

    /**
     * Get the events for the location.
     */
    public function events(){
        return $this->hasMany(Event::class);
    }
}
