<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\AuthUserController;
use App\Http\Requests\v1\User\UserCommentRequest;
use App\Http\Resources\v1\CommentResource;
use App\Models\Comment;
use App\ModelServices\Social\CommentService;
use Illuminate\Http\JsonResponse;

class UserCommentController extends AuthUserController
{
    protected string $resource = CommentResource::class;

    public function __construct(
        public CommentService $commentService
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $comments = $this->commentService->getCommentsFor($this->authUser(), ["ads"]);
        return $this->ok($this->paginate($comments));
    }
    public function myAdsComments(): JsonResponse
    {
        $comments = $this->commentService->getAdsComments($this->authUser(),["ads"]);
        return $this->ok($this->paginate($comments));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserCommentRequest $request): JsonResponse
    {
        $data = $request->validated();
        $comment = $this->commentService->make($this->authUser(), $data);
        $comment->load("ads");
        return $this->ok($comment);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment): JsonResponse
    {
        $comment->load(['ads', "reply"]);
        return $this->ok($comment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserCommentRequest $request, Comment $comment): JsonResponse
    {
        $comment->update($request->validated());
        return $this->ok($comment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment): JsonResponse
    {
        $comment->delete();
        return $this->deleted();
    }
}
