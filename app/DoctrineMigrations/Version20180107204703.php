<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180107204703 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE orders (id INT AUTO_INCREMENT NOT NULL, banner_material_id INT DEFAULT NULL, frame_material_id INT DEFAULT NULL, underframe_id INT DEFAULT NULL, frame_color_id INT DEFAULT NULL, module_type_id INT DEFAULT NULL, picture_id INT DEFAULT NULL, fullname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, address2 VARCHAR(255) DEFAULT NULL, company VARCHAR(255) DEFAULT NULL, fax VARCHAR(255) DEFAULT NULL, comment LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, is_active TINYINT(1) NOT NULL, height INT NOT NULL, width INT NOT NULL, type INT DEFAULT NULL, INDEX IDX_E52FFDEED37F261C (banner_material_id), INDEX IDX_E52FFDEE50A6570B (frame_material_id), INDEX IDX_E52FFDEE3ADBEBF0 (underframe_id), INDEX IDX_E52FFDEED2660E80 (frame_color_id), INDEX IDX_E52FFDEE6E37B28A (module_type_id), INDEX IDX_E52FFDEEEE45BDBF (picture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEED37F261C FOREIGN KEY (banner_material_id) REFERENCES banner_materials (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE50A6570B FOREIGN KEY (frame_material_id) REFERENCES frame_materials (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE3ADBEBF0 FOREIGN KEY (underframe_id) REFERENCES underframes (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEED2660E80 FOREIGN KEY (frame_color_id) REFERENCES frame_colors (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE6E37B28A FOREIGN KEY (module_type_id) REFERENCES module_types (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEEE45BDBF FOREIGN KEY (picture_id) REFERENCES pictures (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE orders');
    }
}
