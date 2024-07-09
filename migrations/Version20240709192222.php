<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240709192222 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE action (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE appointment (id INT AUTO_INCREMENT NOT NULL, receptionist_id INT NOT NULL, conclusion LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_FE38F844BD690E33 (receptionist_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE appointment_action (appointment_id INT NOT NULL, action_id INT NOT NULL, INDEX IDX_9233DCE5B533F9 (appointment_id), INDEX IDX_9233DC9D32F035 (action_id), PRIMARY KEY(appointment_id, action_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844BD690E33 FOREIGN KEY (receptionist_id) REFERENCES doctor_patient (id)');
        $this->addSql('ALTER TABLE appointment_action ADD CONSTRAINT FK_9233DCE5B533F9 FOREIGN KEY (appointment_id) REFERENCES appointment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE appointment_action ADD CONSTRAINT FK_9233DC9D32F035 FOREIGN KEY (action_id) REFERENCES action (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F844BD690E33');
        $this->addSql('ALTER TABLE appointment_action DROP FOREIGN KEY FK_9233DCE5B533F9');
        $this->addSql('ALTER TABLE appointment_action DROP FOREIGN KEY FK_9233DC9D32F035');
        $this->addSql('DROP TABLE action');
        $this->addSql('DROP TABLE appointment');
        $this->addSql('DROP TABLE appointment_action');
    }
}
