<?php

namespace GameShop\Site\Backoffice\Console\Model;

/**
 * Class Console
 * @package GameShop\Site\Backoffice\Console\Model
 */
class Accessory
{
    protected $name;

    /**
     * Console constructor.
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


}