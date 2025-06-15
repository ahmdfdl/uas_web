<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsApproval extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'news_id',
        'editor_id',
        'action',
        'notes',
        'action_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'action_at' => 'datetime',
    ];

    // Relationships
    public function news()
    {
        return $this->belongsTo(News::class);
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'editor_id');
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('action', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('action', 'rejected');
    }

    public function scopeByEditor($query, $editorId)
    {
        return $query->where('editor_id', $editorId);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('action_at', '>=', now()->subDays($days));
    }

    // Accessors
    public function getFormattedActionAtAttribute()
    {
        return $this->action_at->format('d M Y, H:i');
    }

    public function getActionBadgeAttribute()
    {
        return $this->action === 'approved' ? 'success' : 'danger';
    }

    public function getActionIconAttribute()
    {
        return $this->action === 'approved' ? 'fa-check' : 'fa-times';
    }

    // Helper Methods
    public function isApproval()
    {
        return $this->action === 'approved';
    }

    public function isRejection()
    {
        return $this->action === 'rejected';
    }
}