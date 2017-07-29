<?php
namespace GameShop\Site\Backoffice\Game\Model;

/**
 * Class GameInfo
 * @package GameShop\Site\Backoffice\Game\Model
 */
class GameInfo
{
    protected $gameId;
    protected $series;
    protected $publisher;
    protected $publicationType;
    protected $revision;
    protected $validity;

    /**
     * GameInfo constructor.
     * @param int $gameId
     * @param null|string $series
     * @param null|string $publisher
     * @param null|string $publicationType
     * @param null|string $revision
     * @param null|string $validity
     */
    public function __construct(
        int $gameId,
        ?string $series = null,
        ?string $publisher = null,
        ?string $publicationType = null,
        ?string $revision = null,
        ?string $validity = null
    ) {
        $this->gameId = $gameId;
        $this->series = $series;
        $this->publisher = $publisher;
        $this->publicationType = $publicationType;
        $this->revision = $revision;
        $this->validity = $validity;
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
    public function getSeries(): ?string
    {
        return $this->series;
    }

    /**
     * @return null|string
     */
    public function getPublisher(): ?string
    {
        return $this->publisher;
    }

    /**
     * @return null|string
     */
    public function getPublicationType(): ?string
    {
        return $this->publicationType;
    }

    /**
     * @return null|string
     */
    public function getRevision(): ?string
    {
        return $this->revision;
    }

    /**
     * @return null|string
     */
    public function getValidity(): ?string
    {
        return $this->validity;
    }
}
