<?php
namespace GameShop\Site\Social\Model;


class ProfileEmail
{
    protected $value;
    protected $type;
    /**
     * ProfileEmail constructor.
     * @param string $value
     * @param string|null $type
     */
    public function __construct(string $value, ?string $type = null)
    {
        $this->value = $value;
        $this->type = $type;
    }
    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
    /**
     * @return null|string
     */
    public function getType(): ?string
    {
        return $this->type;
    }
}
