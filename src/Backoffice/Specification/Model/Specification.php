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
    protected $description;
    protected $value;


    /**
     * Specification constructor.
     */
    public function __construct(
        int $console_id,
        string $description,
        string $value
)
    {
        $this->console_id = $console_id;
        $this->description = $description;
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
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
