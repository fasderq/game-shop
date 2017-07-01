<?php
namespace GameShop\Site\User\Model;

/**
 * Class User
 * @package GameShop\Site\User\Model
 */
class User
{
    protected $id;
    protected $name;
    protected $email;
    protected $isActive;

    /**
     * User constructor.
     * @param int $id
     * @param string $name
     * @param string $email
     * @param bool $isActive
     */
    public function __construct(int $id, string $name, string $email, bool $isActive = true)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
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
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }
}
