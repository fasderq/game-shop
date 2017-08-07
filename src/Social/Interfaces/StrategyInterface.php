<?php
namespace GameShop\Site\Social\Interfaces;


use GameShop\Site\Social\Exceptions\RequestValidationFailed;
use GameShop\Site\Social\Model\Profile;
use GameShop\Site\Social\Model\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

interface StrategyInterface
{
    /**
     * @param SessionInterface $httpSession
     * @param string $redirectUrl
     * @return string
     */
    public function getOAuthUrl(SessionInterface $httpSession, string $redirectUrl): string;
    /**
     * @param Request $request
     * @return Session
     * @throws RequestValidationFailed
     */
    public function instantiateSession(Request $request): Session;

    /**
     * @param Session $session
     * @return Profile
     */
    public function getProfile(Session $session):Profile;
}