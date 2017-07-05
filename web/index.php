<?php

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader as DiYamlLoader;
use Symfony\Component\Config\FileLocator;
use GameShop\Site\General\EnvLoader;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\HttpFoundation\Response;
use GameShop\Site\General\Exception\ResponseException;
use Symfony\Component\Routing\Router;

require_once __DIR__ . '/../vendor/autoload.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

spl_autoload_register(function ($class) {
    if (0 === strpos($class, 'GameShop\\Site\\')) {
        $file = __DIR__ . '/../src/' . str_replace('\\', '/', substr($class, 14)) . '.php';
        if (is_readable($file)) {
            require $file;
        }
    }
});

$container = (function (): ContainerInterface {
    $container = new ContainerBuilder();

    (new DiYamlLoader(
        $container,
        new FileLocator(sprintf('%s/../cfg', __DIR__))
    ))->load('di.yml');

    (new EnvLoader($container))->load();

    $container->setParameter('path.cfg', sprintf('%s/../cfg', __DIR__));
    $container->setParameter('path.tpl', sprintf('%s/../tpl', __DIR__));
    $container->setParameter('path.data', sprintf('%s/../data', __DIR__));

    $container->compile();

    return $container;
})();

if ($container instanceof ContainerInterface) {
    /**
     * @var Router $router
     */
    $router = $container->get('router');

    try {
        $session = new Session();
        $session->start();
        $request = Request::createFromGlobals();
        $request->setSession($session);

        if ($container->hasParameter('www.proxied') && $container->getParameter('www.proxied')) {
            $request->setTrustedProxies(['0.0.0.0/0']);
        }
        $router->getContext()->setMethod($request->getMethod());
        $parameters = $router->matchRequest($request);
        if (empty($parameters['_controller']) || empty($parameters['_action'])) {
            throw new RuntimeException('Route misconfigured');
        }
        $controller = $container->get(sprintf('controller.%s', $parameters['_controller']));

        $request->attributes->add(
            array_filter(
                $parameters,
                function (string $key) {
                    return strpos($key, '_') !== 0;
                },
                ARRAY_FILTER_USE_KEY
            ),
            $parameters
        );
        $controller->{$parameters['_action']}($request)->send();
    } catch (ResponseException $e) {
        $e->getResponse()->send();
    } catch (ResourceNotFoundException $e) {
        (new Response('Not found', Response::HTTP_NOT_FOUND))->send();
    } catch (MethodNotAllowedException $e) {
        (new Response('Method not allowed', Response::HTTP_METHOD_NOT_ALLOWED))->send();
    } catch (Exception $e) {
        (new Response(
            sprintf('%s: %s<pre>%s</pre>', get_class($e), $e->getMessage(), $e->getTraceAsString()),
            Response::HTTP_INTERNAL_SERVER_ERROR
        ))->send();
    }
}
