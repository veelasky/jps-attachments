<?php

namespace Jalameta\Attachments;

use Illuminate\Routing\Router;

/**
 * JPS Attachment
 *
 * @author      veelasky <veelasky@gmail.com>
 */
class JPSAttachment
{
    /**
     * Indicates if attachment migrations will be run.
     *
     * @var bool
     */
    public static $runMigrations = true;

    public static function ignoreMigrations()
    {
        static::$runMigrations = false;

        return new static;
    }

    public static function getModel() :string
    {
        return config('attachment.model');
    }

    public static function routes($callback = null, $options = [])
    {
        $callback = $callback ?: function (Router $router) {
            $router->get('file/{attachment}', 'AttachmentController@file')->name('attachment');
        };

        $defaultOptions = [
            'namespace' => '\Jalameta\Attachments\Controllers'
        ];

        $options = array_merge($defaultOptions, $options);

        app('router')->group($options, $callback);
    }
}
