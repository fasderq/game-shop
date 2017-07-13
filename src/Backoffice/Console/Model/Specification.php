<?php


namespace GameShop\Site\Backoffice\Console\Model;

/**
 * Class Specification
 * @package GameShop\Site\Backoffice\Console\Model
 */
class Specification
{
    protected $id;
    protected $console_id;
    protected $specification;
    protected $value;


    /**
     * Specification constructor.
     */
    public function __construct(
        int $console_id,
        string $specification,
        string $value
)
    {
        $this->console_id = $console_id;
        $this->specification = $specification;
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
    public function getSpecification(): string
    {
        return $this->specification;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
