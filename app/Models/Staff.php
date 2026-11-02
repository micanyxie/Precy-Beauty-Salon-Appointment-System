<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Add this line to allow these fields to be saved
    protected $fillable = ['name', 'availability'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
