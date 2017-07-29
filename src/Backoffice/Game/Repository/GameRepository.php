<?php
namespace GameShop\Site\Backoffice\Game\Repository;


use Doctrine\DBAL\Connection;
use GameShop\Site\Backoffice\Game\Exception\GameNotFound;
use GameShop\Site\Backoffice\Game\Model\CategoryAssign;
use GameShop\Site\Backoffice\Game\Model\Game;
use GameShop\Site\Backoffice\Game\Model\GameFeature;
use GameShop\Site\Backoffice\Game\Model\GameInfo;
use GameShop\Site\Backoffice\Game\Model\GenreAssign;
use GameShop\Site\Backoffice\GameCategory\Model\GameCategory;
use GameShop\Site\General\Exception\EntryNotFound;

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
     * @param int|null $id
     * @return Game
     * @throws GameNotFound
     */
    public function getGameById(? int $id): Game
    {
        $query = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('game')
            ->where('is_active = 1')
            ->andWhere('id = :id')->setParameter('id',  $id);

        $game = $query->execute()->fetch();

        if (!$game) {
            throw new GameNotFound();
        } else {
            return $this->rowToGame($game);
        }
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
     * @param int $gameId
     * @return GameInfo|null
     */
    public function getGameInfoByGameId(int $gameId): ?GameInfo
    {
        $query = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('game_info')
            ->where('game_id = :game_id')->setParameter('game_id',  $gameId);

        $gameInfo = $query->execute()->fetch();

        if (!$gameInfo) {
            return null;
        } else {
            return $this->rowToGameInfo($gameInfo);
        }
    }

    /**
     * @param int $gameId
     * @return GameFeature|null
     */
    public function getGameFeatureByGameId(int $gameId): ?GameFeature
    {
        $query = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('game_feature')
            ->where('game_id = :game_id')->setParameter('game_id',  $gameId);

        $gameFeature = $query->execute()->fetch();

        if (!$gameFeature) {
            return null;
        } else {
            return $this->rowToGameFeature($gameFeature);
        }
    }

    /**
     * @param array $row
     * @return Game
     */
    protected function rowToGame(array $row): Game {
        return new Game(
            $row['name'],
            $row['description'],
            $row['is_active']

        );
    }

    /**
     * @param array $row
     * @return GameInfo
     */
    protected function rowToGameInfo(array $row): GameInfo
    {
        return new GameInfo(
            $row['game_id'],
            $row['series'],
            $row['publisher'],
            $row['publication_type'],
            $row['revision'],
            $row['validity']
        );
    }

    /**
     * @param array $row
     * @return GameFeature
     */
    protected function rowToGameFeature(array $row): GameFeature
    {
        return new GameFeature(
            $row['game_id'],
            $row['platform'],
            $row['language'],
            $row['required_age']
        );
    }
}
