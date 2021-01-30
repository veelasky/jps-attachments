<?php

namespace Jalameta\Attachments\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Attachment extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'attachments';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'mime', 'path', 'type', 'description', 'options'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'options' => 'array'
    ];

    /**
     * Override the disk attribute
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    public function getDiskAttribute()
    {
        return $this->getOriginal('disk') ?? config('attachment.disk');
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $attachment) {
            $attachment->setAttribute($attachment->getKeyName(), (string) Str::orderedUuid()->toString());
        });

        static::registerModelEvent('forceDeleted', function (self $attachment) {
            Storage::disk($attachment->getAttribute('disk'))->delete($attachment->getAttribute('path'));
        });
    }
}
