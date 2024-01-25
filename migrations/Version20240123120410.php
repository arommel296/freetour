<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240123120410 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE usuario ADD reserva_id INT NOT NULL');
        $this->addSql('ALTER TABLE usuario ADD CONSTRAINT FK_2265B05DD67139E8 FOREIGN KEY (reserva_id) REFERENCES reserva (id)');
        $this->addSql('CREATE INDEX IDX_2265B05DD67139E8 ON usuario (reserva_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE usuario DROP FOREIGN KEY FK_2265B05DD67139E8');
        $this->addSql('DROP INDEX IDX_2265B05DD67139E8 ON usuario');
        $this->addSql('ALTER TABLE usuario DROP reserva_id');
    }
}
