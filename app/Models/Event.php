<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Traits\HasRoles;

class Event extends Model
{
    use HasRoles;

    protected $fillable = [
        'name',
        'description',
        'event_date',
        'start_time',
        'end_time',
        'capacity',
        'status',
        'location_id',
        'event_type_id',
        'organizer_id',
        'created_by',
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

    /** Apply the scope to a given Eloquent query builder.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  function
     * 
     * @return void
     */
    protected static function booted()
    {
        static:: addGlobalScope('status', function(Builder $builder){
            $builder->where('status','published');
        });
    }

    /**
     * Get all of the images for the event.
     * 
     * @return Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function images(){
        return $this->morphMany(Image::class , 'imageable');
    }

        
    /**
     * Bring the latest image for each event
     */
    public function latestImage()
    {
        return $this->morphOne(Image::class , 'imageable')->ofMany('created_at','max');       
    }

    /**
     * The attendees that belong to the event.
     */
    public function attendees(){
        return $this->belongsToMany(User::class,'reservations','event_id','user_id');
    }

    /**
     * Get the organizer that owns the event.
     */
    public function organizer(){
        return $this->belongsTo(User::class ,'organizer_id');
    }

    /**
     * Get the creator that owns the event.
     */
    public function creator(){
        return $this->belongsTo(User::class,'created_by');
    }

    /**
     * Get the event_type that owns the event.
     */
    public function event_type(){
        return $this->belongsTo(EventType::class);
    }

    /**
     * Get the location that owns the event.
     */
    public function location(){
        return $this->belongsTo(Location::class);
    }

    /**
     * Get the reservations for the event.
     */
    public function reservations(){
        return $this->hasMany(Reservation::class);
    }
}
