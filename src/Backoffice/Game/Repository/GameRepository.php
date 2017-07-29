<?php
namespace GameShop\Site\Backoffice\Game\Repository;


use Doctrine\DBAL\Connection;
use GameShop\Site\Backoffice\Game\Exception\GameNotFound;
use GameShop\Site\Backoffice\Game\Model\CategoryAssign;
use GameShop\Site\Backoffice\Game\Model\Game;
use GameShop\Site\Backoffice\Game\Model\GameFeature;
use GameShop\Site\Backoffice\Game\Model\GameInfo;
use GameShop\Site\Backoffice\Game\Model\GenreAssign;

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

    public function getGameGenres(int $gameId): array
    {
        $query = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('game_genres', 'g')
            ->where('game_id = :game_id')->setParameter('game_id', $gameId)
            ->rightJoin('g', 'game_genre', 'gs', 'g.genre_id = gs.id');
        $data = $query->execute()->fetchAll();
        $gameGenres = [];
        foreach ($data as $gameGenre) {
            $gameGenres[] = $this->rowToGameGenre($gameGenre);
        }
        return $gameGenres;
    }

    /**
     * @param int $gameId
     * @return CategoryAssign[]
     */
    public function getGameCategories(int $gameId): array
    {
        $query = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('game_categories', 'g')
            ->where('game_id = :game_id')->setParameter('game_id', $gameId)
            ->rightJoin('g', 'game_category', 'c', 'g.category_id = c.id')
            ->andWhere('is_active = 1');
        $data = $query->execute()->fetchAll();
        $gameCategories = [];
        foreach ($data as $gameCategory) {
            $gameCategories[] = $this->rowToGameCategory($gameCategory);
        }
        return $gameCategories;
    }

    /**
     * @param Game $game
     * @param GameFeature $gameFeature
     * @param GameInfo $gameInfo
     * @param array $categories
     * @param array $genres
     */
    public function addGame(
        Game $game,
        GameFeature $gameFeature,
        GameInfo $gameInfo,
        array $categories,
        array $genres
    ): void {
        $this->connection->beginTransaction();

        $this->connection->insert('game', $this->gameToRow($game));
        $gameId = $this->connection->lastInsertId();

        $this->addGameFeature($gameId, $gameFeature);
        $this->addGameInfo($gameId, $gameInfo);
        $this->editGameGenres($gameId, $genres);
        $this->editGameCategories($gameId, $categories);

        $this->connection->commit();
    }

    /**
     * @param int $gameId
     * @param GameFeature $gameFeature
     */
    protected function addGameFeature(int $gameId, GameFeature $gameFeature): void
    {
        $this->connection->insert(
            'game_feature',
            ['game_id' => $gameId] + $this->gameFeatureToRow($gameFeature)
        );
    }

    /**
     * @param int $gameId
     * @param GameInfo $gameInfo
     */
    protected function addGameInfo(int $gameId, GameInfo $gameInfo): void
    {
        $this->connection->insert(
            'game_info',
            ['game_id' => $gameId] + $this->gameInfoToRow($gameInfo)
        );
    }

    /**
     * @param int $id
     * @param Game $game
     * @param GameFeature $gameFeature
     * @param GameInfo $gameInfo
     * @param array $categories
     * @param array $genres
     */
    public function editGame(
        int $id,
        Game  $game,
        GameFeature $gameFeature,
        GameInfo $gameInfo,
        array $categories,
        array $genres
    ): void {
        $this->connection->beginTransaction();

        $this->connection->update('game', $this->gameToRow($game), ['id' => $id]);
        $this->editGameFeature($id, $gameFeature);
        $this->editGameInfo($id, $gameInfo);
        $this->editGameGenres($id, $genres);
        $this->editGameCategories($id, $categories);

        $this->connection->commit();
    }

    /**
     * @param int $gameId
     * @param GameFeature $gameFeature
     */
    protected function editGameFeature(int $gameId, GameFeature $gameFeature): void
    {
        $this->connection->update('game_feature', $this->gameFeatureToRow($gameFeature), ['game_id' => $gameId]);
    }

    /**
     * @param int $gameId
     * @param GameInfo $gameInfo
     */
    protected function editGameInfo(int $gameId, GameInfo $gameInfo): void
    {
        $this->connection->update('game_info', $this->gameInfoToRow($gameInfo), ['game_id' => $gameId]);
    }

    /**
     * @param int $gameId
     * @param array $genres
     */
    protected function editGameGenres(int $gameId, array $genres): void
    {
        $this->connection->delete('game_genres', ['game_id' => $gameId]);
        foreach ($genres as $genre) {
            $this->connection->insert(
                'game_genres',
                [
                    'game_id' => $gameId,
                    'genre_id' => $genre
                ]
            );
        }
    }

    /**
     * @param int $gameId
     * @param array $categories
     */
    protected function editGameCategories(int $gameId, array $categories): void
    {
        $this->connection->delete('game_categories', ['game_id' => $gameId]);
        foreach ($categories as $category) {
            $this->connection->insert(
                'game_categories',
                [
                    'game_id' => $gameId,
                    'category_id' => $category
                ]
            );
        }
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

    /**
     * @param array $row
     * @return GenreAssign
     */
    protected function rowToGameGenre(array $row)
    {
        return new GenreAssign(
            $row['genre_id'],
            $row['name']
        );
    }

    /**
     * @param array $row
     * @return CategoryAssign
     */
    protected function rowToGameCategory(array $row): CategoryAssign
    {
        return new CategoryAssign(
            $row['category_id'],
            $row['name']
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
            'description' => $game->getDescription(),
            'is_active' => $game->isActive()
        ];
    }

    /**
     * @param GameFeature $gameFeature
     * @return array
     */
    protected function gameFeatureToRow(GameFeature $gameFeature): array
    {
        return [
            'platform' => $gameFeature->getPlatform(),
            'language' => $gameFeature->getLanguage(),
            'required_age' => $gameFeature->getRequiredAge()
        ];
    }

    /**
     * @param GameInfo $gameInfo
     * @return array
     */
    protected function gameInfoToRow(GameInfo $gameInfo): array
    {
        return [
            'series' => $gameInfo->getSeries(),
            'publisher' => $gameInfo->getPublisher(),
            'publication_type' => $gameInfo->getPublicationType(),
            'revision' => $gameInfo->getRevision(),
            'validity' => $gameInfo->getValidity()
        ];
    }
}
