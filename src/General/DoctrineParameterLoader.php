<?php
namespace GameShop\Site\General;


use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Yaml\Yaml;

class DoctrineParameterLoader
{
    protected $container;
    protected $configPath;

    public function __construct(Container $container, string $configPath)
    {
        if (!is_readable($configPath)) {
            throw new \InvalidArgumentException('Config path is not readable');
        }
        $this->container = $container;
        $this->configPath = $configPath;
    }

    public function load(): void
    {
        $dirHandle = opendir($this->configPath);
        if (!is_resource($dirHandle)) {
            throw new \InvalidArgumentException('Cannot open parameters path');
        }
        while (false !== ($fileName = readdir($dirHandle))) {
            if (in_array($fileName, ['.', '..'])) {
                continue;
            }
            $configPath = sprintf('%s/%s/default.yml', $this->configPath, $fileName);
            if (!is_readable($configPath)) {
                throw new \InvalidArgumentException(sprintf('Parameters is not readable for %s', $fileName));
            }

            $configParameters = Yaml::parse(file_get_contents($configPath));

            foreach ($configParameters as $parameter => $value) {
                $this->container->setParameter(
                    sprintf('doctrine_migrations.%s', $parameter),
                    $value
                );
            }

        }
        closedir($dirHandle);
    }
}
