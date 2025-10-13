<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectEntry extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'activity',
        'comment',
        'supervisor_approved',
        'approved_by',
        'approved_at'
    ];
    
    protected $casts = [
        'date' => 'date',
        'approved_at' => 'datetime',
        'supervisor_approved' => 'boolean',
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
