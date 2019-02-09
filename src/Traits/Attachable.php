<?php

namespace Jalameta\Attachments\Traits;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @author muhaj <muhajirinlpu@gmail.com>
 * at 2/9/2019 , 9:15 PM
 */
trait Attachable
{
    public function attachments(): MorphToMany
    {
        $attachment_class = config('attachment.model');

        return $this->morphToMany($attachment_class, 'attachable');
    }
}
