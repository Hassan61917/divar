<?php

namespace App\Filters\Ads;

use App\Filters\ModelFilter;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;

class AdsFilter extends ModelFilter
{
    protected array $handlers = ["category", "price"];

    public function category(Builder $builder, ?string $value = null): Builder
    {
        $category = Category::where("slug", $value)->firstOrFail();
        if ($category) {
            $ids = [$category->id, ...$category->getChildrenIds()];
            $builder = $builder->whereIn("ads.category_id", $ids);
        }
        return $builder;
    }
    public function price(Builder $builder, ?string $value = null): Builder
    {
        $parts = explode("-", $value);
        return $builder->whereBetween("ads.price", [$parts[0], $parts[1]]);
    }
}
