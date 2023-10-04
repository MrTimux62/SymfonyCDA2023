<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231004112414 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participant CHANGE is_actif actif TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE sortie DROP FOREIGN KEY FK_3C3FD3F29D1C3019');
        $this->addSql('DROP INDEX IDX_3C3FD3F29D1C3019 ON sortie');
        $this->addSql('ALTER TABLE sortie CHANGE participant_id participant_organisateur_id INT NOT NULL');
        $this->addSql('ALTER TABLE sortie ADD CONSTRAINT FK_3C3FD3F217AE6A42 FOREIGN KEY (participant_organisateur_id) REFERENCES participant (id)');
        $this->addSql('CREATE INDEX IDX_3C3FD3F217AE6A42 ON sortie (participant_organisateur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participant CHANGE actif is_actif TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE sortie DROP FOREIGN KEY FK_3C3FD3F217AE6A42');
        $this->addSql('DROP INDEX IDX_3C3FD3F217AE6A42 ON sortie');
        $this->addSql('ALTER TABLE sortie CHANGE participant_organisateur_id participant_id INT NOT NULL');
        $this->addSql('ALTER TABLE sortie ADD CONSTRAINT FK_3C3FD3F29D1C3019 FOREIGN KEY (participant_id) REFERENCES participant (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_3C3FD3F29D1C3019 ON sortie (participant_id)');
    }
}
