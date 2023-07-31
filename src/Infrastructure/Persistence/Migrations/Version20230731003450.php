<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230731003450 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE playlist (id VARCHAR(255) NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(30) NOT NULL, slug VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_D782112D989D9B62 (slug), INDEX IDX_D782112DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE playlist_song (playlist_id VARCHAR(255) NOT NULL, song_id VARCHAR(255) NOT NULL, INDEX IDX_93F4D9C36BBD148 (playlist_id), INDEX IDX_93F4D9C3A0BDB2F3 (song_id), PRIMARY KEY(playlist_id, song_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE song (id VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, artist VARCHAR(150) NOT NULL, album VARCHAR(255) NOT NULL, link_youtube VARCHAR(255) NOT NULL, link_youtube_valid TINYINT(1) NOT NULL, photo_album VARCHAR(255) NOT NULL, is_approved TINYINT(1) NOT NULL, slug VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_33EDEEA1989D9B62 (slug), UNIQUE INDEX unique_song_title_artist (title, artist), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_song (user_id INT NOT NULL, song_id VARCHAR(255) NOT NULL, INDEX IDX_496CA268A76ED395 (user_id), INDEX IDX_496CA268A0BDB2F3 (song_id), PRIMARY KEY(user_id, song_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE playlist ADD CONSTRAINT FK_D782112DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE playlist_song ADD CONSTRAINT FK_93F4D9C36BBD148 FOREIGN KEY (playlist_id) REFERENCES playlist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE playlist_song ADD CONSTRAINT FK_93F4D9C3A0BDB2F3 FOREIGN KEY (song_id) REFERENCES song (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_song ADD CONSTRAINT FK_496CA268A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_song ADD CONSTRAINT FK_496CA268A0BDB2F3 FOREIGN KEY (song_id) REFERENCES song (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE playlist DROP FOREIGN KEY FK_D782112DA76ED395');
        $this->addSql('ALTER TABLE playlist_song DROP FOREIGN KEY FK_93F4D9C36BBD148');
        $this->addSql('ALTER TABLE playlist_song DROP FOREIGN KEY FK_93F4D9C3A0BDB2F3');
        $this->addSql('ALTER TABLE user_song DROP FOREIGN KEY FK_496CA268A76ED395');
        $this->addSql('ALTER TABLE user_song DROP FOREIGN KEY FK_496CA268A0BDB2F3');
        $this->addSql('DROP TABLE playlist');
        $this->addSql('DROP TABLE playlist_song');
        $this->addSql('DROP TABLE song');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_song');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
