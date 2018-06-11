<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180611202110 extends AbstractMigration
{
    /**
     * @param Schema $schema
     *
     * @throws \Doctrine\DBAL\Migrations\AbortMigrationException
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('ALTER TABLE `book` CHANGE COLUMN `isbn` `isbn` VARCHAR(17) NOT NULL COMMENT \'ISBN\' AFTER `publish`, 
                                          CHANGE COLUMN `cover` `cover` VARCHAR(100) NULL COMMENT \'Обложка\' AFTER `page`,
                                          CHANGE COLUMN `publish` `publish` SMALLINT UNSIGNED NOT NULL COMMENT \'Год издания книги\' AFTER `name`');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
