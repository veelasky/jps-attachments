<?php

namespace Jalameta\Attachments\Concerns;

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

        return $this->morphToMany($attachment_class, 'attachable', 'attachable', 'attachable_id');
    }
}
