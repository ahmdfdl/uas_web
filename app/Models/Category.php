<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function news()
    {
        return $this->hasMany(News::class);
    }

    public function publishedNews()
    {
        return $this->hasMany(News::class)->where('status', 'published');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeWithNewsCount($query)
    {
        return $query->withCount(['news', 'publishedNews']);
    }

    // Mutators
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    // Accessors
    public function getNewsCountAttribute()
    {
        return $this->news()->count();
    }

    public function getPublishedNewsCountAttribute()
    {
        return $this->publishedNews()->count();
    }

    // Route Model Binding
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Helper Methods
    public function hasNews()
    {
        return $this->news()->exists();
    }

    public function getColorStyleAttribute()
    {
        return "background-color: {$this->color}; color: #fff;";
    }
}