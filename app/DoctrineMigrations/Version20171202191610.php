<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171202191610 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE picture_forms (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, service_name VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, is_active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_7DE59CB93DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE picture_forms ADD CONSTRAINT FK_7DE59CB93DA5256D FOREIGN KEY (image_id) REFERENCES images (id)');
        $this->addSql('ALTER TABLE pictures DROP FOREIGN KEY FK_8F7C2FC0121A8AB9');
        $this->addSql('DROP INDEX IDX_8F7C2FC0121A8AB9 ON pictures');
        $this->addSql('ALTER TABLE pictures CHANGE picture_color_id form_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pictures ADD CONSTRAINT FK_8F7C2FC05FF69B7D FOREIGN KEY (form_id) REFERENCES picture_forms (id)');
        $this->addSql('CREATE INDEX IDX_8F7C2FC05FF69B7D ON pictures (form_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pictures DROP FOREIGN KEY FK_8F7C2FC05FF69B7D');
        $this->addSql('DROP TABLE picture_forms');
        $this->addSql('DROP INDEX IDX_8F7C2FC05FF69B7D ON pictures');
        $this->addSql('ALTER TABLE pictures CHANGE form_id picture_color_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pictures ADD CONSTRAINT FK_8F7C2FC0121A8AB9 FOREIGN KEY (picture_color_id) REFERENCES picture_colors (id)');
        $this->addSql('CREATE INDEX IDX_8F7C2FC0121A8AB9 ON pictures (picture_color_id)');
    }
}
