<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180305201236 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE materials (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, is_active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE frames ADD frame_material_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE frames ADD CONSTRAINT FK_FE6E893A50A6570B FOREIGN KEY (frame_material_id) REFERENCES materials (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_FE6E893A50A6570B ON frames (frame_material_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE frames DROP FOREIGN KEY FK_FE6E893A50A6570B');
        $this->addSql('DROP TABLE materials');
        $this->addSql('DROP INDEX IDX_FE6E893A50A6570B ON frames');
        $this->addSql('ALTER TABLE frames DROP frame_material_id');
    }
}
