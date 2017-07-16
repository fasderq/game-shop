<?php
namespace GameShop\Site\Backoffice\GameGenre\Controller;



use GameShop\Site\Backoffice\GameGenre\Model\GameGenre;
use GameShop\Site\Backoffice\GameGenre\Repository\GameGenreRepository;
use GameShop\Site\General\Renderer;
use GameShop\Site\User\Service\SessionService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;

/**
 * Class GameGenreController
 * @package GameShop\Site\Backoffice\GameGenre\Controller
 */
class GameGenreController
{
    protected $router;
    protected $renderer;
    protected $sessionService;
    protected $gameGenreRepository;

    /**
     * GameGenreController constructor.
     * @param Router $router
     * @param Renderer $renderer
     * @param SessionService $sessionService
     * @param GameGenreRepository $gameGenreRepository
     */
    public function __construct(
        Router $router,
        Renderer $renderer,
        SessionService $sessionService,
        GameGenreRepository $gameGenreRepository
    ) {
        $this->router = $router;
        $this->renderer = $renderer;
        $this->sessionService = $sessionService;
        $this->gameGenreRepository = $gameGenreRepository;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function gameGenreList(Request $request): Response
    {
        $this->sessionService->requireUserId($request->getSession());

        return $this->renderer->getHtmlResponse(
            'backoffice/game_genre/game_genre_list.html',
            [
                'gameGenres' => $this->gameGenreRepository->getGameGenres()
            ],
            $request->getSession()
        );
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function gameGenreEdit(Request $request): Response
    {
        $this->sessionService->requireUserId($request->getSession());
        $gameGenreId = (int)$request->get('id');

        if (!empty($request->get('data')['submit'])) {
            $data = $request->get('data');
            $errors = $this->validateGameGenreFormData($data);

            if (empty($errors)) {
                $this->saveGameFormData($data, $gameGenreId);

                return new RedirectResponse($this->router->generate('backoffice-game-genre-list'));
            }
        } elseif ($gameGenreId) {
            $data = $this->getGameGenreFormData($gameGenreId);
        }

        return $this->renderer->getHtmlResponse(
            'backoffice/game_genre/game_genre_edit.html',
            [
                'id' => $gameGenreId,
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
    public function gameGenreDelete(Request $request): RedirectResponse
    {
        $this->sessionService->requireUserId($request->getSession());
        $this->gameGenreRepository->deleteGameGenre((int)$request->get('id'));

        return new RedirectResponse($this->router->generate('backoffice-game-genre-list'));
    }

    /**
     * @param array $data
     * @return array
     */
    protected function validateGameGenreFormData(array $data): array
    {
        $errors = [];

        if (empty(trim($data['name']))) {
            $errors['name'] = 'name is required';
        }

        return $errors;
    }

    /**
     * @param array $data
     * @param int|null $id
     */
    protected function saveGameFormData(array $data, ?int $id = null): void
    {
        $gameGenre = new GameGenre(
            $data['name'],
            $data['description'],
            $data['is_active']
        );

        if (empty($id)) {
            $this->gameGenreRepository->addGameGenre($gameGenre);
        } else {
            $this->gameGenreRepository->editGameGenre($gameGenre, $id);
        }
    }

    /**
     * @param int $id
     * @return array
     */
    protected function getGameGenreFormData(int $id): array
    {
        $gameGenre = $this->gameGenreRepository->getGameGenreById($id);

        return [
            'name' => $gameGenre->getName(),
            'description' => $gameGenre->getDescription()
        ];
    }
}
