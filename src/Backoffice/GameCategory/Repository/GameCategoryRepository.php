<?php
namespace GameShop\Site\Backoffice\GameCategory\Repository;


use Doctrine\DBAL\Connection;
use GameShop\Site\Backoffice\GameCategory\Exception\GameCategoryNotFound;
use GameShop\Site\Backoffice\GameCategory\Model\GameCategory;

/**
 * Class GameCategoryRepository
 * @package GameShop\Site\Backoffice\GameCategory\Repository
 */
class GameCategoryRepository
{
    protected $connection;

    /**
     * GameCategoryRepository constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param int $id
     * @return GameCategory
     * @throws GameCategoryNotFound
     */
    public function getGameCategoryById(int $id): GameCategory
    {
        $query = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('game_category')
            ->where('is_active')
            ->andWhere('id = :id')->setParameter('id', $id);

        $gameCategory = $query->execute()->fetch();

        if (!$gameCategory) {
            throw new GameCategoryNotFound();
        } else {
            return $this->rowToGameCategory($gameCategory);
        }
    }

    /**
     * @return GameCategory[]
     */
    public function getGameCategories(): array
    {
        $query = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('game_category')
            ->where('is_active = 1');

        $data = $query->execute()->fetchAll();

        $gameCategories = [];
        foreach ($data as $gameCategory) {
            $gameCategories[$gameCategory['id']] = $this->rowToGameCategory($gameCategory);
        }

        return $gameCategories;
    }

    /**
     * @param GameCategory $gameCategory
     */
    public function addGameCategory(GameCategory $gameCategory): void
    {
        $this->connection->insert('game_category', $this->gameCategoryToRow($gameCategory));
    }

    /**
     * @param GameCategory $gameCategory
     * @param int $id
     */
    public function editGameCategory(GameCategory $gameCategory, int $id): void
    {
        $this->connection->update('game_category', $this->gameCategoryToRow($gameCategory), ['id' => $id]);
    }

    /**
     * @param int $id
     */
    public function deleteGameCategory(int $id): void
    {
        $this->connection->update('game_category', ['is_active' => 0], ['id' => $id]);
    }

    /**
     * @param GameCategory $gameCategory
     * @return array
     */
    protected function gameCategoryToRow(GameCategory $gameCategory): array
    {
        return [
            'name' => $gameCategory->getName(),
            'is_active' => $gameCategory->isActive()
        ];
    }

    /**
     * @param array $row
     * @return GameCategory
     */
    protected function rowToGameCategory(array $row): GameCategory
    {
        return new GameCategory(
            $row['name'],
            $row['is_active']
        );
    }
}
