<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171124224736 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE module_types ADD image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE module_types ADD CONSTRAINT FK_503CF95E3DA5256D FOREIGN KEY (image_id) REFERENCES images (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_503CF95E3DA5256D ON module_types (image_id)');
        $this->addSql('ALTER TABLE underframes ADD image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE underframes ADD CONSTRAINT FK_8603EC723DA5256D FOREIGN KEY (image_id) REFERENCES images (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8603EC723DA5256D ON underframes (image_id)');
        $this->addSql('ALTER TABLE banner_materials ADD image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE banner_materials ADD CONSTRAINT FK_39725E093DA5256D FOREIGN KEY (image_id) REFERENCES images (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_39725E093DA5256D ON banner_materials (image_id)');
        $this->addSql('ALTER TABLE frame_materials ADD image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE frame_materials ADD CONSTRAINT FK_CD0C6E873DA5256D FOREIGN KEY (image_id) REFERENCES images (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CD0C6E873DA5256D ON frame_materials (image_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE banner_materials DROP FOREIGN KEY FK_39725E093DA5256D');
        $this->addSql('DROP INDEX UNIQ_39725E093DA5256D ON banner_materials');
        $this->addSql('ALTER TABLE banner_materials DROP image_id');
        $this->addSql('ALTER TABLE frame_materials DROP FOREIGN KEY FK_CD0C6E873DA5256D');
        $this->addSql('DROP INDEX UNIQ_CD0C6E873DA5256D ON frame_materials');
        $this->addSql('ALTER TABLE frame_materials DROP image_id');
        $this->addSql('ALTER TABLE module_types DROP FOREIGN KEY FK_503CF95E3DA5256D');
        $this->addSql('DROP INDEX UNIQ_503CF95E3DA5256D ON module_types');
        $this->addSql('ALTER TABLE module_types DROP image_id');
        $this->addSql('ALTER TABLE underframes DROP FOREIGN KEY FK_8603EC723DA5256D');
        $this->addSql('DROP INDEX UNIQ_8603EC723DA5256D ON underframes');
        $this->addSql('ALTER TABLE underframes DROP image_id');
    }
}
