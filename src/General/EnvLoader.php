<?php
namespace GameShop\Site\General;


use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class EnvLoader
 * @package GameShop\Site\General
 */
class EnvLoader
{
    const PREFIX = 'GAMESHOP__';

    protected $container;

    /**
     * EnvLoader constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * void
     */
    public function load(): void
    {
        foreach ($_SERVER as $key => $value) {
            if (0 !== strpos($key, self::PREFIX)) {
                continue;
            }
            $this->container->setParameter(
                substr(strtolower(str_replace('__', '.', $key)), (strlen(self::PREFIX) - 1)),
                $value
            );
        }
    }
}
