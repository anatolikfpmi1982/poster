<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180122070552 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE own_pictures ADD form_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE own_pictures ADD CONSTRAINT FK_7E9138AB5FF69B7D FOREIGN KEY (form_id) REFERENCES picture_forms (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_7E9138AB5FF69B7D ON own_pictures (form_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE own_pictures DROP FOREIGN KEY FK_7E9138AB5FF69B7D');
        $this->addSql('DROP INDEX IDX_7E9138AB5FF69B7D ON own_pictures');
        $this->addSql('ALTER TABLE own_pictures DROP form_id');
    }
}
