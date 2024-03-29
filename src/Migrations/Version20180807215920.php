<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180807215920 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, thread_id INT NOT NULL, author_id INT NOT NULL, category_id INT NOT NULL, titre VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, weight INT DEFAULT 0, removed TINYINT(1) DEFAULT \'0\', date_creation DATETIME NOT NULL, viewcount INT DEFAULT 0, image VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_23A0E66E2904019 (thread_id), INDEX IDX_23A0E66F675F31B (author_id), INDEX IDX_23A0E6612469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, is_icon TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, thread_id INT NOT NULL, author_id INT NOT NULL, content LONGTEXT NOT NULL, date_creation DATETIME NOT NULL, like_counter INT DEFAULT 0, INDEX IDX_9474526CE2904019 (thread_id), INDEX IDX_9474526CF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact_message (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, sujet VARCHAR(255) NOT NULL, compagnie VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, contenu LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, description LONGTEXT DEFAULT NULL, is_news_forum TINYINT(1) DEFAULT NULL, is_root_forum TINYINT(1) DEFAULT NULL, titre VARCHAR(255) NOT NULL, INDEX IDX_852BBECD727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE thread (id INT AUTO_INCREMENT NOT NULL, forum_id INT NOT NULL, author_id INT NOT NULL, date_creation DATETIME NOT NULL, viewcount INT DEFAULT 0 NOT NULL, titre VARCHAR(255) NOT NULL, INDEX IDX_31204C8329CCBAD0 (forum_id), INDEX IDX_31204C83F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_users (id INT AUTO_INCREMENT NOT NULL, login VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, user_role VARCHAR(255) DEFAULT \'ROLE_USER\', avatar VARCHAR(255) DEFAULT \'http://via.placeholder.com/90x90\', subtitle VARCHAR(255) DEFAULT NULL, signature VARCHAR(255) DEFAULT NULL, localisation VARCHAR(255) DEFAULT NULL, moderation_status INT DEFAULT 0, date_inscription DATETIME NOT NULL, newsletter_subscriber TINYINT(1) DEFAULT \'0\', termsaccepted TINYINT(1) NOT NULL, date_subscription DATETIME DEFAULT NULL, is_active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_C2502824AA08CB10 (login), UNIQUE INDEX UNIQ_C2502824E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66E2904019 FOREIGN KEY (thread_id) REFERENCES thread (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66F675F31B FOREIGN KEY (author_id) REFERENCES app_users (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6612469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CE2904019 FOREIGN KEY (thread_id) REFERENCES thread (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES app_users (id)');
        $this->addSql('ALTER TABLE forum ADD CONSTRAINT FK_852BBECD727ACA70 FOREIGN KEY (parent_id) REFERENCES forum (id)');
        $this->addSql('ALTER TABLE thread ADD CONSTRAINT FK_31204C8329CCBAD0 FOREIGN KEY (forum_id) REFERENCES forum (id)');
        $this->addSql('ALTER TABLE thread ADD CONSTRAINT FK_31204C83F675F31B FOREIGN KEY (author_id) REFERENCES app_users (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6612469DE2');
        $this->addSql('ALTER TABLE forum DROP FOREIGN KEY FK_852BBECD727ACA70');
        $this->addSql('ALTER TABLE thread DROP FOREIGN KEY FK_31204C8329CCBAD0');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66E2904019');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CE2904019');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66F675F31B');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CF675F31B');
        $this->addSql('ALTER TABLE thread DROP FOREIGN KEY FK_31204C83F675F31B');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE contact_message');
        $this->addSql('DROP TABLE forum');
        $this->addSql('DROP TABLE thread');
        $this->addSql('DROP TABLE app_users');
    }
}
