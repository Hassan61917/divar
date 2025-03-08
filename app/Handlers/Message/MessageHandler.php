<?php

namespace App\Handlers\Message;

use App\Handlers\Message\Rules\CheckReceiver;
use App\Handlers\ModelHandler;

class MessageHandler extends ModelHandler
{
    protected array $rules = [
        CheckReceiver::class
    ];
}
