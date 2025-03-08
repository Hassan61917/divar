<?php

namespace App\ModelServices\Ads;

use App\Enums\AdsStatus;
use App\Events\AdsWasPublished;
use App\Exceptions\ModelException;
use App\Handlers\Ads\AdsHandler;
use App\Models\Ads;
use App\Models\AdsLimit;
use App\Models\DeleteReason;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class AdsService
{
    public function __construct(
        private AdsHandler $adsHandler
    )
    {
    }

    public function getAllAds(array $relations = []): Builder
    {
        return Ads::query()->published()->with($relations);
    }

    public function getAdsFor(User $user, array $relations = []): HasMany
    {
        return $user->ads()->with($relations);
    }

    public function makeAds(User $user, array $data): Ads
    {
        $data["state_id"] = $user->profile->state_id;
        $data["city_id"] = $user->profile->city_id;
        return $user->ads()->create($data);
    }

    public function updateFields(Ads $ads, array $data): Ads
    {
        $ads->update([
            "is_validated" => true,
            "fields" => $data,
        ]);
        return $ads;
    }

    public function draft(Ads $advertise): void
    {
        $advertise->update(["status" => AdsStatus::Draft->value]);
    }

    public function publish(Ads $ads): Ads
    {
        $this->adsHandler->handle($ads);
        AdsWasPublished::dispatch($ads);
        $ads->update(["status" => AdsStatus::Published->value]);
        return $ads;
    }

    public function isReachedLimit(User $user, AdsLimit $limit): bool
    {
        $count = $limit->limit;
        $adsCount = $this->getAdsFor($user)
            ->whereHas("category", function (Builder $query) use ($limit) {
                $ids = $limit->category->getChildrenIds();
                return $query->whereIn("id", $ids);
            })
            ->published()
            ->limit($count)
            ->count();
        return $adsCount >= $count;
    }

    public function update(Ads $ads, array $data): Ads
    {
        if (!$data["chat"] && !$data["call"]) {
            throw new ModelException("one of chat or call is required");
        }
        $ads->update([...$data, "status" => AdsStatus::Completed->value]);
        return $ads;
    }

    public function setDeleteReason(Ads $advertise, ?int $reason_id): void
    {
        if ($advertise->isPublished() && $reason_id) {
            $reason = DeleteReason::find($reason_id);
            DB::table("ads_delete_reason")->insert([
                "reason_id" => $reason->id,
                "ads_id" => $advertise->id
            ]);
        }
    }
}
