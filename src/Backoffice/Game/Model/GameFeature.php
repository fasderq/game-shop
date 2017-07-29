<?php
namespace GameShop\Site\Backoffice\Game\Model;

/**
 * Class GameFeature
 * @package GameShop\Site\Backoffice\Game\Model
 */
class GameFeature
{
    protected $gameId;
    protected $platform;
    protected $language;
    protected $requiredAge;

    /**
     * GameFeature constructor.
     * @param int $gameId
     * @param null|string $platform
     * @param null|string $language
     * @param int|null $requiredAge
     */
    public function __construct(
        int $gameId,
        ?string $platform = null,
        ?string $language = null,
        ?int $requiredAge = null
    ) {
        $this->gameId = $gameId;
        $this->platform = $platform;
        $this->language = $language;
        $this->requiredAge = $requiredAge;
    }

    /**
     * @return int
     */
    public function getGameId(): int
    {
        return $this->gameId;
    }

    /**
     * @return null|string
     */
    public function getPlatform(): ?string
    {
        return $this->platform;
    }

    /**
     * @return null|string
     */
    public function getLanguage(): ?string
    {
        return $this->language;
    }

    /**
     * @return int|null
     */
    public function getRequiredAge(): ?int
    {
        return $this->requiredAge;
    }
}
