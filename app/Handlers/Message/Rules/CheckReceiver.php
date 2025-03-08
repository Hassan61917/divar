<?php

namespace App\Handlers\Message\Rules;

use App\Exceptions\ModelException;
use App\Handlers\IModelHandler;
use App\Models\Message;
use App\ModelServices\Social\BlockService;
use Illuminate\Database\Eloquent\Model;

class CheckReceiver implements IModelHandler
{
    public function __construct(
        protected BlockService $blockService
    )
    {
    }

    public function handle(Model|Message $model, array $params = []): void
    {
        if (!$this->canSend($model)) {
            throw new ModelException("message can't be sent");
        }
    }

    private function canSend(Message $message): bool
    {
        return !$this->blockService->isBlocked(
            $message->receiver,
            $message->sender
        );
    }


}
