<?php

namespace GameShop\Site\Backoffice\Accessory\Model;

/**
 * Class Accessory
 * @package GameShop\Site\Backoffice\Accessory\Model
 */
class Accessory
{
    protected $name;

    /**
     * Accessory constructor.
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