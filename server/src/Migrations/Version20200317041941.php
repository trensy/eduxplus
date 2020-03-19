<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200317041941 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_1BF018B9C74F2195 ON base_user');
        $this->addSql('ALTER TABLE base_user ADD original_refresh_token CHAR(36) DEFAULT NULL COMMENT \'token刷新码(DC2Type:guid)\', CHANGE refresh_token refresh_token VARCHAR(255) NOT NULL COMMENT \'token刷新码加密数据\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1BF018B982EF8CA ON base_user (original_refresh_token)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_1BF018B982EF8CA ON base_user');
        $this->addSql('ALTER TABLE base_user DROP original_refresh_token, CHANGE refresh_token refresh_token CHAR(36) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'token刷新码(DC2Type:guid)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1BF018B9C74F2195 ON base_user (refresh_token)');
    }
}
