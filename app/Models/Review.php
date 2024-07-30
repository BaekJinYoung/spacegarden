<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'content',
        'filter_category',
        'filter_area',
        'image',
        'views',
        'is_featured',
        'file',
    ];

    protected $dates = ['deleted_at'];
}
