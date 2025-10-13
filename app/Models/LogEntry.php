<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogEntry extends Model
{
    use SoftDeletes;
    
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'activity',
        'comment',
        'supervisor_approved',
        'approved_by',
        'approved_at'
    ];

    public function student() 
    { 
        return $this->belongsTo(User::class, 'user_id'); 
    }

    public function approver() 
    { 
        return $this->belongsTo(User::class, 'approved_by'); 
    }
}
