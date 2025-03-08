<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\AuthUserController;
use App\Http\Requests\v1\User\UserQuestionRequest;
use App\Http\Resources\v1\QuestionResource;
use App\Models\Question;
use App\ModelServices\Ads\QuestionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserQuestionController extends AuthUserController
{
    protected string $resource = QuestionResource::class;

    public function __construct(
        private QuestionService $questionService,
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $questions = $this->questionService->getQuestionsFor($this->authUser(), ["ads"]);
        return $this->ok($this->paginate($questions));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserQuestionRequest $request): JsonResponse
    {
        $data = $request->validated();
        $question = $this->questionService->makeQuestion($this->authUser(), $data);
        return $this->ok($question);
    }

    public function answer(Request $request): JsonResponse
    {
        $data = $request->validate([
            "question_id" => "required|exists:questions,id",
            "answer" => "required",
        ]);
        $question = $this->questionService->answer($this->authUser(), Question::find($data['question_id']), $data["answer"]);
        return $this->ok($question);
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question): JsonResponse
    {
        $question->load("ads");
        return $this->ok($question);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserQuestionRequest $request, Question $question): JsonResponse
    {
        $data = $request->validated();
        $question->update($data);
        return $this->ok($question);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question): JsonResponse
    {
        $question->delete();
        return $this->deleted();
    }
}
