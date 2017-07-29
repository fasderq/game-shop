<?php

namespace GameShop\Site\Backoffice\Game\Controller;


use GameShop\Site\Backoffice\Game\Model\CategoryAssign;
use GameShop\Site\Backoffice\Game\Model\Game;
use GameShop\Site\Backoffice\Game\Model\GameFeature;
use GameShop\Site\Backoffice\Game\Model\GenreAssign;
use GameShop\Site\Backoffice\Game\Repository\GameRepository;
use GameShop\Site\Backoffice\GameCategory\Model\GameCategory;
use GameShop\Site\Backoffice\GameCategory\Repository\GameCategoryRepository;
use GameShop\Site\Backoffice\GameGenre\Repository\GameGenreRepository;
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
    protected $gameGenreRepository;

    /**
     * GameController constructor.
     * @param Router $router
     * @param Renderer $renderer
     * @param SessionService $sessionService
     * @param GameRepository $gameRepository
     * @param GameCategoryRepository $gameCategoryRepository
     * @param GameGenreRepository $gameGenreRepository
     */
    public function __construct(
        Router $router,
        Renderer $renderer,
        SessionService $sessionService,
        GameRepository $gameRepository,
        GameCategoryRepository $gameCategoryRepository,
        GameGenreRepository $gameGenreRepository
    )
    {
        $this->router = $router;
        $this->renderer = $renderer;
        $this->sessionService = $sessionService;
        $this->gameRepository = $gameRepository;
        $this->gameCategoryRepository = $gameCategoryRepository;
        $this->gameGenreRepository = $gameGenreRepository;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function gameList(Request $request): Response
    {
        $this->sessionService->requireUserId($request->getSession());

        var_dump($this->gameRepository->getGames());

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

        if ($request->get('data')['submit']) {
            $data = $request->get('data');
            $errors = $this->validateGameFormData($data);

            if (empty($errors)) {
                $this->saveGameFormData($data, $gameId);

                return new RedirectResponse($this->router->generate('backoffice-game-list'));
            }

        } elseif ($gameId) {
            $data = $this->getGameFormData($gameId);
        }

        return $this->renderer->getHtmlResponse(
            'backoffice/game/game_edit.html',
            [
                'id' => $gameId,
                'errors' => $errors ?? [],
                'data' => $data ?? [],
                'gameInfo' => $this->gameRepository->getGameInfoByGameId($gameId),
                'gameFeatures' => $this->gameRepository->getGameFeatureByGameId($gameId)
//                'gameCategories' => $this->gameCategoryRepository->getGameCategories(),
//                'gameGenres' => $this->gameGenreRepository->getGameGenres()
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

    /**
     * @param array $data
     * @return array
     */
    protected function validateGameFormData(array $data): array
    {
        $errors = [];

        if (empty(trim($data['name']))) {
            $errors['name'] = 'name is required';
        }

        if (empty(trim($data['price']))) {
            $errors['price'] = 'price is required';
        }

        return $errors;
    }

    /**
     * @param array $data
     * @param int|null $id
     */
    protected function saveGameFormData(array $data, int $id = null): void
    {
        $game = new Game(
            $data['name'],
            $data['price'],
            $data['special_offer'] ?? 0,
            $data['required_age'],
            $data['is_active']
        );


        if (empty($id)) {
            $this->gameRepository->addGame(
                $game,
                $data['categories'] ?? [],
                $data['genres'] ?? []
            );
        } else {
            $this->gameRepository->editGame(
                $game,
                $id,
                $data['categories'] ?? [],
                $data['genres'] ?? []
            );
        }
    }

    /**
     * @param int $id
     * @return array
     */
    protected function getGameFormData(int $id): array
    {
        $game = $this->gameRepository->getGameById($id);
        $gameFeature = $this->gameRepository->getGameFeatureByGameId($id);
        $gameInfo =  $this->gameRepository->getGameInfoByGameId($id);

        return [
            'name' => $game->getName(),
            'description' => $game->getDescription(),
            'gameFeature' => [
                'platform' => $gameFeature->getPlatform(),
                'language' => $gameFeature->getLanguage(),
                'required_age' => $gameFeature->getRequiredAge()
            ],
            'gameInfo' => [
                'series' => $gameInfo->getSeries(),
                'publisher' => $gameInfo->getPublisher(),
                'publicationType' => $gameInfo->getPublicationType(),
                'revision' => $gameInfo->getRevision(),
                'validity' => $gameInfo->getValidity(),
            ]




//            'categories' => array_reduce(
//                $this->gameRepository->getGameCategories($id),
//                function (array $row, CategoryAssign $categoryAssign) {
//                    return $row + [
//                        $categoryAssign->getCategoryId() => [
//                            'name' => $categoryAssign->getCategoryName()
//                        ]
//                    ];
//                },
//                []
//            ),
//            'genres' => array_reduce(
//                $this->gameRepository->getGameGenres($id),
//                function (array $row, GenreAssign $genreAssign) {
//                    return $row + [
//                        $genreAssign->getGenreId() => [
//                            'name' => $genreAssign->getGenreName()
//                        ]
//                    ];
//                },
//                []
//            )
        ];
    }
}
