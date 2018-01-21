<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180121185225 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE frames DROP FOREIGN KEY FK_FE6E893A7ADA1FB5');
        $this->addSql('ALTER TABLE frames DROP FOREIGN KEY FK_FE6E893AE308AC6F');
        $this->addSql('ALTER TABLE frames ADD CONSTRAINT FK_FE6E893A7ADA1FB5 FOREIGN KEY (color_id) REFERENCES frame_colors (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE frames ADD CONSTRAINT FK_FE6E893AE308AC6F FOREIGN KEY (material_id) REFERENCES frame_materials (id) ON DELETE SET NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE frames DROP FOREIGN KEY FK_FE6E893A7ADA1FB5');
        $this->addSql('ALTER TABLE frames DROP FOREIGN KEY FK_FE6E893AE308AC6F');
        $this->addSql('ALTER TABLE frames ADD CONSTRAINT FK_FE6E893A7ADA1FB5 FOREIGN KEY (color_id) REFERENCES frame_colors (id)');
        $this->addSql('ALTER TABLE frames ADD CONSTRAINT FK_FE6E893AE308AC6F FOREIGN KEY (material_id) REFERENCES frame_materials (id)');
    }
}
