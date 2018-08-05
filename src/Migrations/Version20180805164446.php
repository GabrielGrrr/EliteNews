<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180805164446 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE contact_message (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, sujet VARCHAR(255) NOT NULL, compagnie VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, contenu LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article CHANGE removed removed TINYINT(1) DEFAULT \'0\', CHANGE viewcount viewcount INT DEFAULT 0');
        $this->addSql('ALTER TABLE app_users CHANGE user_role user_role VARCHAR(255) DEFAULT \'ROLE_USER\', CHANGE avatar avatar VARCHAR(255) DEFAULT \'http://via.placeholder.com/90x90\', CHANGE moderation_status moderation_status INT DEFAULT 0, CHANGE newsletter_subscriber newsletter_subscriber TINYINT(1) DEFAULT \'0\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE contact_message');
        $this->addSql('ALTER TABLE app_users CHANGE user_role user_role VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE avatar avatar VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE moderation_status moderation_status INT NOT NULL, CHANGE newsletter_subscriber newsletter_subscriber TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE article CHANGE removed removed TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE viewcount viewcount INT DEFAULT 0 NOT NULL');
    }
}
