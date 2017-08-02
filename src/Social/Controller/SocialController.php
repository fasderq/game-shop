<?php
namespace GameShop\Site\Social\Controller;


use GameShop\Site\Social\Exceptions\AccessDenied;
use GameShop\Site\Social\Exceptions\CorruptedResponse;
use GameShop\Site\Social\Service\SocialService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class SocialController
 * @package GameShop\Site\Social\Controller
 */
class SocialController
{
    protected $router;
    protected $socialService;

    /**
     * SocialController constructor.
     * @param RouterInterface $router
     * @param SocialService $socialService
     */
    public function __construct(
        RouterInterface $router,
        SocialService $socialService
    ) {
        $this->router = $router;
        $this->socialService = $socialService;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function signIn(Request $request): Response
    {
        $provider = $request->get('provider');
        $request->getSession()->set(
            'social.return_page',
            $request->get('return_page')
        );

        return new RedirectResponse(
            $this->socialService->getOAuthUrl(
                $provider,
                $request->getSession(),
                $this->router->generate(
                    'social-oauth-callback',
                    ['provider' => $provider],
                    Router::ABSOLUTE_URL
                )
            )
        );
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function oauthCallback(Request $request): Response
    {
        $provider = $request->get('provider');
        $returnPage = $request->getSession()->get('social.return_page');
        $httpCodeResult = Response::HTTP_OK;
        $httpBody = '';
        $error = null;
        try {
            $this->socialService->rememberSession(
                $request->getSession(),
                $provider,
                $this->socialService->instantiateSession($provider, $request)
            );
        } catch (AccessDenied $e) {
            $httpCodeResult = Response::HTTP_BAD_REQUEST;
            $httpBody = 'Доступ к данным запрещен';
            $error = $e;
        } catch (CorruptedResponse $e) {
            $httpCodeResult = Response::HTTP_INTERNAL_SERVER_ERROR;
            $httpBody = 'Ошибка соединения с соцсетью';
            $error = $e;
        }
        $this->socialService->setErrorState($request->getSession(), $provider, $error);
        if ($returnPage) {
            return new RedirectResponse($returnPage);
        }
        return new Response($httpBody, $httpCodeResult);
    }

    public function debug(Request $request): Response
    {
        echo '<pre>';
        $sessions = $this->socialService->getSessionByProviders($request->getSession());
        foreach ($sessions as $provider => $session) {
            var_dump($this->socialService->getProfile($provider, $session));
        }
        return new Response('');
    }

}
