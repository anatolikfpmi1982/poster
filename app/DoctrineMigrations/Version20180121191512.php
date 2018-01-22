<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180121191512 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE frame_materials DROP FOREIGN KEY FK_CD0C6E873DA5256D');
        $this->addSql('ALTER TABLE frame_materials ADD CONSTRAINT FK_CD0C6E873DA5256D FOREIGN KEY (image_id) REFERENCES images (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE frame_materials DROP FOREIGN KEY FK_CD0C6E873DA5256D');
        $this->addSql('ALTER TABLE frame_materials ADD CONSTRAINT FK_CD0C6E873DA5256D FOREIGN KEY (image_id) REFERENCES images (id)');
    }
}
