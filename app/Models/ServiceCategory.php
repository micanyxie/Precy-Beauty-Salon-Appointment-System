<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceCategory extends Model
{
    use SoftDeletes;

    // Add this line right here:
    protected $fillable = ['name'];

    /**
     * Relationship: A category has many services
     */
    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
