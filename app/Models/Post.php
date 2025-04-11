<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory;
    use Sluggable;
    
    protected $fillable = ['title', 'description', 'user_id', 'image', 'slug'];
    
    protected $appends = ['image_url'];
    
    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the post's image URL.
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->image) {
                    return null;
                }
                return Storage::url('posts/' . $this->image);
            },
        );
    }
    
    /**
     * Delete the post's image from storage when the post is deleted.
     */
    protected static function booted()
    {
        static::deleting(function ($post) {
            if ($post->image) {
                Storage::delete('posts/' . $post->image);
            }
        });
    }
}
