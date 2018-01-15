<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180115190641 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE module_types DROP FOREIGN KEY FK_503CF95E3DA5256D');
        $this->addSql('ALTER TABLE module_types ADD CONSTRAINT FK_503CF95E3DA5256D FOREIGN KEY (image_id) REFERENCES images (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE picture_forms DROP FOREIGN KEY FK_7DE59CB93DA5256D');
        $this->addSql('ALTER TABLE picture_forms ADD CONSTRAINT FK_7DE59CB93DA5256D FOREIGN KEY (image_id) REFERENCES images (id) ON DELETE SET NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE module_types DROP FOREIGN KEY FK_503CF95E3DA5256D');
        $this->addSql('ALTER TABLE module_types ADD CONSTRAINT FK_503CF95E3DA5256D FOREIGN KEY (image_id) REFERENCES images (id)');
        $this->addSql('ALTER TABLE picture_forms DROP FOREIGN KEY FK_7DE59CB93DA5256D');
        $this->addSql('ALTER TABLE picture_forms ADD CONSTRAINT FK_7DE59CB93DA5256D FOREIGN KEY (image_id) REFERENCES images (id)');
    }
}
