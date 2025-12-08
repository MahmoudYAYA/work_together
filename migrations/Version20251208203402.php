<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251208203402 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE baie CHANGE numero numero VARCHAR(10) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_709904BAF55AE19E ON baie (numero)');
        $this->addSql('ALTER TABLE unite ADD nom_personnalise VARCHAR(150) DEFAULT NULL, ADD baie_id INT NOT NULL, DROP nom, DROP numero_unite, CHANGE numero numero VARCHAR(3) NOT NULL, CHANGE etat etat VARCHAR(20) NOT NULL, CHANGE statut statut VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE unite ADD CONSTRAINT FK_1D64C11843375062 FOREIGN KEY (baie_id) REFERENCES baie (id)');
        $this->addSql('CREATE INDEX IDX_1D64C11843375062 ON unite (baie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_709904BAF55AE19E ON baie');
        $this->addSql('ALTER TABLE baie CHANGE numero numero INT NOT NULL');
        $this->addSql('ALTER TABLE unite DROP FOREIGN KEY FK_1D64C11843375062');
        $this->addSql('DROP INDEX IDX_1D64C11843375062 ON unite');
        $this->addSql('ALTER TABLE unite ADD nom VARCHAR(150) NOT NULL, ADD numero_unite VARCHAR(255) NOT NULL, DROP nom_personnalise, DROP baie_id, CHANGE numero numero VARCHAR(255) NOT NULL, CHANGE statut statut VARCHAR(255) NOT NULL, CHANGE etat etat VARCHAR(255) NOT NULL');
    }
}
