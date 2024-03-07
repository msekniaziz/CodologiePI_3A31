<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307121551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonces DROP FOREIGN KEY FK_CB988C6FA76ED395');
        $this->addSql('ALTER TABLE annonces DROP FOREIGN KEY FK_CB988C6FC09A1CAE');
        $this->addSql('ALTER TABLE annonces DROP FOREIGN KEY FK_CB988C6F99DED506');
        $this->addSql('ALTER TABLE annonces ADD CONSTRAINT FK_CB988C6FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE annonces ADD CONSTRAINT FK_CB988C6FC09A1CAE FOREIGN KEY (id_cat_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE annonces ADD CONSTRAINT FK_CB988C6F99DED506 FOREIGN KEY (id_client_id) REFERENCES commandes (id)');
        $this->addSql('ALTER TABLE category CHANGE nbr_annonce nbr_annonce INT NOT NULL');
        $this->addSql('ALTER TABLE prod_r CHANGE justificatif justificatif VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE nb_points nb_points INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonces DROP FOREIGN KEY FK_CB988C6FA76ED395');
        $this->addSql('ALTER TABLE annonces DROP FOREIGN KEY FK_CB988C6F99DED506');
        $this->addSql('ALTER TABLE annonces DROP FOREIGN KEY FK_CB988C6FC09A1CAE');
        $this->addSql('ALTER TABLE annonces ADD CONSTRAINT FK_CB988C6FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE annonces ADD CONSTRAINT FK_CB988C6F99DED506 FOREIGN KEY (id_client_id) REFERENCES commandes (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE annonces ADD CONSTRAINT FK_CB988C6FC09A1CAE FOREIGN KEY (id_cat_id) REFERENCES category (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category CHANGE nbr_annonce nbr_annonce INT DEFAULT NULL');
        $this->addSql('ALTER TABLE prod_r CHANGE justificatif justificatif VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE nb_points nb_points INT DEFAULT NULL');
    }
}
