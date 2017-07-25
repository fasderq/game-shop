<?php
namespace GameShop\Site\Frontoffice\Game\Model;

/**
 * Class Game
 * @package GameShop\Site\Frontoffice\Game\Model
 */
class Game
{
    protected $id;
    protected $name;
    protected $price;
    protected $requiredAge;

    /**
     * Game constructor.
     * @param int $id
     * @param string $name
     * @param int $price
     * @param int|null $requiredAge
     */
    public function __construct(
        int $id,
        string $name,
        int $price,
        ?int $requiredAge = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->requiredAge = $requiredAge;
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
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @return int|null
     */
    public function getRequiredAge(): ?int
    {
        return $this->requiredAge;
    }
}
