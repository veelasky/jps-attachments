<?php


namespace Jalameta\Attachments\Concerns;


use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
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
    public function create(UploadedFile $file, array $attributes = [])
    {
        $attachment = new Attachment();

        $attributes = Arr::add($attributes, 'path', $this->store($file));
        $attributes = Arr::add($attributes, 'mime', $file->getMimeType());

        $attachment->fill(
            Arr::only($attributes, ['title', 'mime', 'type', 'options', 'description'])
        );

        $attachment->save();

        return $attachment;
    }

    /**
     * Store file into disk
     *
     * @param UploadedFile $file
     * @return false|string
     */
    public function store(UploadedFile $file)
    {
        return $file->storeAs('/', Str::uuid(), [
            'disk' => config('attachment.disk')
        ]);
    }

}
