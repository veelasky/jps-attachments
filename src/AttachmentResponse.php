<?php


namespace Jalameta\Attachments;


use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        $file = Storage::disk($attachment->getAttribute('disk'))->get($attachment->getAttribute('path'));

        return $this->request->has('stream') && (bool) $this->request->input('stream') === true
            ? $this->makeStreamResponse($file, $attachment)
            : $this->makeDownloadResponse($file, $attachment);
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
     * Make stream response file.
     *
     * @param $file
     * @param Attachment $attachment
     * @return \Illuminate\Http\Response|mixed
     */
    public function makeStreamResponse($file, Attachment $attachment)
    {
        $response = $this->response->make($file, 200);
        $response->header('Content-Type', $attachment->getAttribute('mime'));

        return $response;
    }

    /**
     * Make download response.
     *
     * @param $file
     * @param Attachment $attachment
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function makeDownloadResponse($file, Attachment $attachment)
    {
        return $this->response->download($file, $attachment->getAttribute('title'));
    }
}
