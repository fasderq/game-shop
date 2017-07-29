<?php

namespace GameShopMigrations\DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170728215324 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $gameInfo = $schema->createTable('game_info');

        $gameInfo->addColumn('game_id', Type::INTEGER)
            ->setNotnull(true)
            ->setLength(11);
        $gameInfo->addColumn('series', Type::STRING)
            ->setNotnull(false)
            ->setLength(255);
        $gameInfo->addColumn('publisher', Type::STRING)
            ->setNotnull(false)
            ->setLength(127);
        $gameInfo->addColumn('publication_type', Type::STRING)
            ->setNotnull(false)
            ->setLength(127);
        $gameInfo->addColumn('revision', Type::STRING)
            ->setNotnull(false)
            ->setLength(127);
        $gameInfo->addColumn('validity', Type::STRING)
            ->setNotnull(false)
            ->setLength(127);

        $gameInfo->setPrimaryKey(['game_id']);
        $gameInfo->addIndex(['game_id'], 'game_info_ref');
        $gameInfo->addForeignKeyConstraint('game', ['game_id'], ['id'], [], 'game_info_ref');

        $gameFeature = $schema->createTable('game_feature');

        $gameFeature->addColumn('game_id', Type::INTEGER)
            ->setNotnull(true)
            ->setLength(11);
        $gameFeature->addColumn('platform', Type::STRING)
            ->setNotnull(false)
            ->setLength(255);
        $gameFeature->addColumn('language', Type::STRING)
            ->setNotnull(false)
            ->setLength(127);
        $gameFeature->addColumn('required_age', Type::INTEGER)
            ->setNotnull(false)
            ->setLength(127);

        $gameFeature->setPrimaryKey(['game_id']);
        $gameFeature->addIndex(['game_id'], 'game_feature_ref');
        $gameFeature->addForeignKeyConstraint('game', ['game_id'], ['id'], [], 'game_feature_ref');

        $gameImages = $schema->createTable('game_image');

        $gameImages->addColumn('id', Type::INTEGER)
            ->setAutoincrement(true)
            ->setNotnull(true);
        $gameImages->addColumn('game_id', Type::INTEGER)
            ->setNotnull(true)
            ->setLength(11);
        $gameImages->addColumn('image_path', Type::STRING)
            ->setNotnull(true)
            ->setLength(255);

        $gameImages->setPrimaryKey(['id', 'game_id']);
        $gameImages->addIndex(['game_id'], 'game_image_ref');
        $gameImages->addForeignKeyConstraint('game', ['game_id'], ['id'], [], 'game_image_ref');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('game_info');
        $schema->dropTable('game_feature');
        $schema->dropTable('game_image');
    }
}
