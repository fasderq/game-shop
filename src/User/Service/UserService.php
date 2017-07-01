<?php
namespace GameShop\Site\User\Service;


use GameShop\Site\User\Exception\AuthenticationFailure;
use GameShop\Site\User\Exception\UserNotFound;
use GameShop\Site\User\Model\User;
use GameShop\Site\User\Repository\UserRepository;

/**
 * Class UserService
 * @package GameShop\Site\User\Service
 */
class UserService
{
    protected $userRepository;

    /**
     * UserService constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param int $id
     * @return User
     */
    public function getUser(int $id): User
    {
        return $this->userRepository->getUserById($id);
    }

    /**
     * @param string $email
     * @param string $password
     * @return User
     * @throws AuthenticationFailure
     */
    public function authenticate(string $email, string $password): User
    {
        try {
            return $this->userRepository->getUserByCredentials(
                $email,
                $password
            );
        } catch (UserNotFound $e) {
            throw new AuthenticationFailure();
        }
    }
}
