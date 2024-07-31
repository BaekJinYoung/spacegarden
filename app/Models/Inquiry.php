<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inquiry extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name', // 이름
        'contact', // 연락처
        'agreement', // 개인정보처리방침 동의
        'inquiry_category', // 문의 유형
        'email', // 이메일
        'message', // 문의 내용(텍스트)
    ];

    protected $dates = ['deleted_at'];
}
