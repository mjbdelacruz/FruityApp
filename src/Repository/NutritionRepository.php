<?php

namespace App\Repository;

use App\Entity\Nutrition;
use Doctrine\DBAL\Exception as DoctrineException;
use Doctrine\Persistence\ManagerRegistry;

class NutritionRepository extends BaseRepository
{
    /**
     * @var string
     */
    public static $tableName = 'Nutritions';

    /**
     * @param ManagerRegistry $masterRegistry
     */
    public function __construct(ManagerRegistry $masterRegistry)
    {
        parent::__construct($masterRegistry);
    }

    /**
     * @param int $fruitId
     * @param Nutrition $nutrition
     * @return false|int|string
     * @throws DoctrineException
     */
    public function save(int $fruitId, Nutrition $nutrition)
    {
        $connection = $this->getWriteConnection();
        try {
            $connection->insert(self::$tableName, [
                '`carbohydrates`' => $nutrition->getCarbohydrates(),
                '`protein`' => $nutrition->getProtein(),
                '`fat`' => $nutrition->getFat(),
                '`calories`' => $nutrition->getCalories(),
                '`sugar`' => $nutrition->getSugar(),
                '`fruit`' => $fruitId,
            ]);

            return $connection->lastInsertId();
        } catch (DoctrineException|\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param array $data
     * @return Nutrition
     */
    public function createFromArray(array $data): Nutrition
    {
        return new Nutrition(
            $data['id'],
            $data['carbohydrates'],
            $data['protein'],
            $data['fat'],
            $data['calories'],
            $data['sugar']
        );
    }
}
