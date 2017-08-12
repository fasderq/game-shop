<?php
namespace GameShop\Site\Frontoffice\General\Controller;


use GameShop\Site\General\Renderer;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;

/**
 * Class GeneralController
 * @package GameShop\Site\Frontoffice\General\Controller
 */
class GeneralController
{
    const FILE_STORAGE_PATH = '/front';

    protected $router;
    protected $renderer;

    protected $dataPath;


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

    public function setDataPath(string $dataPath): void
    {
        $this->dataPath = realpath($dataPath);
    }

    public function file(Request $request): Response
    {
        if (empty($this->dataPath) || !is_readable($this->dataPath)) {
            throw new \InvalidArgumentException('DataPath is misconfigured');
        }
        $storagePath = sprintf('%s%s', $this->dataPath, self::FILE_STORAGE_PATH);
        try {
            $response = new BinaryFileResponse(sprintf('%s/%s', $storagePath, $request->get('filePath')));
        } catch (FileNotFoundException $e) {
            return new Response('File not found', Response::HTTP_NOT_FOUND);
        }
        BinaryFileResponse::trustXSendfileTypeHeader();
        $request->headers->set('X-Sendfile-Type', 'X-Accel-Redirect');
        $request->headers->set('X-Accel-Mapping', sprintf('%s/=%s/', $storagePath, self::FILE_STORAGE_PATH));
        return $response->prepare($request);
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
