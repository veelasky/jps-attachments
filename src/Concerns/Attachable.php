<?php

namespace Jalameta\Attachments\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait Attachable
{
    public function attachments(): MorphToMany
    {
        $attachment_class = config('attachment.model');

        return $this->morphToMany($attachment_class, 'attachable', 'attachable', 'attachable_id');
    }
}
