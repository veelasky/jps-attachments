<?php

namespace Jalameta\Attachments\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @author muhaj <muhajirinlpu@gmail.com>
 * at 2/9/2019 , 9:13 PM
 */
interface AttachableContract
{
    /**
     * @return MorphToMany
     */
    public function attachments(): MorphToMany;
}
