<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait InteractWithUserAttributes
{
    public function name(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->last_name.' '.$this->first_name,
        );
    }
}
