<?php
namespace GameShop\Site\Backoffice\Console\Repository;


use Doctrine\DBAL\Connection;
use GameShop\Site\Backoffice\Console\Exception\ConsoleNotFound;
use GameShop\Site\Backoffice\Console\Model\Console;

class ConsoleRepository
{
    protected $connection;

    /**
     * ConsoleRepository constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param int $id
     * @return Console
     * @throws ConsoleNotFound
     */
    public function getConsole(int $id): Console
    {
        $query = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('console')
            ->setParameter('id', $id);

        $console = $query->execute()->fetch();

        if (!$console) {
            throw new ConsoleNotFound();
        }

        return $this->rowToConsole($console);
    }

    /**
     * @param int|null $parentId
     * @return Console[]
     */
    public function getConsoles(int $parentId = null): array
    {
        $query = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('console');

        $data = $query->execute->fetchAll();

        var_dump($data); die;

        $consoles = [];
        foreach($data as $console) {
            $consoles[$console['id']] = $this->rowToConsole($console);
        }
        return $consoles;
    }

    /**
     * @param Console $Console
     */
    public function addConsole(Console $Console): void
    {
        $this->connection->insert('console', $this->ConsoleToRow($Console));
    }

    /**
     * @param Console $Console
     * @param int $id
     */
    public function editConsole(Console $Console, int $id): void
    {
        $this->connection->update('console', $this->ConsoleToRow($Console), ['id' => $id]);
    }

    /**
     * @param int $id
     */
    public function deleteConsole(int $id): void
    {
        $this->connection->update('console', ['id' => $id]);
    }

    /**
     * @param Console $Console
     * @return array
     */
    protected function ConsoleToRow(Console $console): array
    {
        return [
            'name' => $console->getName()
        ];
    }

    /**
     * @param array $row
     * @return Console
     */
    protected function rowToConsole(array $row): Console
    {
        return new Console(
            $row['name'],
            $row ['is_active']
        );
    }
}
