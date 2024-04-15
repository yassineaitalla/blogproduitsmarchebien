<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240415152055 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE devis (id INT AUTO_INCREMENT NOT NULL, idpanier_id INT NOT NULL, idclient_id INT NOT NULL, INDEX IDX_8B27C52B89663B89 (idpanier_id), INDEX IDX_8B27C52B67F0C0D4 (idclient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B89663B89 FOREIGN KEY (idpanier_id) REFERENCES panier (id)');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B67F0C0D4 FOREIGN KEY (idclient_id) REFERENCES client (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52B89663B89');
        $this->addSql('ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52B67F0C0D4');
        $this->addSql('DROP TABLE devis');
    }
}
