<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251211232839 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE offre (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(180) NOT NULL, description VARCHAR(255) DEFAULT NULL, nombre_unites INT NOT NULL, prix_mensuel NUMERIC(10, 0) NOT NULL, prix_annuelle NUMERIC(10, 0) NOT NULL, redction_annuelle INT DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE unite ADD CONSTRAINT FK_1D64C11843375062 FOREIGN KEY (baie_id) REFERENCES baie (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE offre');
        $this->addSql('ALTER TABLE unite DROP FOREIGN KEY FK_1D64C11843375062');
    }
}
