<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class File extends Model
{

    use SoftDeletes;

    const TYPE_PROFILE_PIC = 'pp';
    const TYPE_ID = 'id-card';

    public $incrementing = false;
    protected $fillable = ['url', 'type', 'thumbnail'];
    protected $visible = ['id', 'url', 'created_at'];

    protected static function boot()
    {
        static::creating(function ($model) {
            $model->id = Str::orderedUuid();
            return true;
        });

        parent::boot();
    }

    /**
     * @return MorphTo
     */
    public function fileable(): MorphTo
    {
        return $this->morphTo('fileable');
    }

    public function forceDelete(): ?bool
    {
        if (Storage::exists($this->url))
            Storage::delete($this->url);

        return parent::forceDelete();
    }

    public function getURL(): ?string
    {
        if (Str::startsWith($this->url, 'http')) {
            return $this->url;
        } else {
            return $this->path ? Storage::url($this->path) : null;
        }
    }

}
