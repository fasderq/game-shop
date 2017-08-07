<?php
namespace GameShop\Site\Social\Model;

/**
 * Class Session
 * @package GameShop\Site\Social\Model
 */
class Session
{
    protected $accessToken;
    protected $expiresAt;

    /**
     * Session constructor.
     * @param string $accessToken
     * @param \DateTime $expiresAt
     */
    public function __construct(string $accessToken, \DateTime $expiresAt)
    {
        $this->accessToken = $accessToken;
        $this->expiresAt = $expiresAt;
    }
    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }
    /**
     * @return \DateTime
     */
    public function getExpiresAt(): \DateTime
    {
        return $this->expiresAt;
    }
}
