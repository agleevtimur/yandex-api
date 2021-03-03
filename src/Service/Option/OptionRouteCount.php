<?php

declare(strict_types=1);

namespace Service\Option;

class OptionRouteCount extends OptionAbstract
{
    protected array|string $title = 'количество маршрутов';

    function getValue(): array
    {
        return [$this->title => count(array_filter(parent::getAllDistances(), fn($distance) => $distance > 0))];
    }
}