<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180805172233 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article CHANGE removed removed TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE viewcount viewcount INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE forum CHANGE Titre titre LONGTEXT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article CHANGE removed removed TINYINT(1) DEFAULT \'0\', CHANGE viewcount viewcount INT DEFAULT 0');
        $this->addSql('ALTER TABLE forum CHANGE titre Titre VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
