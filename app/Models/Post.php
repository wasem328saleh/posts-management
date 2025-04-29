<?php

namespace App\Models;

use App\Events\PostCache;
use App\Events\PostCreated;
use App\Mail\PostCreatedNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Post extends Model
{
    use HasFactory;
    protected $table = 'posts';
    protected $guarded = [];

    protected array $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * Get the value of an attribute using its mutator or by directly accessing it.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        // Check if an accessor method exists for the attribute
        $accessor = 'get' . ucfirst($key) . 'Attribute';

        if (method_exists($this, $accessor)) {
            return $this->{$accessor}();
        }

        // If no accessor method exists, get the attribute directly
        return parent::getAttribute($key);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function images(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function scopeHasImages(): bool
    {

        return $this->images()->count() > 0;
    }

    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comment::class, 'post_id');
    }

    protected static function booted()
    {
        parent::booted();
        static::created(function ($post) {
            event(new PostCreated($post));
            event(new PostCache($post));
        });
        static::updated(function ($post) {
            event(new PostCache($post));
        });
        static::deleted(function ($post) {
            event(new PostCache($post));
        });
    }
}
