<?php
namespace GameShop\Site\Backoffice\Game\Repository;


use Doctrine\DBAL\Connection;
use GameShop\Site\Backoffice\Game\Exception\GameNotFound;
use GameShop\Site\Backoffice\Game\Model\CategoryAssign;
use GameShop\Site\Backoffice\Game\Model\Game;
use GameShop\Site\Backoffice\Game\Model\GenreAssign;
use GameShop\Site\Backoffice\GameCategory\Model\GameCategory;

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
     * @param int $gameId
     * @return GenreAssign[]
     */
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
     * @param Game $game
     * @param array $categories
     * @param array $genres
     */
    public function addGame(Game $game, array $categories = [], array $genres = []): void
    {
        $this->connection->beginTransaction();

        $this->connection->insert('game', $this->gameToRow($game));
        $gameId = $this->connection->lastInsertId();

        $this->editGameCategories($gameId, $categories);
        $this->editGameGenres($gameId, $genres);

        $this->connection->commit();
    }

    /**
     * @param Game $game
     * @param int $id
     * @param array $categories
     * @param array $genres
     */
    public function editGame(Game $game, int $id, array $categories = [], array $genres = []): void
    {
        $this->connection->beginTransaction();

        $this->connection->update('game', $this->gameToRow($game), ['id' => $id]);

        $this->editGameCategories($id, $categories);
        $this->editGameGenres($id, $genres);

        $this->connection->commit();
    }

    /**
     * @param int $id
     */
    public function deleteGame(int $id): void
    {
        $this->connection->update('game', ['is_active' => 0], ['id' => $id]);
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
     * @param array $row
     * @return Game
     */
    protected function rowToGame(array $row): Game
    {
        return new Game(
            $row['name'],
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
            'price' => $game->getPrice(),
            'special_offer' => $game->getSpecialOffer(),
            'required_age' => $game->getRequiredAge(),
            'is_active' => $game->isActive()
        ];
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
     * @param array $row
     * @return GenreAssign
     */
    protected function rowToGameGenre(array $row): GenreAssign
    {
        return new GenreAssign(
            $row['genre_id'],
            $row['name']
        );
    }
}
