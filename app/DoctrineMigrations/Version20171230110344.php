<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171230110344 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pictures DROP FOREIGN KEY FK_8F7C2FC077406EC5');
        $this->addSql('ALTER TABLE pictures DROP FOREIGN KEY FK_8F7C2FC0B0534DEF');
        $this->addSql('ALTER TABLE pictures DROP FOREIGN KEY FK_8F7C2FC0B0CC1367');
        $this->addSql('DROP INDEX UNIQ_8F7C2FC0B0CC1367 ON pictures');
        $this->addSql('DROP INDEX UNIQ_8F7C2FC077406EC5 ON pictures');
        $this->addSql('DROP INDEX UNIQ_8F7C2FC0B0534DEF ON pictures');
        $this->addSql('ALTER TABLE pictures DROP image_module_id, DROP image_frame_id, DROP image_banner_id');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pictures ADD image_module_id INT DEFAULT NULL, ADD image_frame_id INT DEFAULT NULL, ADD image_banner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pictures ADD CONSTRAINT FK_8F7C2FC077406EC5 FOREIGN KEY (image_module_id) REFERENCES images (id)');
        $this->addSql('ALTER TABLE pictures ADD CONSTRAINT FK_8F7C2FC0B0534DEF FOREIGN KEY (image_frame_id) REFERENCES images (id)');
        $this->addSql('ALTER TABLE pictures ADD CONSTRAINT FK_8F7C2FC0B0CC1367 FOREIGN KEY (image_banner_id) REFERENCES images (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8F7C2FC0B0CC1367 ON pictures (image_banner_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8F7C2FC077406EC5 ON pictures (image_module_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8F7C2FC0B0534DEF ON pictures (image_frame_id)');
    }
}
