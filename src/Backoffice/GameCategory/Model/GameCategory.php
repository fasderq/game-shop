<?php
namespace GameShop\Site\Backoffice\GameCategory\Model;

/**
 * Class GameCategory
 * @package GameShop\Site\Backoffice\GameCategory\Model
 */
class GameCategory
{
    protected $name;
    protected $description;
    protected $isActive;

    /**
     * GameCategory constructor.
     * @param string $name
     * @param null|string $description
     * @param bool $isActive
     */
    public function __construct(
        string $name,
        ?string $description,
        bool $isActive = true
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->isActive = $isActive;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }
}
