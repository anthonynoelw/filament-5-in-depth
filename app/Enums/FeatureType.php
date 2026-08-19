<?php

namespace App\Enums;

use App\Enums\Traits\UseValueAsLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;

enum FeatureType : string implements HasIcon, HasColor
{
    use UseValueAsLabel;

    case Feature = 'Feature';
    case Bug = 'Bug';

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Feature => 'heroicon-o-star',
            self::Bug => 'heroicon-o-bug-ant',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Feature => 'primary',
            self::Bug => 'danger',
        };
    }
}

