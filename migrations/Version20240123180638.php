<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240123180638 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item ADD localidad_id INT NOT NULL');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E67707C89 FOREIGN KEY (localidad_id) REFERENCES localidad (id)');
        $this->addSql('CREATE INDEX IDX_1F1B251E67707C89 ON item (localidad_id)');
        $this->addSql('ALTER TABLE reserva ADD n_asistentes INT DEFAULT NULL, ADD fecha_reserva DATETIME NOT NULL');
        $this->addSql('ALTER TABLE ruta ADD localidad_id INT NOT NULL');
        $this->addSql('ALTER TABLE ruta ADD CONSTRAINT FK_C3AEF08C67707C89 FOREIGN KEY (localidad_id) REFERENCES localidad (id)');
        $this->addSql('CREATE INDEX IDX_C3AEF08C67707C89 ON ruta (localidad_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E67707C89');
        $this->addSql('DROP INDEX IDX_1F1B251E67707C89 ON item');
        $this->addSql('ALTER TABLE item DROP localidad_id');
        $this->addSql('ALTER TABLE reserva DROP n_asistentes, DROP fecha_reserva');
        $this->addSql('ALTER TABLE ruta DROP FOREIGN KEY FK_C3AEF08C67707C89');
        $this->addSql('DROP INDEX IDX_C3AEF08C67707C89 ON ruta');
        $this->addSql('ALTER TABLE ruta DROP localidad_id');
    }
}
