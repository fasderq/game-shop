<?php

namespace GameShop\Site\General;


use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Class EnvLoader
 * @package GameShop\Site\General
 */
class ConfigurationLoader
{
    const PREFIX = 'GAMESHOP__';

    protected $container;
    protected $configPath;

    /**
     * EnvLoader constructor.
     * @param ContainerInterface $container
     * @param string $configPath
     */
    public function __construct(ContainerInterface $container, string $configPath)
    {
        $this->container = $container;
        $this->configPath = $configPath;
    }

    /**
     * void
     */
    public function load(): void
    {
        if (!is_file($this->configPath)) {
            throw new \InvalidArgumentException('config file does not exist');
        }

        $conf = Yaml::parse(file_get_contents($this->configPath));

        foreach ($conf as $service => $values) {
            foreach ($values as $key => $value) {
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
}
