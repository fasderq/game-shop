<?php
namespace GameShop\Site\User\Service;


use GameShop\Site\General\Exception\ResponseException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Router;

/**
 * Class SessionService
 * @package GameShop\Site\User\Service
 */
class SessionService
{
    protected $router;

    /**
     * SessionService constructor.
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param SessionInterface $session
     * @param int|null $userId
     */
    public function setUserId(SessionInterface $session, int $userId = null): void
    {
        $session->set('user_id', $userId);
    }

    /**
     * @param SessionInterface $session
     * @return bool
     */
    public function isAuthenticated(SessionInterface $session): bool
    {
        return null !== $session->get('user_id', null);
    }

    /**
     * @param SessionInterface $session
     * @return int
     * @throws ResponseException
     */
    public function requireUserId(SessionInterface $session): int
    {
        if ($userId = $session->get('user_id')) {
            return $userId;
        }

        throw new ResponseException(
            new RedirectResponse($this->router->generate('backoffice-login'))
        );
    }
}
