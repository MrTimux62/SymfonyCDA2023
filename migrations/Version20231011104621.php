<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231011104621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES participant (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9D0968116C6E55B5 ON campus (nom)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_55CAF762A4D60759 ON etat (libelle)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2F577D596C6E55B5 ON lieu (nom)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D79F6B11450FF010 ON participant (telephone)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D79F6B1186CC499D ON participant (pseudo)');
        $this->addSql('ALTER TABLE sortie CHANGE duree duree INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP INDEX UNIQ_9D0968116C6E55B5 ON campus');
        $this->addSql('DROP INDEX UNIQ_55CAF762A4D60759 ON etat');
        $this->addSql('DROP INDEX UNIQ_2F577D596C6E55B5 ON lieu');
        $this->addSql('DROP INDEX UNIQ_D79F6B11450FF010 ON participant');
        $this->addSql('DROP INDEX UNIQ_D79F6B1186CC499D ON participant');
        $this->addSql('ALTER TABLE sortie CHANGE duree duree TIME NOT NULL');
    }
}
