<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240222131153 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prod_r ADD image_name VARCHAR(255) DEFAULT NULL, ADD last_update DATE DEFAULT NULL, CHANGE justificatif justificatif VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE nb_points nb_points INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prod_r DROP image_name, DROP last_update, CHANGE justificatif justificatif VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE nb_points nb_points INT DEFAULT NULL');
    }
}
