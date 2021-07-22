<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210721134314 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity CHANGE status status INT NOT NULL');
        $this->addSql('DROP INDEX licence_plate_plate_number_IDX ON licence_plate');
        $this->addSql('ALTER TABLE licence_plate CHANGE plate_number plate_number VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user ADD profile_picture VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity CHANGE status status INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE licence_plate CHANGE plate_number plate_number VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE UNIQUE INDEX licence_plate_plate_number_IDX ON licence_plate (plate_number, user_ids)');
        $this->addSql('ALTER TABLE `user` DROP profile_picture');
    }
}
