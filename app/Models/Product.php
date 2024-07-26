<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Tags\HasTags;

class Product extends Model
{
    use HasFactory,HasTags;


    protected $fillable = ['name', 'price', 'status','category_id','tags'];


    public function category() : BelongsTo{

        return $this->belongsTo(Category::class);

    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

}
