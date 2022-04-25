<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220412003223 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE compagnieaerienne (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, contact VARCHAR(255) NOT NULL, logo VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vol (id INT AUTO_INCREMENT NOT NULL, compagnie_id INT NOT NULL, num_vol INT NOT NULL, date_vol DATETIME NOT NULL, destination VARCHAR(255) NOT NULL, ville_depart VARCHAR(255) NOT NULL, type_vol VARCHAR(255) NOT NULL, INDEX IDX_95C97EB52FBE437 (compagnie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE voyage (id INT AUTO_INCREMENT NOT NULL, vol_id INT NOT NULL, description VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, INDEX IDX_3F9D89559F2BFB7A (vol_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vol ADD CONSTRAINT FK_95C97EB52FBE437 FOREIGN KEY (compagnie_id) REFERENCES compagnieaerienne (id)');
        $this->addSql('ALTER TABLE voyage ADD CONSTRAINT FK_3F9D89559F2BFB7A FOREIGN KEY (vol_id) REFERENCES vol (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vol DROP FOREIGN KEY FK_95C97EB52FBE437');
        $this->addSql('ALTER TABLE voyage DROP FOREIGN KEY FK_3F9D89559F2BFB7A');
        $this->addSql('DROP TABLE compagnieaerienne');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE vol');
        $this->addSql('DROP TABLE voyage');
    }
}
