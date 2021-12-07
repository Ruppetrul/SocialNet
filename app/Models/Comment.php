<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    public function author()
    {
        return $this->belongsTo(User::class,
            'id_comment_author', 'id');
    }

    public function reply()
    {
        return $this->hasOne(Comment::class,
            'id_comment', 'id_comment_reply');
    }
}
