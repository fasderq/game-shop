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
    protected $ConsoleRepository;

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
        ConsoleRepository $ConsoleRepository
    ) {
        $this->router = $router;
        $this->renderer = $renderer;
        $this->sessionService = $sessionService;
        $this->ConsoleRepository = $ConsoleRepository;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function consoleList()
    {

        return 'SASSSSSSSSSSSSSSSSSSSSS';

//        $this->sessionService->requireUserId($request->getSession());

//        return $this->renderer->getHtmlResponse(
//            'backoffice/console/console_list.html', ['text' => 'adfadfadf']
//            [
//                'rootConsoles' => $rootConsoles = $this->ConsoleRepository->getConsoles(null),
//                'childConsoles' => $this->getChildConsoles(array_keys($rootConsoles))
//            ],
//            $request->getSession()
//        );
    }

    /**
     * @param array $ConsoleIds
     * @return Console[]
     */
    protected function getChildConsoles(array $consoleIds): array
    {
        $childConsoles = [];
        foreach ($consoleIds as $consoleId) {
            $childConsoles[$consoleId] = $this->ConsoleRepository->getConsoles($consoleId);
            $childConsoles += $this->getChildConsoles(array_keys($childConsoles[$consoleId]));
        }

        return $childConsoles;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function ConsoleEdit(Request $request): Response
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
        $Console = new Console(
            $data['code'],
            $data['title'],
            $data['parent_id'] ?: null,
            $data['content'] ?: null,
            $data['keywords'] ?: null,
            $data['position'] ?: 0,
            $data['is_active']
        );

        if ($consoleId) {
            $this->ConsoleRepository->editConsole($Console, $consoleId);
        } else {
            $this->ConsoleRepository->addConsole($Console);
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
            'title' => $console->getTitle(),
            'parent_id' => $console->getParentId(),
            'content' => $console->getContent(),
            'keywords' => $console->getKeywords(),
            'position' => $console->getPosition()
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
            $errors['code'] = 'code is required';
        }

        if (empty(trim($data['title']))) {
            $errors['title'] = 'title is required';
        }

        return $errors;
    }
}
