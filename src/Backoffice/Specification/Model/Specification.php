<?php


namespace GameShop\Site\Backoffice\Specification\Model;

/**
 * Class Specification
 * @package GameShop\Site\Backoffice\Specification\Model
 */
class Specification
{
    protected $id;
    protected $console_id;
    protected $name;


    /**
     * Specification constructor.
     */
    public function __construct(
        int $console_id,
        string $name
)
    {
        $this->console_id = $console_id;
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getConsoleId(): int
    {
        return $this->console_id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
