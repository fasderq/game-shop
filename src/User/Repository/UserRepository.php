<?php
namespace GameShop\Site\User\Repository;


use Doctrine\DBAL\Connection;
use GameShop\Site\User\Exception\UserNotFound;
use GameShop\Site\User\Model\User;

/**
 * Class UserRepository
 * @package GameShop\Site\User\Repository
 */
class UserRepository
{
    protected $connection;

    /**
     * UserRepository constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param int $id
     * @return User
     * @throws UserNotFound
     */
    public function getUserById(int $id): User
    {
        $row = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('user')
            ->where('id = :id')->setParameter('id', $id)
            ->execute()->fetch();

        if (!$row) {
            throw new UserNotFound();
        }

        return $this->rowToUser($row);
    }

    /**
     * @param string $email
     * @param string $password
     * @return User
     * @throws UserNotFound
     */
    public function getUserByCredentials(string $email, string $password): User
    {
        $row = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('user')
            ->where('email = :email')->setParameter('email', $email)
            ->andWhere('password = :password')->setParameter('password', $password)
            ->execute()->fetch();

        if (!$row) {
            throw new UserNotFound();
        }

        return $this->rowToUser($row);
    }

    /**
     * @param array $row
     * @return User
     */
    protected function rowToUser(array $row): User
    {
        return new User(
            $row['id'],
            $row['name'],
            $row['email'],
            $row['is_active']
        );
    }
}
