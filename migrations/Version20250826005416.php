<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250826005416 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE course_subscription (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, course_id INT NOT NULL, INDEX IDX_220C1C2EA76ED395 (user_id), INDEX IDX_220C1C2E591CC992 (course_id), UNIQUE INDEX uniq_user_course (user_id, course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE course_subscription ADD CONSTRAINT FK_220C1C2EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE course_subscription ADD CONSTRAINT FK_220C1C2E591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course_subscription DROP FOREIGN KEY FK_220C1C2EA76ED395');
        $this->addSql('ALTER TABLE course_subscription DROP FOREIGN KEY FK_220C1C2E591CC992');
        $this->addSql('DROP TABLE course_subscription');
    }
}
