<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220507180034 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE event_rate (id INT AUTO_INCREMENT NOT NULL, id_user_id INT NOT NULL, id_event_id INT NOT NULL, INDEX IDX_C027739279F37AE5 (id_user_id), INDEX IDX_C0277392212C041E (id_event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recherche (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event_rate ADD CONSTRAINT FK_C027739279F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE event_rate ADD CONSTRAINT FK_C0277392212C041E FOREIGN KEY (id_event_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE evenement CHANGE offre_id offre_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation CHANGE id_user_id id_user_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE event_rate');
        $this->addSql('DROP TABLE recherche');
        $this->addSql('ALTER TABLE evenement CHANGE offre_id offre_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation CHANGE id_user_id id_user_id INT DEFAULT NULL');
    }
}
