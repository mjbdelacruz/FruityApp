<?php

namespace App\Repository;

use App\Entity\Fruit;
use App\Entity\Nutrition;
use Doctrine\DBAL\Exception as DoctrineException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

class FruitRepository extends BaseRepository
{
    /**
     * @var string
     */
    public static $tableName = 'Fruits';

    /**
     * @var NutritionRepository
     */
    private $nutritionRepository;

    /**
     * @param ManagerRegistry $masterRegistry
     * @param NutritionRepository $nutritionRepository
     */
    public function __construct(ManagerRegistry $masterRegistry, NutritionRepository $nutritionRepository)
    {
        parent::__construct($masterRegistry);
        $this->nutritionRepository = $nutritionRepository;
    }

    /**
     * @param Fruit $fruit
     * @return false|int|string
     * @throws DoctrineException
     */
    public function save(Fruit $fruit)
    {
        $connection = $this->getWriteConnection();
        try {
            $connection->insert(self::$tableName, [
                '`id`' => $fruit->getId(),
                '`name`' => $fruit->getName(),
                '`family`' => $fruit->getFamily(),
                '`genus`' => $fruit->getGenus(),
                '`order`' => $fruit->getOrder(),
            ]);

            $this->nutritionRepository->save($fruit->getId(), $fruit->getNutritions());

            return $connection->lastInsertId();
        } catch (DoctrineException|\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param int $id
     * @return Fruit
     * @throws DoctrineException
     * @throws NoResultException
     */
    public function getFruit(int $id): Fruit
    {
        $connection = $this->getReadConnection();
        $queryBuilder = $connection->createQueryBuilder();

        $queryBuilder->select('f.id AS f_id, f.name AS f_name, f.family AS f_family, f.genus AS f_genus, f.order AS f_order, f.isfavorite AS f_isfavorite,
            n.id AS n_id, n.carbohydrates AS n_carbohydrates, n.protein AS n_protein, n.fat AS n_fat, n.calories AS n_calories, n.sugar AS n_sugar')
            ->from(self::$tableName, 'f')
            ->innerJoin('f', NutritionRepository::$tableName, 'n', 'f.id = n.fruit')
            ->where('f.id = :id')
            ->setParameter('id', $id);

        $result = $this->fetchOne($queryBuilder);

        return $this->createFromArray($result);
    }

    /**
     * @param bool $showFavorites
     * @return array
     * @throws DoctrineException
     */
    public function getFruits(bool $showFavorites = false): array
    {
        $connection = $this->getReadConnection();
        $queryBuilder = $connection->createQueryBuilder();

        $queryBuilder->select('f.id AS f_id, f.name AS f_name, f.family AS f_family, f.genus AS f_genus, f.order AS f_order, f.isfavorite AS f_isfavorite,
            n.id AS n_id, n.carbohydrates AS n_carbohydrates, n.protein AS n_protein, n.fat AS n_fat, n.calories AS n_calories, n.sugar AS n_sugar')
            ->from(self::$tableName, 'f')
            ->innerJoin('f', NutritionRepository::$tableName, 'n', 'f.id = n.fruit');

        if ($showFavorites) {
            $queryBuilder->where('isfavorite = 1');
        }

        $queryBuilder->orderBy('f.id', 'ASC');

        $fruits = [];
        $results = $this->fetchAll($queryBuilder);
        foreach ($results as $result) {
            $fruits[] = $this->createFromArray($result);
        }

        return $fruits;
    }

    /**
     * @param Fruit $fruit
     * @return void
     * @throws DoctrineException
     */
    public function addToFavorites(Fruit $fruit): void
    {
        $connection = $this->getWriteConnection();
        try {
            $connection->update(self::$tableName, ['isfavorite' => 1], ['id' => $fruit->getId()]);
        } catch (DoctrineException|\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return mixed
     * @throws DoctrineException
     * @throws NoResultException
     */
    public function getFavoritesCount()
    {
        $connection = $this->getReadConnection();
        $queryBuilder = $connection->createQueryBuilder();

        $queryBuilder->select('COUNT(id) AS count')
            ->from(self::$tableName)
            ->where('isfavorite = 1');

        $result = $this->fetchOne($queryBuilder);

        return $result['count'];
    }

    /**
     * @param array $data
     * @return Fruit
     */
    public function createFromArray(array $data): Fruit
    {
        $nutrition = new Nutrition(
            $data['n_id'],
            $data['n_carbohydrates'],
            $data['n_protein'],
            $data['n_fat'],
            $data['n_calories'],
            $data['n_sugar']
        );

        return new Fruit(
            $data['f_id'],
            $data['f_name'],
            $data['f_family'],
            $data['f_genus'],
            $data['f_order'],
            $data['f_isfavorite'],
            $nutrition
        );
    }
}
