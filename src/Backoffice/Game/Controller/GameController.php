<?php
namespace GameShop\Site\Backoffice\Game\Controller;


use GameShop\Site\Backoffice\Game\Enum\GameGenreEnum;
use GameShop\Site\Backoffice\Game\Repository\GameRepository;
use GameShop\Site\Backoffice\GameCategory\Repository\GameCategoryRepository;
use GameShop\Site\General\Renderer;
use GameShop\Site\User\Service\SessionService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;

/**
 * Class GameController
 * @package GameShop\Site\Backoffice\Game\Controller
 */
class GameController
{
    protected $router;
    protected $renderer;
    protected $sessionService;
    protected $gameRepository;
    protected $gameCategoryRepository;

    /**
     * GameController constructor.
     * @param Router $router
     * @param Renderer $renderer
     * @param SessionService $sessionService
     * @param GameRepository $gameRepository
     * @param GameCategoryRepository $gameCategoryRepository
     */
    public function __construct(
        Router $router,
        Renderer $renderer,
        SessionService $sessionService,
        GameRepository $gameRepository,
        GameCategoryRepository $gameCategoryRepository
    ) {
        $this->router =  $router;
        $this->renderer = $renderer;
        $this->sessionService =  $sessionService;
        $this->gameRepository =  $gameRepository;
        $this->gameCategoryRepository = $gameCategoryRepository;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function gameList(Request $request): Response
    {
        $this->sessionService->requireUserId($request->getSession());

        return $this->renderer->getHtmlResponse(
            'backoffice/game/game_list.html',
            [
                'games' => $this->gameRepository->getGames()
            ],
            $request->getSession()
        );
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function gameEdit(Request $request): Response
    {
        $this->sessionService->requireUserId($request->getSession());

        $gameId = (int)$request->get('id');

        return $this->renderer->getHtmlResponse(
            'backoffice/game/game_edit.html',
            [
                'id' => $gameId,
                'errors' => $errors ?? [],
                'data' => $data ?? [],
                'gameCategories' => $this->gameCategoryRepository->getGameCategories()
            ],
            $request->getSession()
        );
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteGame(Request $request): RedirectResponse
    {
        $this->sessionService->requireUserId($request->getSession());
        $this->gameRepository->deleteGame((int)$request->get('id'));

        return new RedirectResponse($this->router->generate('backoffice-game-list'));
    }
}
