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
        $attributes['path'] = $this->storeFile($file, $attributes['disk'] ?? config('attachment.disk'));
        $attributes['mime'] = $this->getUploadedFileMime($file);

        return $this->save($attributes);
    }

    /**
     * Store file into disk
     *
     * @param UploadedFile $file
     * @param string $disk
     * @return false|string
     */
    public function storeFile(UploadedFile $file, string $disk)
    {
        return $file->storeAs('/', Str::uuid(), [
            'disk' => $disk
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
        $attributes['disk'] = $disk ?? config('attachment.disk');
        $attributes['path'] = $path;
        $attributes['mime'] = $this->getStorageFileMime($attributes['disk'], $attributes['path']);

        return $this->save($attributes);
    }

    /**
     * Get file mimetype that retrieved from laravel uploaded file instance.
     *
     * @param UploadedFile $file
     * @return string|null
     */
    private function getUploadedFileMime(UploadedFile $file)
    {
        return $file->getMimeType();
    }

    /**
     * Get file mimetype that retrieved from specific filesystem.
     *
     * @param string $disk
     * @param string $path
     * @return mixed
     */
    private function getStorageFileMime(string $disk, string $path)
    {
        return Storage::disk($disk)->mimeType($path);
    }

    /**
     * Save attachment into database.
     *
     * @param array $attributes
     * @return Attachment
     */
    private function save(array $attributes)
    {
        $attachment = new Attachment();

        $attachment->fill(
            Arr::only($attributes, ['title', 'mime', 'type', 'path', 'disk', 'options', 'description'])
        );

        $attachment->save();

        return $attachment;
    }

}
