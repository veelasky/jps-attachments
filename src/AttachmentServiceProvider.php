<?php

namespace Jalameta\Attachments;

use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
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
            if (JPSAttachment::$runMigrations)
                $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

            $this->registerPublishes();
        }

        $this->mergeConfigFrom(__DIR__ . '/../config/attachment.php', 'attachment');

        $this->app->bind(AttachmentResponse::class, function (Application $application) {
            return new AttachmentResponse(
                $application->make(Request::class),
                $application->make(Factory::class),
                $application->make(ResponseFactory::class)
            );
        });
    }

    public function registerPublishes()
    {
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations')
        ], 'migrations');

        $this->publishes([
            __DIR__ . '/../config/' => config_path()
        ], 'config');
    }
}
