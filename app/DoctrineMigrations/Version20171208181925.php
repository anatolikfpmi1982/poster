<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171208181925 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pages CHANGE seo_title seo_title VARCHAR(200) NOT NULL, CHANGE seo_description seo_description LONGTEXT NOT NULL, CHANGE seo_keywords seo_keywords LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE pictures ADD file_name VARCHAR(255) NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pages CHANGE seo_title seo_title VARCHAR(200) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE seo_description seo_description LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE seo_keywords seo_keywords LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE pictures DROP file_name');
    }
}
