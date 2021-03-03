<?php

declare(strict_types=1);

namespace Service\Option;

class OptionRouteRangeCount extends OptionAbstract
{
    protected string|array $title = [
        'количество маршрутов до 2км',
        'количество маршрутов от 2 до 5км',
        'количество маршрутов от 5км'
    ];

    function getValue(): array
    {
        $value = [];
        foreach ($this->title as $key => $title) {
            $value[$title] = match ($key) {
                0 => count(array_filter(parent::getAllDistances(), fn($distance) => $distance < 2000 && $distance > 0)),
                1 => count(array_filter(parent::getAllDistances(), fn($distance) => $distance > 2000 && $distance < 5000)),
                2 => count(array_filter(parent::getAllDistances(), fn($distance) => $distance > 5000))
            };
        }

        return $value;
    }
}