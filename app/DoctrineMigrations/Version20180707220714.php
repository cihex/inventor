<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180707220714 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE hire (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, street VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, postal_code VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, hireDate DATE DEFAULT NULL, plannedReturnDate DATE DEFAULT NULL, returnDate DATE DEFAULT NULL, comment TINYTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE donor (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, street VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, postalCode VARCHAR(6) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, alias VARCHAR(20) NOT NULL, datetime DATETIME NOT NULL, UNIQUE INDEX UNIQ_64C19C15E237E06 (name), UNIQUE INDEX UNIQ_64C19C1E16C6B94 (alias), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exhibit_owner (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, street VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, postalCode VARCHAR(6) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exhibit_state (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_C474F94C5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photo (id INT AUTO_INCREMENT NOT NULL, exhibit_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, is_active TINYINT(1) DEFAULT \'1\' NOT NULL, INDEX IDX_14B784182E5CE433 (exhibit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exhibit (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, donor_id INT DEFAULT NULL, state_id INT DEFAULT NULL, owner_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, adoption_date DATE DEFAULT NULL, producer VARCHAR(255) DEFAULT NULL, produce_date INT DEFAULT 1990, is_show_donor TINYINT(1) DEFAULT \'0\' NOT NULL, is_show_owner TINYINT(1) DEFAULT \'0\' NOT NULL, INDEX IDX_E4FBCD1012469DE2 (category_id), INDEX IDX_E4FBCD103DD7B7A7 (donor_id), INDEX IDX_E4FBCD105D83CC1 (state_id), INDEX IDX_E4FBCD107E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exhibits_hires (exhibit_id INT NOT NULL, hire_id INT NOT NULL, INDEX IDX_78FA0E322E5CE433 (exhibit_id), INDEX IDX_78FA0E321C242BD2 (hire_id), PRIMARY KEY(exhibit_id, hire_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(25) NOT NULL, password VARCHAR(64) NOT NULL, email VARCHAR(60) NOT NULL, is_active TINYINT(1) DEFAULT \'1\' NOT NULL, is_admin TINYINT(1) DEFAULT \'0\' NOT NULL, is_new TINYINT(1) DEFAULT \'1\' NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B784182E5CE433 FOREIGN KEY (exhibit_id) REFERENCES exhibit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE exhibit ADD CONSTRAINT FK_E4FBCD1012469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE exhibit ADD CONSTRAINT FK_E4FBCD103DD7B7A7 FOREIGN KEY (donor_id) REFERENCES donor (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE exhibit ADD CONSTRAINT FK_E4FBCD105D83CC1 FOREIGN KEY (state_id) REFERENCES exhibit_state (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE exhibit ADD CONSTRAINT FK_E4FBCD107E3C61F9 FOREIGN KEY (owner_id) REFERENCES exhibit_owner (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE exhibits_hires ADD CONSTRAINT FK_78FA0E322E5CE433 FOREIGN KEY (exhibit_id) REFERENCES exhibit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE exhibits_hires ADD CONSTRAINT FK_78FA0E321C242BD2 FOREIGN KEY (hire_id) REFERENCES hire (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE exhibits_hires DROP FOREIGN KEY FK_78FA0E321C242BD2');
        $this->addSql('ALTER TABLE exhibit DROP FOREIGN KEY FK_E4FBCD103DD7B7A7');
        $this->addSql('ALTER TABLE exhibit DROP FOREIGN KEY FK_E4FBCD1012469DE2');
        $this->addSql('ALTER TABLE exhibit DROP FOREIGN KEY FK_E4FBCD107E3C61F9');
        $this->addSql('ALTER TABLE exhibit DROP FOREIGN KEY FK_E4FBCD105D83CC1');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B784182E5CE433');
        $this->addSql('ALTER TABLE exhibits_hires DROP FOREIGN KEY FK_78FA0E322E5CE433');
        $this->addSql('DROP TABLE hire');
        $this->addSql('DROP TABLE donor');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE exhibit_owner');
        $this->addSql('DROP TABLE exhibit_state');
        $this->addSql('DROP TABLE photo');
        $this->addSql('DROP TABLE exhibit');
        $this->addSql('DROP TABLE exhibits_hires');
        $this->addSql('DROP TABLE user');
    }
}
