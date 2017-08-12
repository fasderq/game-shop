<?php

namespace GameShop\Site\Backoffice\FileManager\Controller;


use GameShop\Site\General\Renderer;
use GameShop\Site\User\Service\SessionService;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;

/**
 * Class FileManagerController
 * @package GameShop\Site\Backoffice\FileManager\Controller
 */
class FileManagerController
{
    protected $router;
    protected $renderer;
    protected $sessionService;

    protected $dataPath;

    /**
     * FileManagerController constructor.
     * @param Router $router
     * @param Renderer $renderer
     * @param SessionService $sessionService
     */
    public function __construct(
        Router $router,
        Renderer $renderer,
        SessionService $sessionService
    ) {
        $this->router = $router;
        $this->renderer = $renderer;
        $this->sessionService = $sessionService;
    }

    /**
     * @param string $dataPath
     */
    public function setDataPath(string $dataPath): void
    {
        $this->dataPath = $dataPath;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function files(Request $request): Response
    {
        $this->sessionService->requireUserId($request->getSession());

        $path = $request->get('path') ? sprintf('%s/%s', $this->dataPath, $request->get('path')) : $this->dataPath;

        if (!empty($files = $request->files) && $request->get('file_form')['submit']) {
            if (null !== $files->get('file')) {
                $this->saveFileFormData($files->get('file'), $path . '/');
            }
        }

        if (!empty($request->get('folder_form')['submit'])) {
            $folderFormData = $request->get('folder_form');
            $errors = $this->validateFolderForm($folderFormData);

            if (empty($errors)) {
                $this->saveFolderFormData($folderFormData, $path);
            }
        }

        return $this->renderer->getHtmlResponse(
            '/backoffice/file_manager/list.html',
            [
                'currentPath' => $this->getCurrentPath($path),
                'directories' => $this->getDirectories($path),
                'files' => $this->getFiles($path),
                'breadcrumbs' => $this->getBreadCrumbs($path)
            ],
            $request->getSession()
        );
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteDirectory(Request $request): RedirectResponse
    {
        $this->sessionService->requireUserId($request->getSession());

        (new Filesystem())->remove(sprintf('%s/%s', $this->dataPath, $request->get('path')));

        $paths = explode('/', $request->get('path'));
        array_pop($paths);
        $realPath = implode('/', $paths);

        return new RedirectResponse($this->router->generate('backoffice-files', ['path' => $realPath]));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteFile(Request $request): RedirectResponse
    {
        $this->sessionService->requireUserId($request->getSession());

        (new Filesystem())->remove(sprintf('%s/%s', $this->dataPath, $request->get('path')));

        $paths = explode('/', $request->get('path'));
        array_pop($paths);
        $realPath = implode('/', $paths);

        return new RedirectResponse($this->router->generate('backoffice-files', ['path' => $realPath]));
    }

    /**
     * @param array $data
     * @param string $path
     * void
     */
    protected function saveFolderFormData(array $data, string $path): void
    {
        (new Filesystem())->mkdir(sprintf('%s/%s', $path, $data['name']), 0777);
        (new Filesystem())->chmod(sprintf('%s/%s', $path, $data['name']), 0777);
    }

    /**
     * @param array $data
     * @return array
     */
    protected function validateFolderForm(array $data): array
    {
        $errors = [];

        if (empty(trim($data['name']))) {
            $errors['errors'] = 'name is required';
        }

        return $errors;
    }

    /**
     * @param string|null $path
     * @return Finder
     */
    protected function getFinder(string $path = null): Finder
    {
        $finder = new Finder();
        $finder->depth('== 0');

        if (null === $path) {
            $finder->in($this->dataPath);
        } else {
            $finder->in($path);
        }

        return $finder;
    }

    /**
     * @param string|null $path
     * @return array
     */
    protected function getFiles(string $path = null)
    {
        $finder = $this->getFinder($path);
        $finder->files();

        $files = [];
        foreach ($finder as $file) {
            $files[$file->getFilename()] = preg_replace(sprintf('[%s/]', $this->dataPath), '', $file);
        }

        return $files;
    }

    /**
     * @param string|null $path
     * @return array
     */
    protected function getDirectories(string $path = null): array
    {
        $finder = $this->getFinder($path);
        $finder->directories();

        $directories = [];
        foreach ($finder as $directory) {
            $directories[$directory->getFilename()] = preg_replace(
                sprintf('[%s/]', $this->dataPath), '', $directory
            );
        }

        return $directories;
    }


    /**
     * @param string|null $path
     * @return array
     */
    protected function getBreadCrumbs(string $path = null): array
    {
        if ($path === $this->dataPath) {
            return [];
        }

        $realPath = preg_replace(sprintf('[%s/]', $this->dataPath), '', $path);

        $breadcrumbs = [];
        $pathParts = explode('/', $realPath);
        $iteratedParts = [];
        foreach ($pathParts as $pathPart) {
            $iteratedParts[] = $pathPart;
            $breadcrumbs[implode('/', $iteratedParts)] = $pathPart;
        }

        return $breadcrumbs;
    }

    /**
     * @param string $path
     * @return string
     */
    protected function getCurrentPath(string $path): string
    {
        if ($this->dataPath === $path) {
            return '';
        } else {
            return preg_replace(sprintf('[%s/]', $this->dataPath), '', $path);
        }
    }

    /**
     * @param UploadedFile $file
     * @param string $path
     * @throws \ErrorException
     */
    protected function saveFileFormData(UploadedFile $file, string $path): void
    {
        if (empty($file->getError())) {
            $file->move($path, $file->getClientOriginalName());
            (new Filesystem())->chmod(sprintf('%s/%s', $path, $file->getClientOriginalName()), 0777);
        } else {
            throw new \ErrorException('file with errors');
        }
    }


}
