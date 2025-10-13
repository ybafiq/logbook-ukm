<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class WeeklyReflection extends Model
{
    use HasFactory;
    
    protected $table = 'weekly_reflection';

    protected $fillable = [
        'user_id',
        'week_start',
        'content',
        'supervisor_signed',
        'signed_by',
        'signed_at'
    ];
    
    protected $casts = [
        'week_start' => 'date',
        'signed_at' => 'datetime',
        'supervisor_signed' => 'boolean',
    ];

    public function student() 
    { 
        return $this->belongsTo(User::class, 'user_id'); 
    }
    
    public function signer() 
    { 
        return $this->belongsTo(User::class, 'signed_by'); 
    }
}
