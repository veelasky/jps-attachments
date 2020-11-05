<?php

namespace Jalameta\Attachments\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

/**
 * @author muhajirin <muhajirinlpu@gmail.com>
 * at 2/1/2019 , 2:32 PM
 */
class AttachmentController
{
    /**
     * File Http Proxy
     * Route Path       : /file/{attachment}
     * Route Name       : file
     * Route Method     : GET.
     *
     * @param $attachment
     * @param Request $request
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException|\Illuminate\Contracts\Container\BindingResolutionException
     */
    public function file($attachment, Request $request)
    {
        $attachment = $this->getAttachment($attachment);

        $file = Storage::disk($attachment->getAttribute('disk'))->get($attachment->getAttribute('path'));

        if ($request->has('stream') && (bool) $request->input('stream') === true) {
            $response = response()->make($file, 200);
            $response->header('Content-Type', $attachment->getAttribute('mime'));

            return $response;
        }

        return response()->download($file, $attachment->getAttribute('title'));
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
