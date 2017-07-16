<?php
namespace GameShop\Site\Backoffice\GameGenre\Model;

/**
 * Class GameGenre
 * @package GameShop\Site\Backoffice\GameGenre\Model
 */
class GameGenre
{
    protected $name;
    protected $description;
    protected $isActive;

    /**
     * GameGenre constructor.
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
