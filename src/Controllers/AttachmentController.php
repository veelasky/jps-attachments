<?php

namespace Jalameta\Attachments\Controllers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Jalameta\Attachments\Entities\Attachment;

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
     * @param Attachment $attachment
     * @param Filesystem $filesystem
     * @param Request $request
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function file(Attachment $attachment, Filesystem $filesystem, Request $request)
    {
        $path = config('filesystems.disks.'. config('attachment.disk') .'.root');

        if ($request->has('stream') && (bool) $request->input('stream') === true) {
            $file = $filesystem->get($path.'/'.$attachment->path);

            $response = response()->make($file, 200);
            $response->header('Content-Type', $attachment->mime);

            return $response;
        }

        return response()->download($path.'/'.$attachment->path, $attachment->title);
    }
}