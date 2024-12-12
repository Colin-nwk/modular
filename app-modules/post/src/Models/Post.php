<?php

namespace Modules\Post\Models;

use App\Models\User;
use Modules\Comment\Models\Comment;
use Modules\Post\Models\PostRating;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    protected $fillable = ['title', 'content', 'slug', 'media'];
    protected $casts = [
        'media' => 'array'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function ratings()
    {
        return $this->hasMany(PostRating::class);
    }

    public function averageRating()
    {
        return $this->ratings()->avg('rating');
    }

    public function getMediaAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    public function addMedia(array $mediaFiles)
    {
        $existingMedia = $this->media ?? [];
        $newMedia = array_merge($existingMedia, $mediaFiles);
        $this->update(['media' => $newMedia]);
    }

    public function removeMedia(string $filename)
    {
        $media = $this->media;
        $media = array_filter($media, fn($file) => $file !== $filename);
        $this->update(['media' => $media]);
    }
}
