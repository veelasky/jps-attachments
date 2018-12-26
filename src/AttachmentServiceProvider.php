<?php

namespace Jalameta\Attachments;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

/**
 * JPS Attachment service provider
 *
 * @author      veelasky <veelasky@gmail.com>
 */
class AttachmentServiceProvider extends LaravelServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        if (Attachment::$runMigrations)
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
