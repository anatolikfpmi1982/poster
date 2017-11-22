<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171120224315 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE categories DROP FOREIGN KEY FK_3AF34668796A8F92');
        $this->addSql('CREATE TABLE main_menu_items (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, target VARCHAR(255) NOT NULL, weight INT NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, is_active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE pictures_categories');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, parent_category_id INT DEFAULT NULL, title VARCHAR(130) NOT NULL COLLATE utf8_unicode_ci, slug VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, is_active TINYINT(1) NOT NULL, description LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, INDEX IDX_3AF34668796A8F92 (parent_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pictures_categories (picture_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_DACB658AEE45BDBF (picture_id), INDEX IDX_DACB658A12469DE2 (category_id), PRIMARY KEY(picture_id, category_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categories ADD CONSTRAINT FK_3AF34668796A8F92 FOREIGN KEY (parent_category_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE pictures_categories ADD CONSTRAINT FK_DACB658AEE45BDBF FOREIGN KEY (picture_id) REFERENCES pictures (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE main_menu_items');
    }
}
