<?php

namespace App\Entity;

class Nutrition
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var float
     */
    private $carbohydrates;

    /**
     * @var float
     */
    private $protein;

    /**
     * @var float
     */
    private $fat;

    /**
     * @var int
     */
    private $calories;

    /**
     * @var float
     */
    private $sugar;

    /**
     * @param int|null $id
     * @param float $carbohydrates
     * @param float $protein
     * @param float $fat
     * @param int $calories
     * @param float $sugar
     */
    public function __construct(
        ?int $id,
        float $carbohydrates,
        float $protein,
        float $fat,
        int $calories,
        float $sugar
    ) {
        $this->id = $id;
        $this->carbohydrates = $carbohydrates;
        $this->protein = $protein;
        $this->fat = $fat;
        $this->calories = $calories;
        $this->sugar = $sugar;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getCarbohydrates(): float
    {
        return $this->carbohydrates;
    }

    /**
     * @return float
     */
    public function getProtein(): float
    {
        return $this->protein;
    }

    /**
     * @return float
     */
    public function getFat(): float
    {
        return $this->fat;
    }

    /**
     * @return int
     */
    public function getCalories(): int
    {
        return $this->calories;
    }

    /**
     * @return float
     */
    public function getSugar(): float
    {
        return $this->sugar;
    }

    /**
     * @return array
     */
    public function __toArray(): array
    {
        return [
            'carbohydrates' => $this->getCarbohydrates(),
            'protein' => $this->getProtein(),
            'fat' => $this->getFat(),
            'calories' => $this->getCalories(),
            'sugar' => $this->getSugar(),
        ];
    }
}
