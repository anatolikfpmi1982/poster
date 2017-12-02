<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171201233015 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE main_menu_items ADD page_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE main_menu_items ADD CONSTRAINT FK_484ABE49C4663E4 FOREIGN KEY (page_id) REFERENCES pages (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_484ABE49C4663E4 ON main_menu_items (page_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE main_menu_items DROP FOREIGN KEY FK_484ABE49C4663E4');
        $this->addSql('DROP INDEX UNIQ_484ABE49C4663E4 ON main_menu_items');
        $this->addSql('ALTER TABLE main_menu_items DROP page_id');
    }
}
