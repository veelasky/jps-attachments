<?php

namespace Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Jalameta\Attachments\Contracts\AttachableContract;

class Attachable extends Model implements AttachableContract
{
    use \Jalameta\Attachments\Concerns\Attachable;

    protected $table = 'attachable';

    protected static $unguarded = true;
}
