<?php

namespace App\ModelServices\Social;

use App\Exceptions\ModelException;
use App\Models\Ads;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommentService
{
    public function getComments(array $relations = []): Builder
    {
        return Comment::query()->with($relations);
    }

    public function getAdsComments(User $user, array $relations = []): Builder
    {
        return Comment::query()
            ->join('posts', 'comments.ads_id', '=', 'ads.id')
            ->where("ads.user_id", $user->id)
            ->with($relations);
    }

    public function getCommentsFor(User $user, array $relations = []): HasMany
    {
        return $user->comments()->with($relations);
    }


    public function make(User $user, array $data)
    {
        $ads = Ads::find($data['ads_id']);
        if (!$ads->isPublished()) {
            throw new ModelException("only published ads can have comments");
        }
        return $user->comments()->create($data);
    }

}
