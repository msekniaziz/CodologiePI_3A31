<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240227214413 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, receiver_id_id INT NOT NULL, sender_id_id INT NOT NULL, INDEX IDX_B6BD307FBE20CAB0 (receiver_id_id), INDEX IDX_B6BD307F6061F7CF (sender_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FBE20CAB0 FOREIGN KEY (receiver_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F6061F7CF FOREIGN KEY (sender_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE produit_troc DROP FOREIGN KEY FK_8D2A6E8C79F37AE5');
        $this->addSql('ALTER TABLE produit_troc CHANGE statut statut INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit_troc ADD CONSTRAINT FK_8D2A6E8C79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE produit_troc_with DROP FOREIGN KEY FK_D1AD6A8666583D84');
        $this->addSql('ALTER TABLE produit_troc_with CHANGE nom nom VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE produit_troc_with ADD CONSTRAINT FK_D1AD6A8666583D84 FOREIGN KEY (prod_id_troc_id) REFERENCES produit_troc (id)');
        $this->addSql('ALTER TABLE user CHANGE nb_points nb_points INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FBE20CAB0');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F6061F7CF');
        $this->addSql('DROP TABLE message');
        $this->addSql('ALTER TABLE produit_troc DROP FOREIGN KEY FK_8D2A6E8C79F37AE5');
        $this->addSql('ALTER TABLE produit_troc CHANGE statut statut INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE produit_troc ADD CONSTRAINT FK_8D2A6E8C79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit_troc_with DROP FOREIGN KEY FK_D1AD6A8666583D84');
        $this->addSql('ALTER TABLE produit_troc_with CHANGE nom nom VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE produit_troc_with ADD CONSTRAINT FK_D1AD6A8666583D84 FOREIGN KEY (prod_id_troc_id) REFERENCES produit_troc (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user CHANGE nb_points nb_points INT DEFAULT NULL');
    }
}
