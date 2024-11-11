<?php

namespace App\Models;

use App\Policies\ProductPolicy;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    use CrudTrait;
    use HasFactory;
    //

    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'image',
        'status'
    ];

    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('dashboard_stats');
        });

        static::deleted(function () {
            Cache::forget('dashboard_stats');
        });
    }


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setImageAttribute($value)
    {
        $attributeName = "image";
        $disk = "public";
        $destinationPath = "products";

        if (request()->hasFile($attributeName)) {
            $filename = request()->file($attributeName)->store($destinationPath, $disk);

            if (!empty($this->{$attributeName})) {
                Storage::disk($disk)->delete($this->{$attributeName});
            }

            $this->attributes[$attributeName] = Str::after($filename, 'public/');
        } elseif (is_null($value)) {
            if (!empty($this->{$attributeName})) {
                Storage::disk($disk)->delete($this->{$attributeName});
            }
            $this->attributes[$attributeName] = null;
        }
    }
}
