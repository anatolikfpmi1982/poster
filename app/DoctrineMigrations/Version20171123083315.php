<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171123083315 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(300) NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, is_active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pictures3_categories (picture_id INT NOT NULL, category3_id INT NOT NULL, INDEX IDX_3A042CEEEE45BDBF (picture_id), INDEX IDX_3A042CEE327320D1 (category3_id), PRIMARY KEY(picture_id, category3_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pictures3_categories ADD CONSTRAINT FK_3A042CEEEE45BDBF FOREIGN KEY (picture_id) REFERENCES pictures (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pictures3_categories ADD CONSTRAINT FK_3A042CEE327320D1 FOREIGN KEY (category3_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE pictures2_categories');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pictures3_categories DROP FOREIGN KEY FK_3A042CEE327320D1');
        $this->addSql('CREATE TABLE pictures2_categories (picture_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_A1A16081EE45BDBF (picture_id), INDEX IDX_A1A1608112469DE2 (category_id), PRIMARY KEY(picture_id, category_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pictures2_categories ADD CONSTRAINT FK_A1A1608112469DE2 FOREIGN KEY (category_id) REFERENCES classification__category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pictures2_categories ADD CONSTRAINT FK_A1A16081EE45BDBF FOREIGN KEY (picture_id) REFERENCES pictures (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE pictures3_categories');
    }
}
