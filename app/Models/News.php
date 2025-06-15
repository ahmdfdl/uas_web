<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class News extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'gallery',
        'category_id',
        'author_id',
        'status',
        'published_at',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'views_count',
        'is_featured',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'gallery' => 'array',
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'views_count' => 'integer',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function approvals()
    {
        return $this->hasMany(NewsApproval::class);
    }

    public function latestApproval()
    {
        return $this->hasOne(NewsApproval::class)->latest();
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', now());
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByAuthor($query, $authorId)
    {
        return $query->where('author_id', $authorId);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Mutators
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    // Accessors
    public function getFeaturedImageUrlAttribute()
    {
        return $this->featured_image 
            ? asset('storage/uploads/news/' . $this->featured_image)
            : asset('assets/img/default-news.jpg');
    }

    public function getExcerptAttribute($value)
    {
        return $value ?: Str::limit(strip_tags($this->content), 150);
    }

    public function getReadingTimeAttribute()
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $readingTime = ceil($wordCount / 200); // Average reading speed
        return $readingTime . ' min read';
    }

    public function getFormattedPublishedAtAttribute()
    {
        return $this->published_at ? $this->published_at->format('d M Y, H:i') : '-';
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => 'secondary',
            'pending' => 'warning',
            'approved' => 'info',
            'rejected' => 'danger',
            'published' => 'success',
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    // Route Model Binding
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Helper Methods
    public function isPublished()
    {
        return $this->status === 'published' && $this->published_at <= now();
    }

    public function isDraft()
    {
        return $this->status === 'draft';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function canBeEditedBy(User $user)
    {
        return $user->isAdmin() || ($user->isWartawan() && $this->author_id === $user->id);
    }

    public function canBeApprovedBy(User $user)
    {
        return $user->canApproveNews();
    }

    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function submitForReview()
    {
        $this->update(['status' => 'pending']);
    }

    public function approve(User $editor, $notes = null)
    {
        $this->update(['status' => 'approved']);
        
        $this->approvals()->create([
            'editor_id' => $editor->id,
            'action' => 'approved',
            'notes' => $notes,
            'action_at' => now(),
        ]);
    }

    public function reject(User $editor, $notes = null)
    {
        $this->update(['status' => 'rejected']);
        
        $this->approvals()->create([
            'editor_id' => $editor->id,
            'action' => 'rejected',
            'notes' => $notes,
            'action_at' => now(),
        ]);
    }

    public function publish()
    {
        $this->update([
            'status' => 'published',
            'published_at' => now(),
        ]);
    }
}