<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Review extends Model
{
    use HasFactory;
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
    protected $fillable = ['review', 'rating'];
    protected static function booted()
    {
        static::updated(function (Review $review) {
            Log::info('Review updated, clearing cache for book: ' . $review->book_id);
            cache()->forget('book:' . $review->book_id);
        });

        static::deleted(function (Review $review) {
            Log::info('Review deleted, clearing cache for book: ' . $review->book_id);
            cache()->forget('book:' . $review->book_id);
        });
        static::created(fn (Review $review) => cache()->forget('book:' . $review->book_id));
    }
}
