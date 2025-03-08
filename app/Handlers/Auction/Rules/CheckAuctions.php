<?php

namespace App\Handlers\Auction\Rules;

use App\Exceptions\ModelException;
use App\Handlers\IModelHandler;
use App\ModelServices\Ads\AuctionService;
use Illuminate\Database\Eloquent\Model;

class CheckAuctions implements IModelHandler
{
    public function __construct(
        protected AuctionService $auctionService
    )
    {
    }

    public function handle(Model $model, array $params = []): void
    {
        if ($this->auctionService->hasInProcessAuction($model->user)) {
            throw new ModelException("an auction is already in process");
        }
    }
}
