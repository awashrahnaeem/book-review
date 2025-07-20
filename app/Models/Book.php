<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Book extends Model
{
    use HasFactory;
    function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function scopeTitle(Builder $query, $title)
    {
        return $query->where('title', 'like', '%' . $title . '%');
    }
    public function scopeWithReviewsCount(Builder $query, $from = null, $to = null): Builder
    {
        return $query->withCount(['reviews' => fn(Builder $q) => $this->dateRangeFilter($q, $from, $to)])
            ->having('reviews_count', '>', 0);
    }
    public function scopePopular(Builder $query, $from = null, $to = null)
    {
        //()
        return $query->withReviewsCount($from, $to)
            ->orderBy('reviews_count', 'desc');

    }
        public function scopeWithAvgRating(Builder $query, $from = null, $to = null)
    {
        
        return $query->withAvg(['reviews' => fn(Builder $q) => $this->dateRangeFilter($q, $from, $to)], 'rating'); 

    }

    public function scopeHighestRated(Builder $query, $from = null, $to = null)
    {
        return $query->withAvgRating()
            ->orderBy('reviews_avg_rating', 'desc');
    }

    private function dateRangeFilter(Builder $q, $from = null, $to = null)
    {

        if ($from && $to) {
            $q->whereBetween('created_at', [$from, $to]);
        } elseif ($from) {
            $q->where('created_at', '>=', $from);
        } elseif ($to) {
            $q->where('created_at', '<=', $to);
        }

    }
    public function scopeMinReviews(Builder $query, int $minReviews): Builder
    {

        return $query->having('reviews_count', '>=', $minReviews);
    }

    public function scopePopularLastMonth(Builder $query): Builder
    {
        return $query->popular(now()->subMonth(), now())
            ->highestRated(now()->subMonth(), now())
            ->minReviews(2);
    }
    public function scopePopularLast6Months(Builder $query): Builder
    {
        return $query->popular(now()->subMonths(6), now())
            ->highestRated(now()->subMonths(6), now())
            ->minReviews(5);
    }
    public function scopeHighestRatedLastMonth(Builder $query): Builder
    {
        return $query->highestRated(now()->subMonth(), now())
            ->popular(now()->subMonth(), now())
            ->minReviews(2);
    }
    public function scopeHighestRatedLast6Months(Builder $query): Builder
    {
        return $query->highestRated(now()->subMonths(6), now())
            ->popular(now()->subMonths(6), now())
            ->minReviews(5);
    }
        protected static function booted()
    {
        static::updated(fn(Book $book) => Cache::forget('book:' . $book->id));
        static::deleted(fn(Book $book) => Cache::forget('book:' . $book->id));

    }

    //
}
