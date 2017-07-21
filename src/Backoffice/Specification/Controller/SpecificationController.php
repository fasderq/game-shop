<?php

namespace GameShop\Site\Backoffice\Specification\Controller;


use GameShop\Site\Backoffice\Specification\Model\Specification;
use GameShop\Site\Backoffice\Specification\Model\SpecConsole;
use GameShop\Site\Backoffice\Specification\Repository\SpecificationRepository;
use GameShop\Site\General\Renderer;
use GameShop\Site\User\Service\SessionService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;

/**
 * Class SpecificationController
 * @package GameShop\Site\Backoffice\Specicfication\Controller
 */
class SpecificationController
{
    protected $router;
    protected $renderer;
    protected $sessionService;
    protected $specificationRepository;

    /**
     * SpecificationController constructor.
     * @param Router $router
     * @param Renderer $renderer
     * @param SessionService $sessionService
     * @param SpecificationRepository $SpecificationRepository
     */
    public function __construct(
        Router $router,
        Renderer $renderer,
        SessionService $sessionService,
        SpecificationRepository $specificationRepository
    )
    {
        $this->router = $router;
        $this->renderer = $renderer;
        $this->sessionService = $sessionService;
        $this->specificationRepository = $specificationRepository;
    }

//    /**
//     * @param Request $request
//     * @return Response
//     */
//    public function specificationByConsole(Request $request): Response
//    {
//
//        $this->sessionService->requireUserId($request->getSession());
//
//        return  $this->specificationRepository->getSpecification('id');
//
//    }


    public function specificationEdit(Request $request): Response
    {
        $this->sessionService->requireUserId($request->getSession());

        return $this->renderer->getHtmlResponse(
            'backoffice/specification/specification_edit.html',  [],  $request->getSession()
        );
    }


}
