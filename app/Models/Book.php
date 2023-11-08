<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Book extends Model
{
    use HasFactory;
    public function Reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function scopeTitle(Builder $builder, string $title): Builder
    {
        return $builder->where('title', 'LIKE', '%' . $title . '%');
    }
    public function scopeWithReviewsCount(Builder $builder, $from = null, $to = null): Builder
    {
        return $builder->withCount([
            'reviews' => fn (Builder $builder) => $this->dateRangeFilter($builder, $from, $to)
        ]);
    }
    public function scopeWithAvgRating(Builder $builder, $from = null, $to = null): Builder
    {
        return $builder->withAvg([
            'reviews' => fn (Builder $builder) => $this->dateRangeFilter($builder, $from, $to)
        ], 'rating');
    }
    public function scopePopular(Builder $builder, $from = null, $to = null): Builder
    {
        return $builder->withReviewsCount()->orderBy('reviews_count', 'desc');
    }
    public function scopeHighestRated(Builder $builder, $from = null, $to = null): Builder
    {
        return $builder->withAvgRating()->orderBy('reviews_avg_rating', 'desc');
    }
    public function scopeMinReviews(Builder $builder, int $minReviews): Builder
    {
        return $builder->having('reviews_count', '>=', $minReviews);
    }
    private function dateRangeFilter(Builder $builder, $from = null, $to = null)
    {
        if ($from && !$to) {
            $builder->where('created_at', '>=', $from);
        } elseif (!$from && $to) {
            $builder->where('created_at', '<=', $to);
        } elseif ($from && $to) {
            $builder->whereBetween('created_at', [$from, $to]);
        }
    }
    public function scopePopularLastMonth(Builder $builder): Builder
    {
        return $builder->popular(now()->subMonth(), now())
            ->highestRated(now()->subMonth(), now())
            ->minReviews(2);
    }
    public function scopePopularLast6Months(Builder $builder): Builder
    {
        return $builder->popular(now()->subMonths(6), now())
            ->highestRated(now()->subMonths(6), now())
            ->minReviews(5);
    }
    public function scopeHighestRatedlastMonth(Builder $builder): Builder
    {
        return $builder->highestRated(now()->subMonth(), now())
            ->popular(now()->subMonth(), now())->minReviews(2);
    }
    public function scopeHighestRatedlast6Months(Builder $builder): Builder
    {
        return $builder->highestRated(now()->subMonths(6), now())
            ->popular(now()->subMonths(6), now())->minReviews(5);
    }
}
