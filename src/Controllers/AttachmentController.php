<?php

namespace Jalameta\Attachments\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Jalameta\Attachments\AttachmentResponse;
use Jalameta\Attachments\Entities\Attachment;

/**
 * @author muhajirin <muhajirinlpu@gmail.com>
 * at 2/1/2019 , 2:32 PM
 */
class AttachmentController
{
    /**
     * Attachment response maker instance.
     *
     * @var AttachmentResponse
     */
    public $response;

    public function __construct(AttachmentResponse $response)
    {
        $this->response = $response;
    }

    /**
     * File Http Proxy
     * Route Path       : /file/{attachment}
     * Route Name       : file
     * Route Method     : GET.
     *
     * @param $attachment
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function file($attachment)
    {
        /** @var Attachment $attachment */
        $attachment = $this->getAttachment($attachment);

        return $this->response->streamOrDownload($attachment);
    }

    /**
     * @param $attachment
     * @return \Illuminate\Database\Eloquent\Collection|Model|null
     */
    public function getAttachment($attachment)
    {
        /*** @var $class Model */
        $class = config('attachment.model');

        return $class::query()->find($attachment);
    }
}
