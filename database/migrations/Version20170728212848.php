<?php

namespace GameShopMigrations\DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170728212848 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $table = $schema->createTable('game');

        $table->addColumn('id', Type::INTEGER)
            ->setNotnull(true)
            ->setAutoincrement(true)
            ->setLength(11);
        $table->addColumn('name', Type::STRING)
            ->setNotnull(true)
            ->setLength(255);
        $table->addColumn('description', Type::TEXT)
            ->setNotnull(false);
        $table->addColumn('is_active', Type::BOOLEAN)
            ->setDefault(0);

        $table->setPrimaryKey(['id']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('game');
    }
}
