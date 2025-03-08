<?php

namespace App\ModelServices\Ads;

use App\Events\QuestionWasAsked;
use App\Exceptions\ModelException;
use App\Models\Ads;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuestionService
{
    public function getAll(array $relations = []): Builder
    {
        return Question::query()->with($relations);
    }

    public function getQuestionsFor(User $user, array $relations = []): HasMany
    {
        return $user->questions()->with($relations);
    }

    public function makeQuestion(User $user, array $data): Question
    {
        $ads = Ads::find($data['ads_id']);
        if (!$ads->isPublished()) {
            throw new ModelException("only published ads can have questions");
        }
        $question = $user->questions()->create($data);
        QuestionWasAsked::dispatch($question);
        return $question;
    }

    public function answer(User $user, Question $question, string $answer): Question
    {
        if (!$user->is($question->ads->user)) {
            throw new ModelException("only ads user can answer question");
        }
        $question->update(['answer' => $answer]);
        return $question;
    }
}
