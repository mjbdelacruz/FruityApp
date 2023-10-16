<?php

namespace App\Service;

use App\Entity\Fruit;
use App\Exception\EntityLimitException;
use App\Repository\FruitRepository;
use Doctrine\DBAL\Exception;

class FruitService
{
    private $fruitRepository;

    /**
     * @param FruitRepository $fruitRepository
     */
    public function __construct(FruitRepository $fruitRepository)
    {
        $this->fruitRepository = $fruitRepository;
    }

    /**
     * @param int $fruitId
     * @return Fruit
     */
    public function getFruit(int $fruitId): Fruit
    {
        return $this->fruitRepository->getFruit($fruitId);
    }

    /**
     * @param bool $showFavorites
     * @return array
     */
    public function getFruits(bool $showFavorites = false): array
    {
        return $this->fruitRepository->getFruits($showFavorites);
    }

    /**
     * @param Fruit $fruit
     * @return void
     * @throws Exception
     */
    public function addToFavorite(Fruit $fruit): void
    {
        $favoriteCount = $this->fruitRepository->getFavoritesCount();
        if ($favoriteCount < 10) {
            $this->fruitRepository->addToFavorites($fruit);
        } else {
            throw new EntityLimitException('Add to favorites failed. Number of favorites has reached its limit.');
        }
    }
}