<?php

namespace App\Enums;

use App\Models\Comment;
use App\Models\Question;
enum LikeableModel: string
{
    case question = Question::class;
    case comment = Comment::class;
}
