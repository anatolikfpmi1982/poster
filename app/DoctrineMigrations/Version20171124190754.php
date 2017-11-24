<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171124190754 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE banner_materials (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, ratio DOUBLE PRECISION NOT NULL, min_area DOUBLE PRECISION NOT NULL, max_area DOUBLE PRECISION NOT NULL, min_price DOUBLE PRECISION NOT NULL, max_price DOUBLE PRECISION NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, is_active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE frame_materials ADD ratio DOUBLE PRECISION NOT NULL, ADD min_area DOUBLE PRECISION NOT NULL, ADD max_area DOUBLE PRECISION NOT NULL, ADD min_price DOUBLE PRECISION NOT NULL, ADD max_price DOUBLE PRECISION NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE banner_materials');
        $this->addSql('ALTER TABLE frame_materials DROP ratio, DROP min_area, DROP max_area, DROP min_price, DROP max_price');
    }
}
