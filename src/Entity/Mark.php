<?php

declare(strict_types=1);

namespace Entity;

use App;
use PDO;

/**
 * Class Mark
 * @package Entity
 *
 * Active Record
 */
class Mark
{
    private const TABLE = 'mark';
    private int $id;

    public function __construct(private float $x, private float $y, private float $distanceToPrevPoint)
    {}

    public function save(): static
    {
        $sql = 'INSERT ' . self::TABLE . '(x, y, distanceToPrevPoint) VALUES (:x, :y, :distanceToPrevPoint)';
        App::getDb()->prepare($sql)->execute(
            [
                ':x' => $this->x,
                ':y' => $this->y,
                ':distanceToPrevPoint' => $this->distanceToPrevPoint
            ]
        );

        return $this;
    }

    public static function deleteAll(): void
    {
        App::getDB()->query('DELETE FROM ' . self::TABLE)->execute();
    }

    public static function getAll(): array
    {
        return App::getDB()->query('SELECT * FROM ' . self::TABLE, PDO::FETCH_ASSOC)->fetchAll();
    }

    public static function getAllDistances(): array
    {
        return App::getDB()->query('SELECT distanceToPrevPoint FROM ' . self::TABLE, PDO::FETCH_ASSOC)->fetchAll();
    }
}