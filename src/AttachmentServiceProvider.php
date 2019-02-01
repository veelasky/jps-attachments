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
        if ($this->app->runningInConsole()) {
            if (Attachment::$runMigrations)
                $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

            $this->publishes([
                __DIR__ . '../database/migrations' => database_path('migrations')
            ], 'jps-attachment-migration');
        }
    }
}
