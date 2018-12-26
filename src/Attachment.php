<?php

namespace Jalameta\Attachments;

/**
 * JPS Attachment
 *
 * @author      veelasky <veelasky@gmail.com>
 */
class Attachment
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
}
