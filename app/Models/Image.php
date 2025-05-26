<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Permission\Traits\HasRoles;

class Image extends Model
{
    use HasRoles;
    protected $fillable = [
        'path',
        'imageable_id',
        'imageable_type',
    ];

    /**
     * Get the parent imageable model (event or location).
     */
    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }
}
