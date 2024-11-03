<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    use CrudTrait;
    //

    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'image',
        'status'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
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
