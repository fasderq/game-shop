<?php


namespace GameShop\Site\Backoffice\Console\Model;

/**
 * Class SpeConsole
 * @package GameShop\Site\Backoffice\Console\Model
 */
class SpecConsole
{
    protected $specification_id;
    protected $console_id;
    protected $name;


    /**
     * Specification constructor.
     */
    public function __construct(
        int $console_id,
        int $specification_id,
        string $value
)
    {
        $this->console_id = $console_id;
        $this->specification_id = $specification_id;
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getConsoleId(): int
    {
        return $this->console_id;
    }

    /**
     * @return int
     */
    public function getSpecId(): int
    {
        return $this->specification_id;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
