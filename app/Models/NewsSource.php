<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsSource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
    ];

    // Define relationships
    public function preferences()
    {
        return $this->hasMany(UserPreference::class);
    }
}
