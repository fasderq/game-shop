<?php
namespace GameShop\Site\Backoffice\Page\Repository;


use Doctrine\DBAL\Connection;
use GameShop\Site\Backoffice\Page\Exception\PageNotFound;
use GameShop\Site\Backoffice\Page\Model\Page;

class PageRepository
{
    protected $connection;

    /**
     * PageRepository constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param int $id
     * @return Page
     * @throws PageNotFound
     */
    public function getPage(int $id): Page
    {
        $query = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('page')
            ->where('is_active = 1')
            ->andWhere('id = :id')->setParameter('id', $id);

        $page = $query->execute()->fetch();

        if (!$page) {
            throw new PageNotFound();
        }

        return $this->rowToPage($page);
    }

    /**
     * @param int|null $parentId
     * @return Page[]
     */
    public function getPages(int $parentId = null): array
    {
        $query = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('page')
            ->orderBy('position', 'ASC')
            ->where('is_active = 1');

        if (null === $parentId) {
            $query->andWhere('parent_id is NULL');
        } else {
            $query->andWhere('parent_id = :parent_id')->setParameter('parent_id', $parentId);
        }

        return array_reduce(
            $query->execute()->fetchAll(),
            function (array $result, array $row) {
                return $result + [
                    $row['id'] => $this->rowToPage($row)
                ];
            },
            []
        );
    }

    /**
     * @param Page $page
     */
    public function addPage(Page $page): void
    {
        $this->connection->insert('page', $this->pageToRow($page));
    }

    /**
     * @param Page $page
     * @param int $id
     */
    public function editPage(Page $page, int $id): void
    {
        $this->connection->update('page', $this->pageToRow($page), ['id' => $id]);
    }

    /**
     * @param int $id
     */
    public function deletePage(int $id): void
    {
        $this->connection->update('page', ['is_active' => 0], ['id' => $id]);
    }

    /**
     * @param Page $page
     * @return array
     */
    protected function pageToRow(Page $page): array
    {
        return [
            'code' => $page->getCode(),
            'title' => $page->getTitle(),
            'parent_id' => $page->getParentId(),
            'content' => $page->getContent(),
            'keywords' => $page->getKeywords(),
            'position' => $page->getPosition(),
            'is_active' => $page->isActive()
        ];
    }

    /**
     * @param array $row
     * @return Page
     */
    protected function rowToPage(array $row): Page
    {
        return new Page(
            $row['code'],
            $row['title'],
            $row['parent_id'],
            $row['content'],
            $row['keywords'],
            $row['position'],
            $row['is_active']
        );
    }
}
