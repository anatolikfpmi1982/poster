<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180306174819 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE orders ADD material_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEE308AC6F FOREIGN KEY (material_id) REFERENCES materials (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_E52FFDEEE308AC6F ON orders (material_id)');
        $this->addSql('ALTER TABLE frames DROP FOREIGN KEY FK_FE6E893AE308AC6F');
        $this->addSql('DROP INDEX IDX_FE6E893AE308AC6F ON frames');
        $this->addSql('ALTER TABLE frames DROP material_id');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE frames ADD material_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE frames ADD CONSTRAINT FK_FE6E893AE308AC6F FOREIGN KEY (material_id) REFERENCES frame_materials (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_FE6E893AE308AC6F ON frames (material_id)');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEEE308AC6F');
        $this->addSql('DROP INDEX IDX_E52FFDEEE308AC6F ON orders');
        $this->addSql('ALTER TABLE orders DROP material_id');
    }
}
