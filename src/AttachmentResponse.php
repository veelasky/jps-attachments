<?php

namespace Jalameta\Attachments;

use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Jalameta\Attachments\Entities\Attachment;

class AttachmentResponse
{
    /**
     * Http Request instance.
     *
     * @var Request
     */
    public $request;

    /**
     * Filesystem singleton instance.
     *
     * @var Factory
     */
    public $filesystem;

    /**
     * Response singleton instance.
     *
     * @var ResponseFactory;
     */
    public $response;

    /**
     * AttachmentResponse constructor.
     * @param Request $request
     * @param Factory $filesystem
     * @param ResponseFactory $response
     */
    public function __construct(Request $request, Factory $filesystem, ResponseFactory $response)
    {
        $this->request = $request;
        $this->filesystem = $filesystem;
        $this->response = $response;
    }

    /**
     * Stream or download response. Give stream response if stream request query provided.
     *
     * @param Attachment $attachment
     * @return \Illuminate\Http\Response|mixed|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function streamOrDownload(Attachment $attachment)
    {
        return $this->request->has('stream') && (bool) $this->request->input('stream') === true
            ? $this->stream($attachment)
            : $this->download($attachment);
    }

    /**
     * Find attachment file in filesystem.
     *
     * @param Attachment $attachment
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getAttachmentFile(Attachment $attachment)
    {
        return $this->filesystem->disk($attachment->getAttribute('disk'))->get($attachment->getAttribute('path'));
    }

    /**
     * Get attachment file path
     *
     * @param Attachment $attachment
     * @return string
     */
    public function getAttachmentFilePath(Attachment $attachment)
    {
        $basePath = config('filesystems.disks.'. $attachment->getAttribute('disk') .'.root');
        return $basePath . "/" . $attachment->getAttribute('path');
    }

    /**
     * Make stream response file.
     *
     * @param Attachment $attachment
     * @return \Illuminate\Http\Response|mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function stream(Attachment $attachment)
    {
        $file = $this->getAttachmentFile($attachment);

        $response = $this->response->make($file, 200);
        $response->header('Content-Type', $attachment->getAttribute('mime'));

        return $response;
    }

    /**
     * Make download response.
     *
     * @param Attachment $attachment
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(Attachment $attachment)
    {
        $path = $this->getAttachmentFilePath($attachment);
        return $this->response->download($path, $attachment->getAttribute('title'));
    }
}
