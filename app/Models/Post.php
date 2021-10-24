<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;

class Post extends Model
{
    use HasFactory;
    use HasEagerLimit;
    use HasUuid;

    protected $primaryKey = 'uuid';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $guarded = [];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(PostLike::class, 'post_id');
    }

    public function reacters(): HasManyThrough
    {
        return $this->hasManyThrough(
            User::class,
            PostLike::class,
            'post_id',
            'uuid',
            'uuid',
            'user_id'
        );
    }

    public function scopeWithLastReacters(Builder $query, int $length): Builder
    {
        return $query->with(
            'reacters',
            fn($query) => $query->latest('post_likes.created_at')->limit($length)
        );
    }

    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image);
    }
}
