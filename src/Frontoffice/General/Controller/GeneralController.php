<?php
namespace GameShop\Site\Frontoffice\General\Controller;


use GameShop\Site\General\Renderer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;

/**
 * Class GeneralController
 * @package GameShop\Site\Frontoffice\General\Controller
 */
class GeneralController
{
    protected $router;
    protected $renderer;

    /**
     * GeneralController constructor.
     * @param Router $router
     * @param Renderer $renderer
     */
    public function __construct(
        Router $router,
        Renderer $renderer
    ) {
        $this->router = $router;
        $this->renderer = $renderer;
    }

    /**
     * @return Response
     */
    public function index(): Response
    {
        return $this->renderer->getHtmlResponse(
            'frontoffice/general/main.html',
            []
        );
    }

    /**
     * @return Response
     */
    public function about(): Response
    {
        return $this->renderer->getHtmlResponse(
            'frontoffice/about/about.html',
            []
        );
    }

    /**
     * @return Response
     */
    public function contact(): Response
    {
        return $this->renderer->getHtmlResponse(
            'frontoffice/contact/contact.html',
            []
        );
    }
}
