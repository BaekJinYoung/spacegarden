<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Youtube extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'link',
        'is_featured',
        'views',
    ];

    protected $dates = ['deleted_at'];
}
