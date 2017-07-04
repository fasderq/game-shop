<?php
namespace GameShop\Site\Backoffice\Game\Repository;


use Doctrine\DBAL\Connection;
use GameShop\Site\Backoffice\Game\Exception\GameNotFound;
use GameShop\Site\Backoffice\Game\Model\Game;

/**
 * Class GameRepository
 * @package GameShop\Site\Backoffice\Game\Repository
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

    /**
     * @param int $id
     * @return Game
     * @throws GameNotFound
     */
    public function getGameById(int $id): Game
    {
        $game = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('game')
            ->where('is_active = 1')
            ->andWhere('id = :id')->setParameter('id', $id)
            ->execute()->fetch();

        if (!$game) {
            throw new GameNotFound('game not found');
        }

        return $this->rowToGame($game);
    }

    /**
     * @return Game[]
     */
    public function getGames(): array
    {
        $query = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('game')
            ->where('is_active = 1');

        $data = $query->execute()->fetchAll();

        $games = [];
        foreach ($data as $game) {
            $games[$game['id']] = $this->rowToGame($game);
        }

        return $games;
    }

    /**
     * @param Game $game
     */
    public function addGame(Game $game): void
    {
        $this->connection->insert('game', $this->gameToRow($game));
    }

    /**
     * @param Game $game
     * @param int $id
     */
    public function editGame(Game $game, int $id): void
    {
        $this->connection->update('game', $this->gameToRow($game), ['id' => $id]);
    }

    /**
     * @param int $id
     */
    public function deleteGame(int $id): void
    {
        $this->connection->update('game', ['is_active' => 0], ['id' => $id]);
    }

    /**
     * @param array $row
     * @return Game
     */
    protected function rowToGame(array $row): Game
    {
        return new Game(
            $row['name'],
            $row['category'],
            $row['genre'],
            $row['price'],
            $row['special_offer'],
            $row['required_age'],
            $row['is_active']
        );
    }

    /**
     * @param Game $game
     * @return array
     */
    protected function gameToRow(Game $game): array
    {
        return [
            'name' => $game->getName(),
            'category' => $game->getCategory(),
            'genre' => $game->getGenre(),
            'price' => $game->getSpecialOffer(),
            'special_offer' => $game->getSpecialOffer(),
            'required_age' => $game->getRequiredAge(),
            'is_active' => $game->isActive()
        ];
    }
}
