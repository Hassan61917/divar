<?php

namespace App\Handlers\Ads\Rules;

use App\Exceptions\ModelException;
use App\Handlers\IModelHandler;
use App\Models\Ads;
use App\ModelServices\Ads\AdsService;
use App\ModelServices\Ads\OfferOrderService;
use Illuminate\Database\Eloquent\Model;

class CheckLimit implements IModelHandler
{
    public function __construct(
        private AdsService      $adsService,
        private OfferOrderService $orderService
    )
    {
    }

    public function handle(Model|Ads $model, array $params = []): void
    {
        $limit = $this->orderService->findLimit($model);
        if ($this->adsService->isReachedLimit($model->user, $limit) && !$this->hasOffer($model)) {
            throw new ModelException("you have reached the limit");
        }
    }

    private function hasOffer(Ads $ads): bool
    {
        $order = $this->orderService->getAvailableOffer($ads->user, $ads->category);
        return $order !== null;
    }
}
