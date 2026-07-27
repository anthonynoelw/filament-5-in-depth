<?php

namespace App\Enums\Traits;

use BackedEnum;
use Illuminate\Contracts\Support\Htmlable;

/**
 * @mixin BackedEnum
 */
trait UseValueAsLabel
{
    public function getLabel(): string|Htmlable|null
    {
        return $this->value;
    }
}