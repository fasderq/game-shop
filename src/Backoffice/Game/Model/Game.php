<?php
namespace GameShop\Site\Backoffice\Game\Model;

/**
 * Class Game
 * @package GameShop\Site\Backoffice\Game\Model
 */
class Game
{
    protected $name;
    protected $description;
    protected $isActive;

    /**
     * Game constructor.
     * @param string $name
     * @param null|string $description
     * @param bool $isActive
     */
    public function __construct(
        string $name,
        ?string $description = null,
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
