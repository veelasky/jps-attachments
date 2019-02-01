<?php
/**
 * @author muhajirin <muhajirinlpu@gmail.com>
 * at 2/1/2019 , 1:49 PM
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Disk used by JPS Attachment
    |--------------------------------------------------------------------------
    |
    | disk here is meaning for filesystem disk in laravel configuration
    | which is located in `config/filesystem`
    |
    */

    'disk' => 'local',

    /*
    |--------------------------------------------------------------------------
    | JPS Attachment model
    |--------------------------------------------------------------------------
    |
    | Define which model should be used by jps-attachment
    | you can custom your own attachment model and migration
    |
    */

    'model' => \Jalameta\Attachments\Entities\Attachment::class
];
