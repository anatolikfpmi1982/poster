<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171202163832 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE picture_colors (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, code VARCHAR(10) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, is_active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pictures_colors (picture_id INT NOT NULL, color_id INT NOT NULL, INDEX IDX_8872A7D7EE45BDBF (picture_id), INDEX IDX_8872A7D77ADA1FB5 (color_id), PRIMARY KEY(picture_id, color_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pictures_colors ADD CONSTRAINT FK_8872A7D7EE45BDBF FOREIGN KEY (picture_id) REFERENCES pictures (id)');
        $this->addSql('ALTER TABLE pictures_colors ADD CONSTRAINT FK_8872A7D77ADA1FB5 FOREIGN KEY (color_id) REFERENCES picture_colors (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pictures ADD picture_color_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pictures ADD CONSTRAINT FK_8F7C2FC0121A8AB9 FOREIGN KEY (picture_color_id) REFERENCES picture_colors (id)');
        $this->addSql('CREATE INDEX IDX_8F7C2FC0121A8AB9 ON pictures (picture_color_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pictures DROP FOREIGN KEY FK_8F7C2FC0121A8AB9');
        $this->addSql('ALTER TABLE pictures_colors DROP FOREIGN KEY FK_8872A7D77ADA1FB5');
        $this->addSql('DROP TABLE picture_colors');
        $this->addSql('DROP TABLE pictures_colors');
        $this->addSql('DROP INDEX IDX_8F7C2FC0121A8AB9 ON pictures');
        $this->addSql('ALTER TABLE pictures DROP picture_color_id');
    }
}
