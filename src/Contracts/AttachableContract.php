<?php

namespace Jalameta\Attachments\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

interface AttachableContract
{
    /**
     * @return MorphToMany
     */
    public function attachments(): MorphToMany;
}
