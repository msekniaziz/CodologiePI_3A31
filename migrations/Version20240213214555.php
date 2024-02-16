<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240213214555 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit_troc ADD nom_produit_recherche VARCHAR(255) NOT NULL, CHANGE statut statut INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE nb_points nb_points INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit_troc DROP nom_produit_recherche, CHANGE statut statut INT DEFAULT 1');
        $this->addSql('ALTER TABLE user CHANGE nb_points nb_points INT DEFAULT NULL');
    }
}
