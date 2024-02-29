<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240221111126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE association CHANGE rib rib INT NOT NULL');
        $this->addSql('ALTER TABLE don_bien_materiel CHANGE statut_don statut_don TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE association CHANGE rib rib BIGINT NOT NULL');
        $this->addSql('ALTER TABLE don_bien_materiel CHANGE statut_don statut_don TINYINT(1) DEFAULT NULL');
    }
}
