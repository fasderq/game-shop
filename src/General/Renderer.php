<?php
namespace GameShop\Site\General;


use GameShop\Site\User\Service\SessionService;
use GameShop\Site\User\Service\UserService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Router;

/**
 * Class Renderer
 * @package GameShop\Site\General
 */
class Renderer
{
    protected $twig;
    protected $router;
    protected $sessionService;
    protected $userService;
    protected $aclService;
    protected $worldContextEntity;

    /**
     * Renderer constructor.
     * @param \Twig_Environment $twig
     * @param Router $router
     * @param SessionService $sessionService
     * @param UserService $userService
     */
    public function __construct(
        \Twig_Environment $twig,
        Router $router,
        SessionService $sessionService,
        UserService $userService
    ) {
        $this->twig = $twig;
        $this->router = $router;
        $this->sessionService = $sessionService;
        $this->userService = $userService;
    }

    /**
     * @param $name
     * @param array $context
     * @param SessionInterface|null $session
     * @return Response
     */
    public function getHtmlResponse($name, array $context = [], SessionInterface $session = null): Response
    {
        return new Response(
            $this->renderer($name, $context, $session)
        );
    }

    /**
     * @param $name
     * @param array $context
     * @param SessionInterface|null $session
     * @return string
     */
    public function renderer($name, array $context = [], SessionInterface $session = null): string
    {
        $globalContext = [];

        if (null !== $session && $this->sessionService->isAuthenticated($session)) {
            $userId = $this->sessionService->requireUserId($session);
            $globalContext += [
                'sessionUser' => $this->userService->getUser($userId)
            ];
        }

        return $this->twig->render($name, $context + $globalContext);
    }
}
