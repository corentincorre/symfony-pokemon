<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230511124548 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__pokemon AS SELECT id, user_id FROM pokemon');
        $this->addSql('DROP TABLE pokemon');
        $this->addSql('CREATE TABLE pokemon (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, pokemon_name VARCHAR(255) NOT NULL, pokemon_image CLOB NOT NULL, CONSTRAINT FK_62DC90F3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO pokemon (id, user_id) SELECT id, user_id FROM __temp__pokemon');
        $this->addSql('DROP TABLE __temp__pokemon');
        $this->addSql('CREATE INDEX IDX_62DC90F3A76ED395 ON pokemon (user_id)');
        $this->addSql('ALTER TABLE user ADD COLUMN last_game DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__pokemon AS SELECT id, user_id FROM pokemon');
        $this->addSql('DROP TABLE pokemon');
        $this->addSql('CREATE TABLE pokemon (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, pokemon CLOB NOT NULL --(DC2Type:object)
        , CONSTRAINT FK_62DC90F3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO pokemon (id, user_id) SELECT id, user_id FROM __temp__pokemon');
        $this->addSql('DROP TABLE __temp__pokemon');
        $this->addSql('CREATE INDEX IDX_62DC90F3A76ED395 ON pokemon (user_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, username, roles, password, email, genre FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, genre SMALLINT DEFAULT NULL)');
        $this->addSql('INSERT INTO user (id, username, roles, password, email, genre) SELECT id, username, roles, password, email, genre FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
    }
}
