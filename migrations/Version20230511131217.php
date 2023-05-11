<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230511131217 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__trade AS SELECT id FROM trade');
        $this->addSql('DROP TABLE trade');
        $this->addSql('CREATE TABLE trade (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, sender_id INTEGER NOT NULL, sender_pokemon_id INTEGER NOT NULL, receiver_id INTEGER NOT NULL, reciever_pokemon_id INTEGER NOT NULL, state VARCHAR(50) NOT NULL, CONSTRAINT FK_7E1A4366F624B39D FOREIGN KEY (sender_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_7E1A43667B6342F3 FOREIGN KEY (sender_pokemon_id) REFERENCES pokemon (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_7E1A4366CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_7E1A43668E9460DB FOREIGN KEY (reciever_pokemon_id) REFERENCES pokemon (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO trade (id) SELECT id FROM __temp__trade');
        $this->addSql('DROP TABLE __temp__trade');
        $this->addSql('CREATE INDEX IDX_7E1A4366F624B39D ON trade (sender_id)');
        $this->addSql('CREATE INDEX IDX_7E1A43667B6342F3 ON trade (sender_pokemon_id)');
        $this->addSql('CREATE INDEX IDX_7E1A4366CD53EDB6 ON trade (receiver_id)');
        $this->addSql('CREATE INDEX IDX_7E1A43668E9460DB ON trade (reciever_pokemon_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__trade AS SELECT id FROM trade');
        $this->addSql('DROP TABLE trade');
        $this->addSql('CREATE TABLE trade (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL)');
        $this->addSql('INSERT INTO trade (id) SELECT id FROM __temp__trade');
        $this->addSql('DROP TABLE __temp__trade');
    }
}
