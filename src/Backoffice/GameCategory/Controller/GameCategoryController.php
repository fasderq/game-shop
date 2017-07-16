<?php
namespace GameShop\Site\Backoffice\GameCategory\Controller;


use GameShop\Site\Backoffice\GameCategory\Model\GameCategory;
use GameShop\Site\Backoffice\GameCategory\Repository\GameCategoryRepository;
use GameShop\Site\General\Renderer;
use GameShop\Site\User\Service\SessionService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;

/**
 * Class GameCategoryController
 * @package GameShop\Site\Backoffice\GameCategory\Controller
 */
class GameCategoryController
{
    protected $router;
    protected $renderer;
    protected $sessionService;
    protected $gameCategoryRepository;

    /**
     * GameCategoryController constructor.
     * @param Router $router
     * @param Renderer $renderer
     * @param SessionService $sessionService
     * @param GameCategoryRepository $gameCategoryRepository
     */
    public function __construct(
        Router $router,
        Renderer $renderer,
        SessionService $sessionService,
        GameCategoryRepository $gameCategoryRepository
    ) {
        $this->router = $router;
        $this->renderer = $renderer;
        $this->sessionService = $sessionService;
        $this->gameCategoryRepository = $gameCategoryRepository;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function gameCategoryList(Request $request): Response
    {
        $this->sessionService->requireUserId($request->getSession());

        return $this->renderer->getHtmlResponse(
            'backoffice/game_category/game_category_list.html',
            [
                'gameCategories' => $this->gameCategoryRepository->getGameCategories()
            ],
            $request->getSession()
        );
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function gameCategoryEdit(Request $request): Response
    {
        $this->sessionService->requireUserId($request->getSession());

        $gameCategoryId = (int)$request->get('id');

        if (!empty($request->get('data')['submit'])) {
            $data = $request->get('data');
            $errors = $this->validateGameCategoryFormData($data);

            if (empty($errors)) {
                $this->saveGameCategoryData($data, $gameCategoryId);

                return new RedirectResponse($this->router->generate('backoffice-game-category-list'));
            }
        } elseif ($gameCategoryId) {
            $data = $this->getGameCategoryFormData($gameCategoryId);
        }

        return $this->renderer->getHtmlResponse(
            'backoffice/game_category/game_category_edit.html',
            [
                'id' => $gameCategoryId,
                'errors' => $errors ?? [],
                'data' => $data ?? []
            ],
            $request->getSession()
        );
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function gameCategoryDelete(Request $request): RedirectResponse
    {
        $this->sessionService->requireUserId($request->getSession());
        $this->gameCategoryRepository->deleteGameCategory((int)$request->get('id'));

        return new RedirectResponse($this->router->generate('backoffice-game-category-list'));
    }

    /**
     * @param array $data
     * @param int|null $id
     */
    protected function saveGameCategoryData(array $data, ?int $id = null): void
    {
        $gameCategory = new GameCategory(
            $data['name'],
            $data['description'],
            $data['is_active']
        );

        if (empty($id)) {
            $this->gameCategoryRepository->addGameCategory($gameCategory);
        } else {
            $this->gameCategoryRepository->editGameCategory($gameCategory, $id);
        }
    }

    /**
     * @param int $id
     * @return array
     */
    protected function getGameCategoryFormData(int $id): array
    {
        $gameCategory = $this->gameCategoryRepository->getGameCategoryById($id);

        return [
            'name' => $gameCategory->getName(),
            'description' => $gameCategory->getDescription()
        ];
    }

    /**
     * @param array $data
     * @return array
     */
    protected function validateGameCategoryFormData(array $data): array
    {
        $errors = [];

        if (empty(trim($data['name']))) {
            $errors['name'] = 'name is required';
        }

        return $errors;
    }
}