<?php

namespace GameShop\Site\Backoffice\Console\Model;

/**
 * Class Console
 * @package GameShop\Site\Backoffice\Console\Model
 */
class Console
{
    protected $id;
    protected $name;
    protected $code;
    protected $isActive;


    /**
     * Console constructor.
     */
    public function __construct(
        $code,
        string $name,
        bool $isActive = true
    )
    {
        $this->name = $name;
        $this->code = $code;
        $this->isActive = $isActive;
    }



    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

}
