<?php
namespace GameShop\Site\General;


use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Yaml;

class ParameterLoader
{
    protected $container;
    protected $parametersPath;
    /**
     * @param ContainerInterface $container
     * @param string $parametersPath
     * @throws \InvalidArgumentException
     */
    public function __construct(ContainerInterface $container, string $parametersPath)
    {
        if (!is_readable($parametersPath)) {
            throw new \InvalidArgumentException('Parameters path is no readable');
        }
        $this->container = $container;
        $this->parametersPath = rtrim($parametersPath, '/');
    }
    /**
     * @throws \InvalidArgumentException
     */
    public function load():void
    {
        $dirHandle = opendir($this->parametersPath);
        if (!is_resource($dirHandle)) {
            throw new \InvalidArgumentException('Cannot open parameters path');
        }
        while (false !== ($fileName = readdir($dirHandle))) {
            if (in_array($fileName, ['.', '..'])) {
                continue;
            }
            $configPath = sprintf('%s/%s/default.yml', $this->parametersPath, $fileName);
            if (!is_readable($configPath)) {
                throw new \InvalidArgumentException(sprintf('Parameters is not readable for %s', $fileName));
            }
            $this->container->setParameter(
//                sprintf('parameter.%s', $fileName),
                sprintf('parameter.%s', $fileName),
                Yaml::parse(file_get_contents($configPath))
            );
        }
        closedir($dirHandle);
    }
}
