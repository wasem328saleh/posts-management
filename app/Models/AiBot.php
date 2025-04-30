<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiBot extends Model
{
    use HasFactory;
    protected $table = 'ai_bots';

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

    public function replies(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ReplyComment::class, 'bot_id');
    }

    public function image_profile(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}
