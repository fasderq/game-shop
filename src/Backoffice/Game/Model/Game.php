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
    protected $gameInfo;
    protected $gameFeature;

    /**
     * Game constructor.
     * @param string $name
     * @param null|string $description
     * @param bool $isActive
     * @param GameInfo|null $gameInfo
     * @param GameFeature|null $gameFeature
     */
    public function __construct(
        string $name,
        ?string $description = null,
        bool $isActive = true,
        ?GameInfo $gameInfo = null,
        ?GameFeature $gameFeature = null
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->isActive = $isActive;
        $this->gameInfo = $gameInfo;
        $this->gameFeature = $gameFeature;
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
