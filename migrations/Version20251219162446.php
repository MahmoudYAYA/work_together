<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251219162446 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
        $this->addSql('DROP INDEX IDX_6EEAA67D1463CD2B ON commande');
        $this->addSql('ALTER TABLE commande DROP clientss_id');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D4CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DAB014612 FOREIGN KEY (clients_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955EC4A74AB FOREIGN KEY (unite_id) REFERENCES unite (id)');
        $this->addSql('ALTER TABLE ticket_support ADD CONSTRAINT FK_8CC8B2F7EC4A74AB FOREIGN KEY (unite_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE ticket_support ADD CONSTRAINT FK_8CC8B2F719EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE unite ADD CONSTRAINT FK_1D64C11843375062 FOREIGN KEY (baie_id) REFERENCES baie (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455B83297E7');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D82EA2E54');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D4CC8505A');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DAB014612');
        $this->addSql('ALTER TABLE commande ADD clientss_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_6EEAA67D1463CD2B ON commande (clientss_id)');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955EC4A74AB');
        $this->addSql('ALTER TABLE ticket_support DROP FOREIGN KEY FK_8CC8B2F7EC4A74AB');
        $this->addSql('ALTER TABLE ticket_support DROP FOREIGN KEY FK_8CC8B2F719EB6921');
        $this->addSql('ALTER TABLE unite DROP FOREIGN KEY FK_1D64C11843375062');
    }
}
