<?php

namespace GameShopMigrations\DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170727180117 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $table = $schema->createTable('user');

        $table->addColumn('id', Type::INTEGER)->setAutoincrement(true)->setLength(10);
        $table->addColumn('name', Type::STRING)->setLength(255);
        $table->addColumn('email', Type::STRING)->setLength(255);
        $table->addColumn('password', Type::STRING)->setLength(32);
        $table->addColumn('is_active', Type::BOOLEAN)->setUnsigned(true)->setDefault(1)->setLength(3);

        $table->setPrimaryKey(['id']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('user');
    }
}
