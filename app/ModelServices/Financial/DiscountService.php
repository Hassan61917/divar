<?php

namespace App\ModelServices\Financial;


use App\Exceptions\ModelException;
use App\Models\Discount;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DiscountService
{
    public function make(User $user, array $data): Discount
    {
        return $user->discounts()->create($data);
    }

    public function makeFor(User $user, array $data): Discount
    {
        if ($this->hasService($data)) {
            $service = $user->services()->find($data['service_id']);
            if (!$service || !$service->items()->find($data['item_id'])) {
                throw new ModelException("invalid data");
            }
            $data["category_id"] = $service->category_id;
        }
        return $this->make($user, $data);
    }

    public function getAll(array $relations = []): Builder
    {
        return Discount::query()->with($relations);
    }

    public function getAllFor(User $user, array $relations = []): HasMany
    {
        return $user->discounts()->with($relations);
    }

    public function getUsedDiscounts(User $user, array $relations = []): Builder
    {
        return $user->usedDiscounts()->with($relations);
    }

    public function getMyDiscounts(User $user, array $relations = []): Builder
    {
        return $user->myDiscounts()->with($relations);
    }

    private function hasService(array $data): bool
    {
        return array_key_exists("service_id", $data) ||
            array_key_exists("item_id", $data);
    }
}
