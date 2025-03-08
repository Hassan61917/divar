<?php

namespace User;

use App\Models\Comment;
use Tests\UserTest;

class UserLikeTest extends UserTest
{
    public function test_like_user_can_like()
    {
        $data = $this->createData();
        $this->withoutExceptionHandling();
        $this->postJson(route("v1.user.like"), $data);
        $this->assertDatabaseCount("likes", 1);
        $this->assertDatabaseHas("likes", ["isLike" => true]);
    }

    public function test_like_user_should_remove_like_if_hit_twice()
    {
        $data = $this->createData();
        $this->postJson(route("v1.user.like"), $data);
        $this->assertDatabaseCount("likes", 1);
        $this->postJson(route("v1.user.like"), $data);
        $this->assertDatabaseCount("likes", 0);
    }

    public function test_like_user_can_dislike()
    {
        $data = $this->createData();
        $this->postJson(route("v1.user.dislike"), $data);
        $this->assertDatabaseCount("likes", 1);
        $this->assertDatabaseHas("likes", ["isLike" => false]);
    }

    public function test_like_user_should_remove_dislike_if_hit_twice()
    {
        $data = $this->createData();
        $this->postJson(route("v1.user.dislike"), $data);
        $this->assertDatabaseCount("likes", 1);
        $this->postJson(route("v1.user.dislike"), $data);
        $this->assertDatabaseCount("likes", 0);
    }

    public function test_like_user_can_not_have_like_and_dislike_in_same_time()
    {
        $data = $this->createData();
        $this->postJson(route("v1.user.like"), $data);
        $this->assertDatabaseCount("likes", 1);
        $this->assertDatabaseHas("likes", ["isLike" => true]);
        $this->postJson(route("v1.user.dislike"), $data);
        $this->assertDatabaseCount("likes", 1);
        $this->assertDatabaseHas("likes", ["isLike" => false]);
    }

    private function createData(): array
    {
        $comment = Comment::factory()->create();
        return [
            "model" => "comment",
            "model_id" => $comment->id,
        ];
    }
}
