<?php
namespace GameShop\Site\Social\Service;


use GameShop\Site\Social\Enum\StrategyEnum;
use GameShop\Site\Social\Interfaces\StrategyInterface;
use GameShop\Site\Social\Model\Profile;
use GameShop\Site\Social\Model\Session;
use GameShop\Site\Social\Strategy\VkontakteStrategy;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SocialService
{
    protected $vkontakteStrategy;

    public function __construct(
        VkontakteStrategy $vkontakteStrategy
    ) {
        $this->vkontakteStrategy = $vkontakteStrategy;
    }

    /**
     * @param string $provider
     * @param SessionInterface $httpSession
     * @param string $redirectUrl
     * @return string
     */
    public function getOAuthUrl(string $provider, SessionInterface $httpSession, string $redirectUrl): string
    {
        return $this->getStrategy($provider)->getOAuthUrl($httpSession, $redirectUrl);
    }
    /**
     * @param string $provider
     * @param Request $request
     * @return Session
     */
    public function instantiateSession(string $provider, Request $request): Session
    {
        return $this->getStrategy($provider)->instantiateSession($request);
    }

    /**
     * @param string $provider
     * @param Session $session
     * @return Profile
     */
    public function getProfile(string $provider, Session $session): Profile
    {
        return $this->getStrategy($provider)->getProfile($session);
    }

    /**
     * @param SessionInterface $httpSession
     * @param string $provider
     * @param Session $session
     */
    public function rememberSession(SessionInterface $httpSession, string $provider, Session $session): void
    {
        $httpSession->set(
            'social.session',
            [$provider => $session] + $this->getSessionByProviders($httpSession)
        );
    }
    /**
     * @param SessionInterface $httpSession
     * @param string $provider
     */
    public function flushSession(SessionInterface $httpSession, string $provider): void
    {
        $currentSessions = $httpSession->get('social.session', []);
        $currentErrorsState = $httpSession->get('social.error-state', []);
        if (isset($currentErrorsState)) {
            unset($currentErrorsState[$provider]);
            $httpSession->set('social.error-state', $currentErrorsState);
        }
        if (isset($currentSessions[$provider])) {
            unset($currentSessions[$provider]);
            $httpSession->set('social.session', $currentSessions);
        }
    }
    /**
     * @param SessionInterface $httpSession
     * @param string $provider
     * @param \Exception|null $error
     */
    public function setErrorState(SessionInterface $httpSession, string $provider, ?\Exception $error): void
    {
        $httpSession->set(
            'social.error-state',
            [$provider => $error] + $this->getErrorState($httpSession)
        );
    }
    /**
     * @param SessionInterface $httpSession
     * @return array
     */
    public function getErrorState(SessionInterface $httpSession): array
    {
        return $httpSession->get('social.error-state', []);
    }
    /**
     * @param SessionInterface $httpSession
     * @return Session[]
     */
    public function getSessionByProviders(SessionInterface $httpSession): array
    {
        return $httpSession->get('social.session', []);
    }
    /**
     * @param SessionInterface $httpSession
     * @return array
     */
    public function getErrorStateByProviders(SessionInterface $httpSession): array
    {
        return $httpSession->get('social.error-state', []);
    }
    /**
     * @param string $provider
     * @throws \InvalidArgumentException
     * @return StrategyInterface
     */
    protected function getStrategy(string $provider): StrategyInterface
    {
        switch ($provider) {
            case StrategyEnum::VKONTAKTE:
                return $this->vkontakteStrategy;
            default:
                throw new \InvalidArgumentException(sprintf('Unknown provider %s', $provider));
        }
    }
}