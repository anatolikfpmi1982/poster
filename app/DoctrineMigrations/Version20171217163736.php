<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171217163736 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE main_menu_items ADD category_id INT DEFAULT NULL, ADD picture_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE main_menu_items ADD CONSTRAINT FK_484ABE4912469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE main_menu_items ADD CONSTRAINT FK_484ABE49EE45BDBF FOREIGN KEY (picture_id) REFERENCES pictures (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_484ABE4912469DE2 ON main_menu_items (category_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_484ABE49EE45BDBF ON main_menu_items (picture_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE main_menu_items DROP FOREIGN KEY FK_484ABE4912469DE2');
        $this->addSql('ALTER TABLE main_menu_items DROP FOREIGN KEY FK_484ABE49EE45BDBF');
        $this->addSql('DROP INDEX UNIQ_484ABE4912469DE2 ON main_menu_items');
        $this->addSql('DROP INDEX UNIQ_484ABE49EE45BDBF ON main_menu_items');
        $this->addSql('ALTER TABLE main_menu_items DROP category_id, DROP picture_id');
    }
}
