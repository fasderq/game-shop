<?php
namespace GameShop\Site\Backoffice\Page\Controller;


use GameShop\Site\Backoffice\Page\Model\Page;
use GameShop\Site\Backoffice\Page\Repository\PageRepository;
use GameShop\Site\General\Renderer;
use GameShop\Site\User\Service\SessionService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;

/**
 * Class PageController
 * @package GameShop\Site\Backoffice\Page\Controller
 */
class PageController
{
    protected $router;
    protected $renderer;
    protected $sessionService;
    protected $pageRepository;

    /**
     * PageController constructor.
     * @param Router $router
     * @param Renderer $renderer
     * @param SessionService $sessionService
     * @param PageRepository $pageRepository
     */
    public function __construct(
        Router $router,
        Renderer $renderer,
        SessionService $sessionService,
        PageRepository $pageRepository
    ) {
        $this->router = $router;
        $this->renderer = $renderer;
        $this->sessionService = $sessionService;
        $this->pageRepository = $pageRepository;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function pageList(Request $request): Response
    {
        $this->sessionService->requireUserId($request->getSession());

        return $this->renderer->getHtmlResponse(
            'backoffice/page/page_list.html',
            [
                'rootPages' => $rootPages = $this->pageRepository->getPages(null),
                'childPages' => $this->getChildPages(array_keys($rootPages))
            ],
            $request->getSession()
        );
    }

    /**
     * @param array $pageIds
     * @return Page[]
     */
    protected function getChildPages(array $pageIds): array
    {
        $childPages = [];
        foreach ($pageIds as $pageId) {
            $childPages[$pageId] = $this->pageRepository->getPages($pageId);
            $childPages += $this->getChildPages(array_keys($childPages[$pageId]));
        }

        return $childPages;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function pageEdit(Request $request): Response
    {
        $this->sessionService->requireUserId($request->getSession());

        $pageId = (int)$request->get('id');

        if ($request->get('data')['submit']) {
            $pageFormData = $request->get('data');

            if (empty($errors = $this->validatePageFormData($pageFormData))) {
                $this->savePageFormData($pageFormData, $pageId);

                return new RedirectResponse($this->router->generate('backoffice-page-list'));
            }
        } elseif ($pageId) {
            $pageFormData = $this->getPageFormData($pageId);
        }

        return $this->renderer->getHtmlResponse(
            'backoffice/page/page_edit.html',
            [
                'id' => $pageId,
                'data' => $pageFormData ?? [],
                'errors' => $errors ?? []
            ],
            $request->getSession()
        );
    }

    /**
     * @param array $data
     * @param int|null $pageId
     */
    protected function savePageFormData(array $data, ?int $pageId = null): void
    {
        $page = new Page(
            $data['code'],
            $data['title'],
            $data['parent_id'] ?: null,
            $data['content'] ?: null,
            $data['keywords'] ?: null,
            $data['position'] ?: 0,
            $data['is_active']
        );

        if ($pageId) {
            $this->pageRepository->editPage($page, $pageId);
        } else {
            $this->pageRepository->addPage($page);
        }
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function pageDelete(Request $request): RedirectResponse
    {
        $this->sessionService->requireUserId($request->getSession());

        $this->pageRepository->deletePage((int)$request->get('id'));

        return new RedirectResponse($this->router->generate('backoffice-page-list'));
    }

    /**
     * @param int $id
     * @return array
     */
    protected function getPageFormData(int $id): array
    {
        $page = $this->pageRepository->getPage($id);

        return [
            'code' => $page->getCode(),
            'title' => $page->getTitle(),
            'parent_id' => $page->getParentId(),
            'content' => $page->getContent(),
            'keywords' => $page->getKeywords(),
            'position' => $page->getPosition()
        ];
    }

    /**
     * @param array $data
     * @return array
     */
    protected function validatePageFormData(array $data): array
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
