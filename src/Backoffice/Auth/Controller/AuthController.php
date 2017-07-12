<?php
namespace GameShop\Site\Backoffice\Auth\Controller;


use GameShop\Site\General\Renderer;
use GameShop\Site\User\Exception\AuthenticationFailure;
use GameShop\Site\User\Service\SessionService;
use GameShop\Site\User\Service\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;

/**
 * Class AuthController
 * @package GameShop\Site\Backoffice\Auth\Controller
 */
class AuthController
{
    protected $router;
    protected $renderer;
    protected $userService;
    protected $sessionService;

    /**
     * AuthController constructor.
     * @param Router $router
     * @param Renderer $renderer
     * @param UserService $userService
     * @param SessionService $sessionService
     */
    public function __construct(
        Router $router,
        Renderer $renderer,
        UserService $userService,
        SessionService $sessionService
    ) {
        $this->router = $router;
        $this->renderer = $renderer;
        $this->userService = $userService;
        $this->sessionService = $sessionService;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        if (!$this->sessionService->isAuthenticated($request->getSession())) {
            return new RedirectResponse($this->router->generate('backoffice-login'));
        }

        return new RedirectResponse($this->router->generate('backoffice-welcome'));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function welcome(Request $request): Response
    {
        $this->sessionService->requireUserId($request->getSession());

        return $this->renderer->getHtmlResponse(
            'backoffice/general/welcome.html',
            [],
            $request->getSession()
        );
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function login(Request $request): Response
    {
        if (!empty($request->get('login')['submit'])) {
            $loginFormData = $request->get('login');

            if (empty($errors = $this->validateLoginFormData($loginFormData))) {
                try {
                    $user = $this->userService->authenticate(
                        $loginFormData['email'],
                        $loginFormData['password']
                    );
                    $this->sessionService->setUserId($request->getSession(), $user->getId());

                    return new RedirectResponse($this->router->generate('backoffice-index'));
                } catch (AuthenticationFailure $e) {
                    $errors['failure'] = 'Wrong email or password';
                }
            }
        }

        return $this->renderer->getHtmlResponse(
            'backoffice/auth/login_form.html',
            [
                'errors' => $errors ?? [],
                'email' => $request->get('login')['email']
            ],
            $request->getSession()
        );
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        $this->sessionService->setUserId($request->getSession(), null);

        return new RedirectResponse($this->router->generate('backoffice-index'));
    }

    /**
     * @param array $data
     * @return array
     */
    protected function validateLoginFormData(array $data): array
    {
        $errors = [];

        if (empty(trim($data['email']))) {
            $errors['email'] = 'email is required';
        }

        if (empty(trim($data['password']))) {
            $errors['password'] = 'password is required';
        }

        return $errors;
    }
}
