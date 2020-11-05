<?php


namespace Jalameta\Attachments\Concerns;


use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Jalameta\Attachments\Entities\Attachment;

trait AttachmentCreator
{

    /**
     * Create any attachment
     *
     * @param UploadedFile $file
     * @param array $attributes
     * @return Attachment
     */
    public function create(UploadedFile $file, array $attributes)
    {
        $attachment = new Attachment();

        $disk = Arr::get($attributes, 'disk');

        $attributes = Arr::add($attributes, 'path', $this->store($file, $disk));
        $attributes = Arr::add($attributes, 'mime', $file->getMimeType());

        $attachment->fill(
            Arr::only($attributes, ['title', 'mime', 'type', 'path', 'disk', 'options', 'description'])
        );

        $attachment->save();

        return $attachment;
    }

    /**
     * Store file into disk
     *
     * @param UploadedFile $file
     * @param null $disk
     * @return false|string
     */
    public function store(UploadedFile $file, $disk = null)
    {
        return $file->storeAs('/', Str::uuid(), [
            'disk' => $disk ?? config('attachment.disk')
        ]);
    }

    /**
     * Create attachment from file that have been stored in filesystems.
     *
     * @param string $path
     * @param array $attributes
     * @param null $disk
     * @return Attachment
     */
    public function createFromPath(string $path, array $attributes, $disk = null)
    {
        $attachment = new Attachment();

        $attributes = Arr::add($attributes, 'disk', $disk ?? config('attachment.disk'));
        $attributes = Arr::add($attributes, 'path', $path);

        $file = Storage::disk(Arr::get($attributes, 'disk'))->mimeType(Arr::get($attributes, 'path'));

        $attributes = Arr::add($attributes, 'mime', $file);

        $attachment->fill(
            Arr::only($attributes, ['title', 'mime', 'type', 'path', 'disk', 'options', 'description'])
        );

        $attachment->save();

        return $attachment;
    }

}
