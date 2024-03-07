<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307163913 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE association ADD description_asso VARCHAR(500) NOT NULL, CHANGE rib rib INT NOT NULL');
        $this->addSql('ALTER TABLE prod_r CHANGE justificatif justificatif VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE produit_troc_with DROP FOREIGN KEY FK_D1AD6A8666583D84');
        $this->addSql('ALTER TABLE produit_troc_with ADD CONSTRAINT FK_D1AD6A8666583D84 FOREIGN KEY (prod_id_troc_id) REFERENCES produit_troc (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE association DROP description_asso, CHANGE rib rib BIGINT NOT NULL');
        $this->addSql('ALTER TABLE produit_troc_with DROP FOREIGN KEY FK_D1AD6A8666583D84');
        $this->addSql('ALTER TABLE produit_troc_with ADD CONSTRAINT FK_D1AD6A8666583D84 FOREIGN KEY (prod_id_troc_id) REFERENCES produit_troc (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE prod_r CHANGE justificatif justificatif VARCHAR(255) DEFAULT NULL');
    }
}
