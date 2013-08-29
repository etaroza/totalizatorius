<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20130829181750 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, team_home INT DEFAULT NULL, team_away INT DEFAULT NULL, time DATETIME NOT NULL, score_home INT NOT NULL, score_away INT NOT NULL, INDEX IDX_232B318CA037F8AB (team_home), INDEX IDX_232B318C77BE56AA (team_away), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE game ADD CONSTRAINT FK_232B318CA037F8AB FOREIGN KEY (team_home) REFERENCES team (id)");
        $this->addSql("ALTER TABLE game ADD CONSTRAINT FK_232B318C77BE56AA FOREIGN KEY (team_away) REFERENCES team (id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("DROP TABLE game");
    }
}
