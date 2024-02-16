<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240214121023 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commandes (id INT AUTO_INCREMENT NOT NULL, id_user_c_id INT DEFAULT NULL, date DATE NOT NULL, etat TINYINT(1) NOT NULL, INDEX IDX_35D4282C6B636B3D (id_user_c_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT FK_35D4282C6B636B3D FOREIGN KEY (id_user_c_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE annonces ADD id_client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE annonces ADD CONSTRAINT FK_CB988C6F99DED506 FOREIGN KEY (id_client_id) REFERENCES commandes (id)');
        $this->addSql('CREATE INDEX IDX_CB988C6F99DED506 ON annonces (id_client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonces DROP FOREIGN KEY FK_CB988C6F99DED506');
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282C6B636B3D');
        $this->addSql('DROP TABLE commandes');
        $this->addSql('DROP INDEX IDX_CB988C6F99DED506 ON annonces');
        $this->addSql('ALTER TABLE annonces DROP id_client_id');
    }
}
