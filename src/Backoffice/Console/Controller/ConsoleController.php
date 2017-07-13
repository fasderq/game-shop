<?php
namespace GameShop\Site\Backoffice\Console\Controller;


use GameShop\Site\Backoffice\Console\Model\Console;
use GameShop\Site\Backoffice\Console\Repository\ConsoleRepository;
use GameShop\Site\General\Renderer;
use GameShop\Site\User\Service\SessionService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;

/**
 * Class ConsoleController
 * @package GameShop\Site\Backoffice\Console\Controller
 */
class ConsoleController
{
    protected $router;
    protected $renderer;
    protected $sessionService;
    protected $consoleRepository;

    /**
     * ConsoleController constructor.
     * @param Router $router
     * @param Renderer $renderer
     * @param SessionService $sessionService
     * @param ConsoleRepository $ConsoleRepository
     */
    public function __construct(
        Router $router,
        Renderer $renderer,
        SessionService $sessionService,
        ConsoleRepository $consoleRepository
    ) {
        $this->router = $router;
        $this->renderer = $renderer;
        $this->sessionService = $sessionService;
        $this->consoleRepository = $consoleRepository;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function consoleList(Request $request): Response
    {

        $this->sessionService->requireUserId($request->getSession());

        return $this->renderer->getHtmlResponse(
            'backoffice/console/console_list.html', [
            'consoles' => $consoles = $this->consoleRepository->getConsoles(),
        ],
            $request->getSession()
        );
    }


    /**
     * @param Request $request
     * @return Response
     */
    public function consoleEdit(Request $request): Response
    {
        $this->sessionService->requireUserId($request->getSession());

        $ConsoleId = (int)$request->get('id');

        if ($request->get('data')['submit']) {
            $ConsoleFormData = $request->get('data');

            if (empty($errors = $this->validateConsoleFormData($ConsoleFormData))) {
                $this->saveConsoleFormData($ConsoleFormData, $ConsoleId);

                return new RedirectResponse($this->router->generate('backoffice-console-list'));
            }
        } elseif ($ConsoleId) {
            $ConsoleFormData = $this->getConsoleFormData($ConsoleId);
        }

        return $this->renderer->getHtmlResponse(
            'backoffice/console/console_edit.html',
            [
                'id' => $ConsoleId,
                'data' => $ConsoleFormData ?? [],
                'errors' => $errors ?? []
            ],
            $request->getSession()
        );
    }

    /**
     * @param array $data
     * @param int|null $consoleId
     */
    protected function saveConsoleFormData(array $data, ?int $consoleId = null): void
    {
        $console = new Console(
            $data['code'],
            $data['name']
        );

        if ($consoleId) {
            $this->consoleRepository->editConsole($console, $consoleId);
        } else {
            $this->consoleRepository->addConsole($console);
        }
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function ConsoleDelete(Request $request): RedirectResponse
    {
        $this->ConsoleRepository->deleteConsole((int)$request->get('id'));

        return new RedirectResponse($this->router->generate('backoffice-Console-list'));
    }

    /**
     * @param int $id
     * @return array
     */
    protected function getConsoleFormData(int $id): array
    {
        $console = $this->ConsoleRepository->getConsole($id);

        return [
            'code' => $console->getCode(),
            'name' => $console->getName()
        ];
    }

    /**
     * @param array $data
     * @return array
     */
    protected function validateConsoleFormData(array $data): array
    {
        $errors = [];

        if (empty(trim($data['code']))) {
            $errors['code'] = 'Нужен артикул';
        }

        if (empty(trim($data['name']))) {
            $errors['name'] = 'Модель консоли не введен';
        }

        return $errors;
    }
}
