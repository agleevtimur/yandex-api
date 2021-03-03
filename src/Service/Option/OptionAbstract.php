<?php

declare(strict_types=1);

namespace Service\Option;

use Entity\Mark;

/**
 * Class OptionAbstract
 * @package Service\Option
 *
 * Базовый класс, описывающий пункты статистики
 */
abstract class OptionAbstract
{
    private static array $distances;
    private static bool $isPulled = false;
    protected array|string $title;

    protected static function getAllDistances(): array
    {
        if (self::$isPulled) {
            return self::$distances;
        }

        self::$isPulled = true;
        self::$distances = array_column(Mark::getAllDistances(), 'distanceToPrevPoint');
        return self::$distances;
    }

    abstract function getValue(): array;
}