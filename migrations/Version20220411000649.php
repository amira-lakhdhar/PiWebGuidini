<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220411000649 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE evenement CHANGE offre_id offre_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation CHANGE id_user_id id_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD adresse VARCHAR(255) NOT NULL, ADD email VARCHAR(255) NOT NULL, ADD password VARCHAR(255) NOT NULL, ADD role TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE evenement CHANGE offre_id offre_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation CHANGE id_user_id id_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user DROP adresse, DROP email, DROP password, DROP role');
    }
}
