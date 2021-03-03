<?php

declare(strict_types=1);

namespace Service;

use Service\Option\OptionDistanceAverage;
use Service\Option\OptionRouteCount;
use Service\Option\OptionRouteRangeCount;

class Statistics
{
    private const OPTIONS = [
        OptionRouteCount::class => true,
        OptionDistanceAverage::class => true,
        OptionRouteRangeCount::class => true
    ];

    /**
     * @return array в формате ['@title' => '@value'], например ['количество маршрутов' => 10]
     */
    public static function get(): array
    {
        $option = [];

        foreach (self::OPTIONS as $optionClass => $isActive) {
            if ($isActive) {
                $value = (new $optionClass())->getValue();
                foreach ($value as $name => $item) {
                    $option[$name] = $item;
                }
            }
        }

        return $option;
    }
}