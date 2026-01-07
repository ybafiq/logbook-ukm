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
        'approved_at',
        'supervisor_signature',
        'supervisor_approved',
        'approved_by',
        'approved_at',
        'supervisor_comment',
        'weekly_reflection_content',
        'reflection_week_start',
        'reflection_supervisor_signed',
        'reflection_signed_by',
        'reflection_signed_at'
    ];
    
    protected $casts = [
        'date' => 'date',
        'approved_at' => 'datetime',
        'supervisor_approved' => 'boolean',
        'reflection_week_start' => 'date',
        'reflection_signed_at' => 'datetime',
        'reflection_supervisor_signed' => 'boolean',
    ];

    public function student() 
    { 
        return $this->belongsTo(User::class, 'user_id'); 
    }

    public function approver() 
    { 
        return $this->belongsTo(User::class, 'approved_by'); 
    }

    public function reflectionSigner() 
    { 
        return $this->belongsTo(User::class, 'reflection_signed_by'); 
    }
}
