<?php
namespace GameShop\Site\Backoffice\Game\Model;

/**
 * Class Game
 * @package GameShop\Site\Backoffice\Game\Model
 */
class Game
{
    protected $name;
    protected $category;
    protected $genre;
    protected $price;
    protected $specialOffer;
    protected $requiredAge;
    protected $isActive;

    /**
     * Game constructor.
     * @param string $name
     * @param string $category
     * @param string $genre
     * @param int $price
     * @param bool $specialOffer
     * @param int|null $requiredAge
     * @param bool $isActive
     */
    public function __construct(
        string $name,
        string $category,
        string $genre,
        int $price,
        bool $specialOffer = false,
        ?int $requiredAge = null,
        bool $isActive = true
    ) {
        $this->name = $name;
        $this->category =  $category;
        $this->genre =  $genre;
        $this->price = $price;
        $this->specialOffer = $specialOffer;
        $this->requiredAge = $requiredAge;
        $this->isActive =  $isActive;
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
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @return string
     */
    public function getGenre(): string
    {
        return $this->genre;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @return bool
     */
    public function getSpecialOffer(): bool
    {
        return $this->specialOffer;
    }

    /**
     * @return int|null
     */
    public function getRequiredAge(): ?int
    {
        return $this->requiredAge;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }
}
