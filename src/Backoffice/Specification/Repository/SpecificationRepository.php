<?php
namespace GameShop\Site\Backoffice\Specification\Repository;


use Doctrine\DBAL\Connection;
use GameShop\Site\Backoffice\Specification\Exception\SpecificationNotFound;
use GameShop\Site\Backoffice\Specification\Model\Specification;

class SpecificationRepository
{
    protected $connection;

    /**
     * SpecificationRepository constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param int $id
     * @return Specification
     * @throws SpecificationNotFound
     */
    public function getSpecification(int $id): Specification
    {
        $query = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('specification')
            ->setParameter('id', $id);

        $console = $query->execute()->fetch();

        if (!$console) {
            throw new SpecificationNotFound();
        }

        return $this->rowToSpecification($console);
    }


    /**
     * @param Specification $Specification
     */
    public function addSpecification(Specification $specification): void
    {

        $this->connection->insert('specification', $this->specificationToRow($specification));
    }

    /**
     * @param Specification $Specification
     * @param int $id
     */
    public function editSpecification(Specification $specification, int $id): void
    {
        $this->connection->update('specification', $this->specificationToRow($specification), ['id' => $id]);
    }

    /**
     * @param int $id
     */
    public function deleteSpecification(int $id): void
    {
        $this->connection->delete('specification', ['id' => $id]);
    }

    /**
     * @param Specification $Specification
     * @return array
     */
    protected function specificationToRow(Specification $specification): array
    {
        return [

            'console_id' => $specification->getConsoleId(),
            'value' => $specification->getValue()

        ];
    }

    /**
     * @param array $row
     * @return Specification
     */
    protected function rowToSpecification(array $row): Specification
    {
        return new Specification(
            $row['console_id'],
            $row['value']
        );
    }
}
