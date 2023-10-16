<?php

namespace App\Entity;

class Fruit
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $family;

    /**
     * @var string
     */
    private $genus;

    /**
     * @var string
     */
    private $order;

    /**
     * @var bool
     */
    private $isFavorite;

    /**
     * @var Nutrition
     */
    private $nutritions;

    /**
     * @param int $id
     * @param string $name
     * @param string $family
     * @param string $genus
     * @param string $order
     * @param bool $isFavorite
     * @param Nutrition $nutritions
     */
    public function __construct(
        int $id,
        string $name,
        string $family,
        string $genus,
        string $order,
        bool $isFavorite,
        Nutrition $nutritions
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->family = $family;
        $this->genus = $genus;
        $this->order = $order;
        $this->isFavorite = $isFavorite;
        $this->nutritions = $nutritions;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getFamily(): string
    {
        return $this->family;
    }

    /**
     * @return string
     */
    public function getGenus(): string
    {
        return $this->genus;
    }

    /**
     * @return string
     */
    public function getOrder(): string
    {
        return $this->order;
    }

    /**
     * @return bool
     */
    public function isFavorite(): bool
    {
        return $this->isFavorite;
    }

    /**
     * @return Nutrition
     */
    public function getNutritions(): Nutrition
    {
        return $this->nutritions;
    }

    /**
     * @return array
     */
    public function __toArray(): array
    {
        return [
            'name' => $this->getName(),
            'family' => $this->getName(),
            'genus' => $this->getGenus(),
            'order' => $this->getOrder(),
            'nutritions' => $this->getNutritions(),
        ];
    }
}
