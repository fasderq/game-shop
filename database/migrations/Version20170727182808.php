<?php

namespace GameShopMigrations\DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170727182808 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $table = $schema->createTable('page');

        $table->addColumn('id',  Type::INTEGER)->setAutoincrement(true)->setLength(10);
        $table->addColumn('code', Type::STRING)->setLength(255);
        $table->addColumn('title', Type::STRING)->setLength(255);
        $table->addColumn('parent_id', Type::INTEGER)->setLength(10)->setNotnull(false);
        $table->addColumn('content', Type::TEXT)->setNotnull(false);
        $table->addColumn('keywords', Type::TEXT)->setNotnull(false);
        $table->addColumn('position', Type::INTEGER)->setDefault(0);
        $table->addColumn('is_active', Type::BOOLEAN)->setDefault(0)->setLength(3);

        $table->setPrimaryKey(['id']);
        $table->addIndex(['parent_id'], 'page_parent_ref');

        $table->addForeignKeyConstraint($table, ['parent_id'], ['id'], [], 'page_parent_ref');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('page');
    }
}
