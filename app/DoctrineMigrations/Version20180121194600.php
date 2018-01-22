<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180121194600 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE categories_pictures DROP FOREIGN KEY FK_598286F912469DE2');
        $this->addSql('ALTER TABLE categories_pictures DROP FOREIGN KEY FK_598286F9EE45BDBF');
        $this->addSql('ALTER TABLE categories_pictures ADD CONSTRAINT FK_598286F912469DE2 FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categories_pictures ADD CONSTRAINT FK_598286F9EE45BDBF FOREIGN KEY (picture_id) REFERENCES pictures (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE module_types DROP FOREIGN KEY FK_503CF95E3DA5256D');
        $this->addSql('ALTER TABLE module_types ADD CONSTRAINT FK_503CF95E3DA5256D FOREIGN KEY (image_id) REFERENCES images (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE3ADBEBF0');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE3FA3C347');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE50A6570B');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE6E37B28A');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEED2660E80');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEED37F261C');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEEEB9F7BF9');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEEEE45BDBF');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE3ADBEBF0 FOREIGN KEY (underframe_id) REFERENCES underframes (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE3FA3C347 FOREIGN KEY (frame_id) REFERENCES frames (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE50A6570B FOREIGN KEY (frame_material_id) REFERENCES frame_materials (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE6E37B28A FOREIGN KEY (module_type_id) REFERENCES module_types (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEED2660E80 FOREIGN KEY (frame_color_id) REFERENCES frame_colors (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEED37F261C FOREIGN KEY (banner_material_id) REFERENCES banner_materials (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEEB9F7BF9 FOREIGN KEY (own_picture_id) REFERENCES own_pictures (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEEE45BDBF FOREIGN KEY (picture_id) REFERENCES pictures (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE underframes DROP FOREIGN KEY FK_8603EC723DA5256D');
        $this->addSql('ALTER TABLE underframes ADD CONSTRAINT FK_8603EC723DA5256D FOREIGN KEY (image_id) REFERENCES images (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE banner_materials DROP FOREIGN KEY FK_39725E093DA5256D');
        $this->addSql('ALTER TABLE banner_materials ADD CONSTRAINT FK_39725E093DA5256D FOREIGN KEY (image_id) REFERENCES images (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE slider DROP FOREIGN KEY FK_CFC710073DA5256D');
        $this->addSql('ALTER TABLE slider ADD CONSTRAINT FK_CFC710073DA5256D FOREIGN KEY (image_id) REFERENCES images (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categories DROP FOREIGN KEY FK_3AF346689981B510');
        $this->addSql('ALTER TABLE categories ADD CONSTRAINT FK_3AF346689981B510 FOREIGN KEY (parent_category) REFERENCES categories (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE own_pictures DROP FOREIGN KEY FK_7E9138AB3DA5256D');
        $this->addSql('ALTER TABLE own_pictures ADD CONSTRAINT FK_7E9138AB3DA5256D FOREIGN KEY (image_id) REFERENCES images (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE main_menu_items DROP FOREIGN KEY FK_484ABE4912469DE2');
        $this->addSql('ALTER TABLE main_menu_items DROP FOREIGN KEY FK_484ABE493DA5256D');
        $this->addSql('ALTER TABLE main_menu_items DROP FOREIGN KEY FK_484ABE49C4663E4');
        $this->addSql('ALTER TABLE main_menu_items DROP FOREIGN KEY FK_484ABE49EE45BDBF');
        $this->addSql('ALTER TABLE main_menu_items ADD CONSTRAINT FK_484ABE4912469DE2 FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE main_menu_items ADD CONSTRAINT FK_484ABE493DA5256D FOREIGN KEY (image_id) REFERENCES images (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE main_menu_items ADD CONSTRAINT FK_484ABE49C4663E4 FOREIGN KEY (page_id) REFERENCES pages (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE main_menu_items ADD CONSTRAINT FK_484ABE49EE45BDBF FOREIGN KEY (picture_id) REFERENCES pictures (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE pictures DROP FOREIGN KEY FK_8F7C2FC03DA5256D');
        $this->addSql('ALTER TABLE pictures DROP FOREIGN KEY FK_8F7C2FC05FF69B7D');
        $this->addSql('ALTER TABLE pictures DROP FOREIGN KEY FK_8F7C2FC074B286E');
        $this->addSql('ALTER TABLE pictures DROP FOREIGN KEY FK_8F7C2FC0F675F31B');
        $this->addSql('ALTER TABLE pictures ADD CONSTRAINT FK_8F7C2FC03DA5256D FOREIGN KEY (image_id) REFERENCES images (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pictures ADD CONSTRAINT FK_8F7C2FC05FF69B7D FOREIGN KEY (form_id) REFERENCES picture_forms (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE pictures ADD CONSTRAINT FK_8F7C2FC074B286E FOREIGN KEY (popular_id) REFERENCES popular (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE pictures ADD CONSTRAINT FK_8F7C2FC0F675F31B FOREIGN KEY (author_id) REFERENCES authors (id) ON DELETE SET NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE banner_materials DROP FOREIGN KEY FK_39725E093DA5256D');
        $this->addSql('ALTER TABLE banner_materials ADD CONSTRAINT FK_39725E093DA5256D FOREIGN KEY (image_id) REFERENCES images (id)');
        $this->addSql('ALTER TABLE categories DROP FOREIGN KEY FK_3AF346689981B510');
        $this->addSql('ALTER TABLE categories ADD CONSTRAINT FK_3AF346689981B510 FOREIGN KEY (parent_category) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE categories_pictures DROP FOREIGN KEY FK_598286F912469DE2');
        $this->addSql('ALTER TABLE categories_pictures DROP FOREIGN KEY FK_598286F9EE45BDBF');
        $this->addSql('ALTER TABLE categories_pictures ADD CONSTRAINT FK_598286F912469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE categories_pictures ADD CONSTRAINT FK_598286F9EE45BDBF FOREIGN KEY (picture_id) REFERENCES pictures (id)');
        $this->addSql('ALTER TABLE main_menu_items DROP FOREIGN KEY FK_484ABE49C4663E4');
        $this->addSql('ALTER TABLE main_menu_items DROP FOREIGN KEY FK_484ABE4912469DE2');
        $this->addSql('ALTER TABLE main_menu_items DROP FOREIGN KEY FK_484ABE49EE45BDBF');
        $this->addSql('ALTER TABLE main_menu_items DROP FOREIGN KEY FK_484ABE493DA5256D');
        $this->addSql('ALTER TABLE main_menu_items ADD CONSTRAINT FK_484ABE49C4663E4 FOREIGN KEY (page_id) REFERENCES pages (id)');
        $this->addSql('ALTER TABLE main_menu_items ADD CONSTRAINT FK_484ABE4912469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE main_menu_items ADD CONSTRAINT FK_484ABE49EE45BDBF FOREIGN KEY (picture_id) REFERENCES pictures (id)');
        $this->addSql('ALTER TABLE main_menu_items ADD CONSTRAINT FK_484ABE493DA5256D FOREIGN KEY (image_id) REFERENCES images (id)');
        $this->addSql('ALTER TABLE module_types DROP FOREIGN KEY FK_503CF95E3DA5256D');
        $this->addSql('ALTER TABLE module_types ADD CONSTRAINT FK_503CF95E3DA5256D FOREIGN KEY (image_id) REFERENCES images (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEED37F261C');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE50A6570B');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE3ADBEBF0');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEED2660E80');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE3FA3C347');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE6E37B28A');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEEEB9F7BF9');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEEEE45BDBF');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEED37F261C FOREIGN KEY (banner_material_id) REFERENCES banner_materials (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE50A6570B FOREIGN KEY (frame_material_id) REFERENCES frame_materials (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE3ADBEBF0 FOREIGN KEY (underframe_id) REFERENCES underframes (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEED2660E80 FOREIGN KEY (frame_color_id) REFERENCES frame_colors (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE3FA3C347 FOREIGN KEY (frame_id) REFERENCES frames (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE6E37B28A FOREIGN KEY (module_type_id) REFERENCES module_types (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEEB9F7BF9 FOREIGN KEY (own_picture_id) REFERENCES own_pictures (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEEE45BDBF FOREIGN KEY (picture_id) REFERENCES pictures (id)');
        $this->addSql('ALTER TABLE own_pictures DROP FOREIGN KEY FK_7E9138AB3DA5256D');
        $this->addSql('ALTER TABLE own_pictures ADD CONSTRAINT FK_7E9138AB3DA5256D FOREIGN KEY (image_id) REFERENCES images (id)');
        $this->addSql('ALTER TABLE pictures DROP FOREIGN KEY FK_8F7C2FC03DA5256D');
        $this->addSql('ALTER TABLE pictures DROP FOREIGN KEY FK_8F7C2FC0F675F31B');
        $this->addSql('ALTER TABLE pictures DROP FOREIGN KEY FK_8F7C2FC074B286E');
        $this->addSql('ALTER TABLE pictures DROP FOREIGN KEY FK_8F7C2FC05FF69B7D');
        $this->addSql('ALTER TABLE pictures ADD CONSTRAINT FK_8F7C2FC03DA5256D FOREIGN KEY (image_id) REFERENCES images (id)');
        $this->addSql('ALTER TABLE pictures ADD CONSTRAINT FK_8F7C2FC0F675F31B FOREIGN KEY (author_id) REFERENCES authors (id)');
        $this->addSql('ALTER TABLE pictures ADD CONSTRAINT FK_8F7C2FC074B286E FOREIGN KEY (popular_id) REFERENCES popular (id)');
        $this->addSql('ALTER TABLE pictures ADD CONSTRAINT FK_8F7C2FC05FF69B7D FOREIGN KEY (form_id) REFERENCES picture_forms (id)');
        $this->addSql('ALTER TABLE slider DROP FOREIGN KEY FK_CFC710073DA5256D');
        $this->addSql('ALTER TABLE slider ADD CONSTRAINT FK_CFC710073DA5256D FOREIGN KEY (image_id) REFERENCES images (id)');
        $this->addSql('ALTER TABLE underframes DROP FOREIGN KEY FK_8603EC723DA5256D');
        $this->addSql('ALTER TABLE underframes ADD CONSTRAINT FK_8603EC723DA5256D FOREIGN KEY (image_id) REFERENCES images (id)');
    }
}
