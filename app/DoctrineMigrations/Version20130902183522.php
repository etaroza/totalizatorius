<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20130902183522 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE competition_users (competition_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_E734973F7B39D312 (competition_id), INDEX IDX_E734973FA76ED395 (user_id), PRIMARY KEY(competition_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE competition_users ADD CONSTRAINT FK_E734973F7B39D312 FOREIGN KEY (competition_id) REFERENCES competition (id)");
        $this->addSql("ALTER TABLE competition_users ADD CONSTRAINT FK_E734973FA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)");
        $this->addSql("ALTER TABLE competition ADD admin_id INT DEFAULT NULL");
        $this->addSql("ALTER TABLE competition ADD CONSTRAINT FK_B50A2CB1642B8210 FOREIGN KEY (admin_id) REFERENCES fos_user (id)");
        $this->addSql("CREATE INDEX IDX_B50A2CB1642B8210 ON competition (admin_id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("DROP TABLE competition_users");
        $this->addSql("ALTER TABLE competition DROP FOREIGN KEY FK_B50A2CB1642B8210");
        $this->addSql("DROP INDEX IDX_B50A2CB1642B8210 ON competition");
        $this->addSql("ALTER TABLE competition DROP admin_id");
    }
}
