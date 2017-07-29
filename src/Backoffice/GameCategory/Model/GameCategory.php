<?php
namespace GameShop\Site\Backoffice\GameCategory\Model;

/**
 * Class GameCategory
 * @package GameShop\Site\Backoffice\GameCategory\Model
 */
class GameCategory
{
    protected $name;
    protected $isActive;

    /**
     * GameCategory constructor.
     * @param string $name
     * @param bool $isActive
     */
    public function __construct(
        string $name,
        bool $isActive = true
    ) {
        $this->name = $name;
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
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }
}
