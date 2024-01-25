<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240123114841 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE informe (id INT AUTO_INCREMENT NOT NULL, tour_id INT NOT NULL, foto LONGTEXT NOT NULL, observaciones LONGTEXT DEFAULT NULL, dinero DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_7E75E1D915ED8D43 (tour_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(100) NOT NULL, coordenadas VARCHAR(100) NOT NULL, descripcion LONGTEXT NOT NULL, foto LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE localidad (id INT AUTO_INCREMENT NOT NULL, provincia_id INT NOT NULL, nombre VARCHAR(100) NOT NULL, INDEX IDX_4F68E0104E7121AF (provincia_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE provincia (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reserva (id INT AUTO_INCREMENT NOT NULL, tour_id INT NOT NULL, n_entradas INT NOT NULL, INDEX IDX_188D2E3B15ED8D43 (tour_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ruta (id INT AUTO_INCREMENT NOT NULL, localidad_id INT NOT NULL, nombre VARCHAR(100) NOT NULL, coord_inicio VARCHAR(100) NOT NULL, descripcion LONGTEXT NOT NULL, text LONGTEXT DEFAULT NULL, INDEX IDX_C3AEF08C67707C89 (localidad_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ruta_item (ruta_id INT NOT NULL, item_id INT NOT NULL, INDEX IDX_837FEAD6ABBC4845 (ruta_id), INDEX IDX_837FEAD6126F525E (item_id), PRIMARY KEY(ruta_id, item_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tour (id INT AUTO_INCREMENT NOT NULL, fecha_hora DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuario (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nombre VARCHAR(100) NOT NULL, apellidos VARCHAR(100) NOT NULL, foto LONGTEXT NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_2265B05DE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE valoracion (id INT AUTO_INCREMENT NOT NULL, reserva_id INT NOT NULL, nota_guia INT NOT NULL, nota_ruta INT NOT NULL, comentario LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_6D3DE0F4D67139E8 (reserva_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE informe ADD CONSTRAINT FK_7E75E1D915ED8D43 FOREIGN KEY (tour_id) REFERENCES tour (id)');
        $this->addSql('ALTER TABLE localidad ADD CONSTRAINT FK_4F68E0104E7121AF FOREIGN KEY (provincia_id) REFERENCES provincia (id)');
        $this->addSql('ALTER TABLE reserva ADD CONSTRAINT FK_188D2E3B15ED8D43 FOREIGN KEY (tour_id) REFERENCES tour (id)');
        $this->addSql('ALTER TABLE ruta ADD CONSTRAINT FK_C3AEF08C67707C89 FOREIGN KEY (localidad_id) REFERENCES localidad (id)');
        $this->addSql('ALTER TABLE ruta_item ADD CONSTRAINT FK_837FEAD6ABBC4845 FOREIGN KEY (ruta_id) REFERENCES ruta (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ruta_item ADD CONSTRAINT FK_837FEAD6126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE valoracion ADD CONSTRAINT FK_6D3DE0F4D67139E8 FOREIGN KEY (reserva_id) REFERENCES reserva (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE informe DROP FOREIGN KEY FK_7E75E1D915ED8D43');
        $this->addSql('ALTER TABLE localidad DROP FOREIGN KEY FK_4F68E0104E7121AF');
        $this->addSql('ALTER TABLE reserva DROP FOREIGN KEY FK_188D2E3B15ED8D43');
        $this->addSql('ALTER TABLE ruta DROP FOREIGN KEY FK_C3AEF08C67707C89');
        $this->addSql('ALTER TABLE ruta_item DROP FOREIGN KEY FK_837FEAD6ABBC4845');
        $this->addSql('ALTER TABLE ruta_item DROP FOREIGN KEY FK_837FEAD6126F525E');
        $this->addSql('ALTER TABLE valoracion DROP FOREIGN KEY FK_6D3DE0F4D67139E8');
        $this->addSql('DROP TABLE informe');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE localidad');
        $this->addSql('DROP TABLE provincia');
        $this->addSql('DROP TABLE reserva');
        $this->addSql('DROP TABLE ruta');
        $this->addSql('DROP TABLE ruta_item');
        $this->addSql('DROP TABLE tour');
        $this->addSql('DROP TABLE usuario');
        $this->addSql('DROP TABLE valoracion');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
