<?php
namespace GameShop\Site\Frontoffice\Game\Controller;


use GameShop\Site\Frontoffice\Game\Repository\GameRepository;
use GameShop\Site\General\Renderer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;

class GameController
{
    protected $router;
    protected $renderer;
    protected $gameRepository;

    public function __construct(
        Router $router,
        Renderer $renderer,
        GameRepository $gameRepository
    ) {
        $this->router = $router;
        $this->renderer = $renderer;
        $this->gameRepository = $gameRepository;
    }

    public function games(Request $request): Response
    {


        return $this->renderer->getHtmlResponse(
            'frontoffice/game/games.html',
            []
        );
    }
}
