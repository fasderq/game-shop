<?php

namespace GameShopMigrations\DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170729070956 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $gameCategory = $schema->createTable('game_category');
        $gameCategory->addColumn('id', Type::INTEGER)
            ->setAutoincrement(true)
            ->setNotnull(true)
            ->setLength(11);
        $gameCategory->addColumn('name', Type::STRING)
            ->setNotnull(true)
            ->setLength(255);
        $gameCategory->addColumn('is_active', Type::BOOLEAN)
            ->setDefault(0);

        $gameCategory->setPrimaryKey(['id']);

        $gameCategories = $schema->createTable('game_categories');
        $gameCategories->addColumn('game_id', Type::INTEGER)
            ->setNotnull(true)
            ->setLength(11);
        $gameCategories->addColumn('category_id',  Type::INTEGER)
            ->setNotnull(true)
            ->setLength(11);

        $gameCategories->setPrimaryKey(['game_id', 'category_id']);
        $gameCategories->addIndex(['game_id'], 'game_categories_game_ref');
        $gameCategories->addIndex(['category_id'], 'game_categories_category_ref');
        $gameCategories->addForeignKeyConstraint('game', ['game_id'], ['id'], [], 'game_categories_game_ref');
        $gameCategories->addForeignKeyConstraint(
            'game_category',['category_id'], ['id'], [], 'game_categories_category_ref'
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('game_categories');
        $schema->dropTable('game_category');
    }
}
