<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240123124215 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tour ADD ruta_id INT NOT NULL');
        $this->addSql('ALTER TABLE tour ADD CONSTRAINT FK_6AD1F969ABBC4845 FOREIGN KEY (ruta_id) REFERENCES ruta (id)');
        $this->addSql('CREATE INDEX IDX_6AD1F969ABBC4845 ON tour (ruta_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tour DROP FOREIGN KEY FK_6AD1F969ABBC4845');
        $this->addSql('DROP INDEX IDX_6AD1F969ABBC4845 ON tour');
        $this->addSql('ALTER TABLE tour DROP ruta_id');
    }
}
