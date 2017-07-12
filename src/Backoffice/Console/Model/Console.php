<?php

namespace GameShop\Site\Backoffice\Console\Model;

/**
 * Class Console
 * @package GameShop\Site\Backoffice\Console\Model
 */
class Console
{
    protected $name;
    protected $isActive;


    /**
     * Console constructor.
     */
    public function __construct(
        string $name,
        bool $isActive = true
    )
    {
        $this->name = $name;
        $this->isActive = $isActive;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

}
