<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231010093511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9D0968116C6E55B5 ON campus (nom)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_55CAF762A4D60759 ON etat (libelle)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2F577D596C6E55B5 ON lieu (nom)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D79F6B11450FF010 ON participant (telephone)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D79F6B1186CC499D ON participant (pseudo)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_9D0968116C6E55B5 ON campus');
        $this->addSql('DROP INDEX UNIQ_55CAF762A4D60759 ON etat');
        $this->addSql('DROP INDEX UNIQ_2F577D596C6E55B5 ON lieu');
        $this->addSql('DROP INDEX UNIQ_D79F6B11450FF010 ON participant');
        $this->addSql('DROP INDEX UNIQ_D79F6B1186CC499D ON participant');
    }
}
