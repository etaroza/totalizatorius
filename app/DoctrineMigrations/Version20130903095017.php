<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20130903095017 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE competition ADD tournament_id INT DEFAULT NULL");
        $this->addSql("ALTER TABLE competition ADD CONSTRAINT FK_B50A2CB133D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id)");
        $this->addSql("CREATE INDEX IDX_B50A2CB133D1A3E7 ON competition (tournament_id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE competition DROP FOREIGN KEY FK_B50A2CB133D1A3E7");
        $this->addSql("DROP INDEX IDX_B50A2CB133D1A3E7 ON competition");
        $this->addSql("ALTER TABLE competition DROP tournament_id");
    }
}
