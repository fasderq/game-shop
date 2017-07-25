<?php
namespace GameShop\Site\Frontoffice\Game\Repository;


use Doctrine\DBAL\Connection;
use GameShop\Site\Frontoffice\Game\Model\Game;

/**
 * Class GameRepository
 * @package GameShop\Site\Frontoffice\Game\Repository
 */
class GameRepository
{
    protected $connection;

    /**
     * GameRepository constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getGameById(int $id): Game
    {
        $game =  $this->connection->createQueryBuilder()
            ->select('*')
            ->from('game')
            ->where('id = :id')->setParameter('id', $id)
            ->andWhere('is_active = 1')
            ->execute()->fetch();

        if (!$game) {

        } else {
            return $this->rowToGame($game);
        }
    }

    /**
     * @param array $row
     * @return Game
     */
    protected function rowToGame(array $row): Game
    {
        return new Game(
            $row['id'],
            $row['name'],
            $row['price'],
            $row['required_age']
        );
    }
}
