<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20130902185231 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE bid (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, competition_id INT DEFAULT NULL, game_id INT DEFAULT NULL, score_home INT NOT NULL, score_away INT NOT NULL, points INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_4AF2B3F3A76ED395 (user_id), INDEX IDX_4AF2B3F37B39D312 (competition_id), INDEX IDX_4AF2B3F3E48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE bid ADD CONSTRAINT FK_4AF2B3F3A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)");
        $this->addSql("ALTER TABLE bid ADD CONSTRAINT FK_4AF2B3F37B39D312 FOREIGN KEY (competition_id) REFERENCES competition (id)");
        $this->addSql("ALTER TABLE bid ADD CONSTRAINT FK_4AF2B3F3E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("DROP TABLE bid");
    }
}
