<?php

namespace GameShopMigrations\DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170729064206 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $gameGenre = $schema->createTable('game_genre');
        $gameGenre->addColumn('id', Type::INTEGER)
            ->setAutoincrement(true)
            ->setNotnull(true)
            ->setLength(11);
        $gameGenre->addColumn('name', Type::STRING)
            ->setNotnull(true)
            ->setLength(255);
        $gameGenre->addColumn('description', Type::TEXT)
            ->setNotnull(false);
        $gameGenre->addColumn('is_active', Type::BOOLEAN)
            ->setDefault(0);

        $gameGenre->setPrimaryKey(['id']);

        $gameGenres = $schema->createTable('game_genres');
        $gameGenres->addColumn('game_id', Type::INTEGER)
            ->setNotnull(true)
            ->setLength(11);
        $gameGenres->addColumn('genre_id',  Type::INTEGER)
            ->setNotnull(true)
            ->setLength(11);

        $gameGenres->setPrimaryKey(['game_id', 'genre_id']);
        $gameGenres->addIndex(['game_id'], 'game_genres_game_ref');
        $gameGenres->addIndex(['genre_id'], 'game_genres_genre_ref');
        $gameGenres->addForeignKeyConstraint('game', ['game_id'], ['id'], [], 'game_genres_game_ref');
        $gameGenres->addForeignKeyConstraint('game_genre',['genre_id'], ['id'], [], 'game_genres_genre_ref');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('game_genres');
        $schema->dropTable('game_genre');
    }
}
