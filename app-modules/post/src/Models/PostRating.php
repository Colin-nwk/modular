<?php

namespace Modules\Post\Models;

use App\Models\User;
use Modules\Post\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostRating extends Model
{
    use HasFactory;
    protected $fillable = ['rating'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
