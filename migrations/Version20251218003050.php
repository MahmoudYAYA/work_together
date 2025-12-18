<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251218003050 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE baie (id INT AUTO_INCREMENT NOT NULL, numero VARCHAR(10) NOT NULL, capacite_totale INT NOT NULL, UNIQUE INDEX UNIQ_709904BAF55AE19E (numero), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, societe VARCHAR(255) DEFAULT NULL, telephone VARCHAR(20) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, ville VARCHAR(100) DEFAULT NULL, pays VARCHAR(100) DEFAULT NULL, date_creation DATETIME NOT NULL, actif TINYINT(1) NOT NULL, reservation_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_C7440455E7927C74 (email), INDEX IDX_C7440455B83297E7 (reservation_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, numero_commande VARCHAR(50) NOT NULL, date_commande DATETIME NOT NULL, montant_total NUMERIC(10, 2) NOT NULL, montant_tva NUMERIC(10, 2) NOT NULL, type_facture VARCHAR(255) NOT NULL, date_debut_service DATE NOT NULL, date_fin_service DATE NOT NULL, statut VARCHAR(50) DEFAULT \'En attente paiement\' NOT NULL, type_paiement VARCHAR(50) DEFAULT NULL, numero_facture VARCHAR(50) DEFAULT NULL, date_paiement DATETIME DEFAULT NULL, date_creation DATETIME NOT NULL, client_id INT NOT NULL, offre_id INT NOT NULL, INDEX IDX_6EEAA67D19EB6921 (client_id), INDEX IDX_6EEAA67D4CC8505A (offre_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE intervention (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(100) DEFAULT NULL, titre VARCHAR(180) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE offre (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(180) NOT NULL, description VARCHAR(255) DEFAULT NULL, nombre_unites INT NOT NULL, prix_mensuel NUMERIC(10, 0) NOT NULL, prix_annuelle NUMERIC(10, 0) NOT NULL, redction_annuelle INT DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, unite_id INT DEFAULT NULL, INDEX IDX_42C84955EC4A74AB (unite_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE ticket_support (id INT AUTO_INCREMENT NOT NULL, numero_ticket VARCHAR(255) NOT NULL, sujet VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, priorite INT DEFAULT NULL, date_creation DATETIME NOT NULL, date_time DATETIME DEFAULT NULL, unite_id INT NOT NULL, client_id INT NOT NULL, INDEX IDX_8CC8B2F7EC4A74AB (unite_id), INDEX IDX_8CC8B2F719EB6921 (client_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE unite (id INT AUTO_INCREMENT NOT NULL, numero VARCHAR(3) NOT NULL, statut VARCHAR(20) NOT NULL, etat VARCHAR(20) NOT NULL, nom_personnalise VARCHAR(150) DEFAULT NULL, baie_id INT DEFAULT NULL, INDEX IDX_1D64C11843375062 (baie_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, date_creation DATETIME NOT NULL, date_modification DATETIME NOT NULL, actif TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D4CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955EC4A74AB FOREIGN KEY (unite_id) REFERENCES unite (id)');
        $this->addSql('ALTER TABLE ticket_support ADD CONSTRAINT FK_8CC8B2F7EC4A74AB FOREIGN KEY (unite_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE ticket_support ADD CONSTRAINT FK_8CC8B2F719EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE unite ADD CONSTRAINT FK_1D64C11843375062 FOREIGN KEY (baie_id) REFERENCES baie (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455B83297E7');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D19EB6921');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D4CC8505A');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955EC4A74AB');
        $this->addSql('ALTER TABLE ticket_support DROP FOREIGN KEY FK_8CC8B2F7EC4A74AB');
        $this->addSql('ALTER TABLE ticket_support DROP FOREIGN KEY FK_8CC8B2F719EB6921');
        $this->addSql('ALTER TABLE unite DROP FOREIGN KEY FK_1D64C11843375062');
        $this->addSql('DROP TABLE baie');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE intervention');
        $this->addSql('DROP TABLE offre');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE ticket_support');
        $this->addSql('DROP TABLE unite');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
