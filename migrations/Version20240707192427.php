<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240707192427 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE patient ADD patient_id INT NOT NULL, ADD doctor_id INT NOT NULL, DROP username');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EB6B899279 FOREIGN KEY (patient_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EB87F4FB17 FOREIGN KEY (doctor_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_1ADAD7EB6B899279 ON patient (patient_id)');
        $this->addSql('CREATE INDEX IDX_1ADAD7EB87F4FB17 ON patient (doctor_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EB6B899279');
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EB87F4FB17');
        $this->addSql('DROP INDEX IDX_1ADAD7EB6B899279 ON patient');
        $this->addSql('DROP INDEX IDX_1ADAD7EB87F4FB17 ON patient');
        $this->addSql('ALTER TABLE patient ADD username VARCHAR(255) NOT NULL, DROP patient_id, DROP doctor_id');
    }
}
