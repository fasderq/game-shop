<?php
namespace GameShop\Site\Backoffice\GameGenre\Repository;


use Doctrine\DBAL\Connection;
use GameShop\Site\Backoffice\GameGenre\Exception\GameGenreNotFound;
use GameShop\Site\Backoffice\GameGenre\Model\GameGenre;

/**
 * Class GameGenreRepository
 * @package GameShop\Site\Backoffice\GameGenre\Repository
 */
class GameGenreRepository
{
    protected $connection;

    /**
     * GameGenreRepository constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param int $id
     * @return GameGenre
     * @throws GameGenreNotFound
     */
    public function getGameGenreById(int $id): GameGenre
    {
        $gameGenre = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('game_genre')
            ->where('is_active = 1')
            ->andWhere('id = :id')->setParameter('id', $id)
            ->execute()->fetch();

        if (!$gameGenre) {
            throw new GameGenreNotFound();
        } else {
            return $this->rowToGameGenre($gameGenre);
        }
    }

    /**
     * @return GameGenre[]
     */
    public function getGameGenres(): array
    {
        $query = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('game_genre')
            ->where('is_active = 1');

        $data = $query->execute()->fetchAll();

        $gameGenres = [];
        foreach ($data as $gameGenre) {
            $gameGenres[$gameGenre['id']] = $this->rowToGameGenre($gameGenre);
        }

        return $gameGenres;
    }

    /**
     * @param GameGenre $gameGenre
     */
    public function addGameGenre(GameGenre $gameGenre): void
    {
        $this->connection->insert('game_genre', $this->gameGenreToRow($gameGenre));
    }

    /**
     * @param GameGenre $gameGenre
     * @param int $id
     */
    public function editGameGenre(GameGenre $gameGenre, int $id): void
    {
        $this->connection->update('game_genre', $this->gameGenreToRow($gameGenre), ['id' => $id]);
    }

    /**
     * @param int $id
     */
    public function deleteGameGenre(int $id): void
    {
        $this->connection->update('game_genre', ['is_active' => 0], ['id' => $id]);
    }

    /**
     * @param array $row
     * @return GameGenre
     */
    protected function rowToGameGenre(array $row): GameGenre
    {
        return new GameGenre(
            $row['name'],
            $row['description'],
            $row['is_active']
        );
    }

    /**
     * @param GameGenre $gameGenre
     * @return array
     */
    protected function gameGenreToRow(GameGenre $gameGenre): array
    {
        return [
            'name' => $gameGenre->getName(),
            'description' => $gameGenre->getDescription(),
            'is_active' => $gameGenre->isActive()
        ];
    }
}
