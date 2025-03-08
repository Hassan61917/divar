<?php

namespace App\ModelServices\Social;


use App\Models\Ads;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VisitService
{
    public function getVisits(array $relations = [])
    {
        return Visit::withTrashed()->with($relations);
    }


    public function userVisits(User $user, array $relations = [])
    {
        return $user->visits()->with($relations);
    }

    public function adsVisits(Ads $ads, array $relations = [])
    {
        return $ads->visits()->with($relations);
    }

    public function destroyAllFor(User $user): void
    {
        $user->visits()->delete();
    }

    public function visit(Ads $ads, ?User $user, string $ip): ?Visit
    {
        if ($user) {
            $this->updateVisitsUser($ip, $user);
        }
        if ($this->isVisited($ip, $user, $ads)) {
            return null;
        }
        return $ads->visits()->create([
            'ip' => $ip,
            "user_id" => $user->id
        ]);
    }

    public function lastVisit(string $ip, ?User $user, Ads $ads)
    {
        return $this->getVisit($ip, $user, $ads)->latest()->first();
    }

    public function isVisited(string $ip, ?User $user, Ads $ads): bool
    {
        return $this->getVisit($ip, $user, $ads)->exists();
    }

    private function getVisit(string $ip, ?User $user, Ads $ads): HasMany
    {
        return $ads->visits()
            ->where("ip", $ip)
            ->where("user_id", $user?->id);
    }

    private function updateVisitsUser(string $ip, User $user): void
    {
        $visits = Visit::onlyIps($ip);
        if (!$visits->exists()) {
            return;
        }
        $visits->update(["user_id", $user->id]);
    }
}
