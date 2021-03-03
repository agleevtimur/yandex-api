<?php

declare(strict_types=1);

namespace Service\Option;

class OptionDistanceAverage extends OptionAbstract
{
    protected array|string $title = 'среднее расстояние';

    function getValue(): array
    {
        $distances = parent::getAllDistances();
        if (count($distances) === 1) {
            return [$this->title => 0];
        }

        $result = 0;
        foreach ($distances as $distance) {
            $result += $distance;
        }

        return [$this->title => round($result / (count($distances) - 1), 4)];
    }
}