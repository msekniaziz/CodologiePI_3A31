<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307122333 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit_troc_with DROP FOREIGN KEY FK_D1AD6A869019FF49');
        $this->addSql('DROP INDEX UNIQ_D1AD6A869019FF49 ON produit_troc_with');
        $this->addSql('ALTER TABLE produit_troc_with DROP user_id_tchange_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit_troc_with ADD user_id_tchange_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit_troc_with ADD CONSTRAINT FK_D1AD6A869019FF49 FOREIGN KEY (user_id_tchange_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D1AD6A869019FF49 ON produit_troc_with (user_id_tchange_id)');
    }
}
