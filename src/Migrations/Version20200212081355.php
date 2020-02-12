<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200212081355 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE action (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, name VARCHAR(255) NOT NULL, content LONGTEXT DEFAULT NULL, region_start_at DATETIME DEFAULT NULL, region_end_at DATETIME DEFAULT NULL, taux1 INT NOT NULL, taux2 INT NOT NULL, experimental TINYINT(1) NOT NULL, show_all TINYINT(1) NOT NULL, cadrage LONGTEXT DEFAULT NULL, ref VARCHAR(10) NOT NULL, measure_value INT DEFAULT NULL, measure_content LONGTEXT DEFAULT NULL, state VARCHAR(30) NOT NULL, state_at DATETIME NOT NULL, content_state LONGTEXT DEFAULT NULL, show_at DATETIME DEFAULT NULL, INDEX IDX_47CC8C9212469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE action_cible (action_id INT NOT NULL, cible_id INT NOT NULL, INDEX IDX_69320879D32F035 (action_id), INDEX IDX_6932087A96E5E09 (cible_id), PRIMARY KEY(action_id, cible_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE action_vecteur (action_id INT NOT NULL, vecteur_id INT NOT NULL, INDEX IDX_8DF82599D32F035 (action_id), INDEX IDX_8DF825979578F41 (vecteur_id), PRIMARY KEY(action_id, vecteur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE actionreader_corbeille (action_id INT NOT NULL, corbeille_id INT NOT NULL, INDEX IDX_AA7AC70D9D32F035 (action_id), INDEX IDX_AA7AC70D57350F79 (corbeille_id), PRIMARY KEY(action_id, corbeille_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE actionwriter_corbeille (action_id INT NOT NULL, corbeille_id INT NOT NULL, INDEX IDX_881E32A29D32F035 (action_id), INDEX IDX_881E32A257350F79 (corbeille_id), PRIMARY KEY(action_id, corbeille_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE actionvalider_corbeille (action_id INT NOT NULL, corbeille_id INT NOT NULL, INDEX IDX_BF60B54D9D32F035 (action_id), INDEX IDX_BF60B54D57350F79 (corbeille_id), PRIMARY KEY(action_id, corbeille_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE action_file (id INT AUTO_INCREMENT NOT NULL, action_id INT NOT NULL, file_name VARCHAR(255) NOT NULL, update_at DATETIME DEFAULT NULL, file_extension VARCHAR(10) NOT NULL, nbr_view INT NOT NULL, title VARCHAR(50) NOT NULL, comment VARCHAR(255) DEFAULT NULL, INDEX IDX_FA7E8D1E9D32F035 (action_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE action_link (id INT AUTO_INCREMENT NOT NULL, action_id INT NOT NULL, title VARCHAR(255) NOT NULL, link VARCHAR(255) NOT NULL, comment VARCHAR(255) DEFAULT NULL, INDEX IDX_404D22FF9D32F035 (action_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE action_state (id INT AUTO_INCREMENT NOT NULL, action_id INT DEFAULT NULL, user_id INT DEFAULT NULL, state_old VARCHAR(20) NOT NULL, state_new VARCHAR(20) NOT NULL, change_at DATETIME NOT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_445D1E479D32F035 (action_id), INDEX IDX_445D1E47A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE avatar (id INT AUTO_INCREMENT NOT NULL, image LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE axe (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL, enable TINYINT(1) NOT NULL, content LONGTEXT DEFAULT NULL, taux1 INT NOT NULL, taux2 INT NOT NULL, archiving TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cadrage_file (id INT AUTO_INCREMENT NOT NULL, action_id INT NOT NULL, file_name VARCHAR(255) NOT NULL, update_at DATETIME DEFAULT NULL, file_extension VARCHAR(10) NOT NULL, nbr_view INT NOT NULL, title VARCHAR(50) NOT NULL, comment VARCHAR(255) DEFAULT NULL, INDEX IDX_AEEB83879D32F035 (action_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cadrage_link (id INT AUTO_INCREMENT NOT NULL, action_id INT NOT NULL, title VARCHAR(255) NOT NULL, link VARCHAR(255) NOT NULL, comment VARCHAR(255) DEFAULT NULL, INDEX IDX_14D82C669D32F035 (action_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, thematique_id INT NOT NULL, name VARCHAR(255) NOT NULL, enable TINYINT(1) NOT NULL, content LONGTEXT DEFAULT NULL, taux1 INT NOT NULL, taux2 INT NOT NULL, ref VARCHAR(5) NOT NULL, INDEX IDX_64C19C1476556AF (thematique_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cible (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL, enable TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE corbeille (id INT AUTO_INCREMENT NOT NULL, organisme_id INT NOT NULL, name VARCHAR(40) NOT NULL, enable TINYINT(1) NOT NULL, content LONGTEXT DEFAULT NULL, show_default TINYINT(1) NOT NULL, show_read TINYINT(1) NOT NULL, show_write TINYINT(1) NOT NULL, show_validate TINYINT(1) NOT NULL, INDEX IDX_1646B7075DDD38F5 (organisme_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE corbeille_user (corbeille_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_8ABC48C457350F79 (corbeille_id), INDEX IDX_8ABC48C4A76ED395 (user_id), PRIMARY KEY(corbeille_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE deployement (id INT AUTO_INCREMENT NOT NULL, organisme_id INT NOT NULL, action_id INT NOT NULL, taux1 INT NOT NULL, taux2 INT NOT NULL, show_at DATETIME DEFAULT NULL, end_at DATETIME DEFAULT NULL, INDEX IDX_E0E4A0625DDD38F5 (organisme_id), INDEX IDX_E0E4A0629D32F035 (action_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE deployementwriter_corbeille (deployement_id INT NOT NULL, corbeille_id INT NOT NULL, INDEX IDX_56EFF56124724016 (deployement_id), INDEX IDX_56EFF56157350F79 (corbeille_id), PRIMARY KEY(deployement_id, corbeille_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE deployementreader_corbeille (deployement_id INT NOT NULL, corbeille_id INT NOT NULL, INDEX IDX_748B00CE24724016 (deployement_id), INDEX IDX_748B00CE57350F79 (corbeille_id), PRIMARY KEY(deployement_id, corbeille_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE deployement_file (id INT AUTO_INCREMENT NOT NULL, deployement_id INT NOT NULL, file_name VARCHAR(255) NOT NULL, update_at DATETIME DEFAULT NULL, file_extension VARCHAR(10) NOT NULL, nbr_view INT NOT NULL, title VARCHAR(50) NOT NULL, comment VARCHAR(255) DEFAULT NULL, INDEX IDX_8FB6F0B324724016 (deployement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE deployement_link (id INT AUTO_INCREMENT NOT NULL, deployement_id INT NOT NULL, title VARCHAR(255) NOT NULL, link VARCHAR(255) NOT NULL, comment VARCHAR(255) DEFAULT NULL, INDEX IDX_35855F5224724016 (deployement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE indicator (id INT AUTO_INCREMENT NOT NULL, action_id INT NOT NULL, name VARCHAR(255) NOT NULL, enable TINYINT(1) NOT NULL, content LONGTEXT DEFAULT NULL, indicator_type VARCHAR(20) DEFAULT NULL, goal VARCHAR(20) DEFAULT NULL, value VARCHAR(20) DEFAULT NULL, taux1 INT NOT NULL, taux2 INT NOT NULL, INDEX IDX_D1349DB39D32F035 (action_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE indicator_value (id INT AUTO_INCREMENT NOT NULL, indicator_id INT NOT NULL, deployement_id INT NOT NULL, content LONGTEXT DEFAULT NULL, taux1 INT NOT NULL, taux2 INT NOT NULL, goal VARCHAR(20) DEFAULT NULL, value VARCHAR(20) DEFAULT NULL, enable TINYINT(1) NOT NULL, INDEX IDX_D18506234402854A (indicator_id), INDEX IDX_D185062324724016 (deployement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE indicator_value_history (id INT AUTO_INCREMENT NOT NULL, indicator_value_id INT NOT NULL, user_id INT DEFAULT NULL, added_at DATETIME NOT NULL, content LONGTEXT DEFAULT NULL, goal VARCHAR(10) DEFAULT NULL, value VARCHAR(10) DEFAULT NULL, taux1 INT NOT NULL, taux2 INT NOT NULL, INDEX IDX_BD33123B97C2ED71 (indicator_value_id), INDEX IDX_BD33123BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, content LONGTEXT DEFAULT NULL, modify_at DATETIME DEFAULT NULL, name VARCHAR(30) NOT NULL, enable TINYINT(1) NOT NULL, INDEX IDX_B6BD307FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organisme (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL, ref VARCHAR(5) NOT NULL, alterable TINYINT(1) NOT NULL, content LONGTEXT DEFAULT NULL, enable TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organisme_user (organisme_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_A53730645DDD38F5 (organisme_id), INDEX IDX_A5373064A76ED395 (user_id), PRIMARY KEY(organisme_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pole (id INT AUTO_INCREMENT NOT NULL, axe_id INT NOT NULL, name VARCHAR(100) NOT NULL, enable TINYINT(1) NOT NULL, content LONGTEXT DEFAULT NULL, taux1 INT NOT NULL, taux2 INT NOT NULL, INDEX IDX_FD6042E12E30CD41 (axe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE thematique (id INT AUTO_INCREMENT NOT NULL, pole_id INT NOT NULL, name VARCHAR(255) NOT NULL, enable TINYINT(1) NOT NULL, content LONGTEXT DEFAULT NULL, taux1 INT NOT NULL, taux2 INT NOT NULL, ref VARCHAR(5) NOT NULL, INDEX IDX_3A8ED5A8419C3385 (pole_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, avatar_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(100) NOT NULL, email_validated TINYINT(1) NOT NULL, email_validated_token VARCHAR(255) DEFAULT NULL, forget_token VARCHAR(50) DEFAULT NULL, login_at DATETIME DEFAULT NULL, created_at DATETIME DEFAULT NULL, modified_at DATETIME DEFAULT NULL, enable TINYINT(1) NOT NULL, content LONGTEXT DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D64986383B10 (avatar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vecteur (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(60) NOT NULL, enable TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE action ADD CONSTRAINT FK_47CC8C9212469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE action_cible ADD CONSTRAINT FK_69320879D32F035 FOREIGN KEY (action_id) REFERENCES action (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE action_cible ADD CONSTRAINT FK_6932087A96E5E09 FOREIGN KEY (cible_id) REFERENCES cible (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE action_vecteur ADD CONSTRAINT FK_8DF82599D32F035 FOREIGN KEY (action_id) REFERENCES action (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE action_vecteur ADD CONSTRAINT FK_8DF825979578F41 FOREIGN KEY (vecteur_id) REFERENCES vecteur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE actionreader_corbeille ADD CONSTRAINT FK_AA7AC70D9D32F035 FOREIGN KEY (action_id) REFERENCES action (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE actionreader_corbeille ADD CONSTRAINT FK_AA7AC70D57350F79 FOREIGN KEY (corbeille_id) REFERENCES corbeille (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE actionwriter_corbeille ADD CONSTRAINT FK_881E32A29D32F035 FOREIGN KEY (action_id) REFERENCES action (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE actionwriter_corbeille ADD CONSTRAINT FK_881E32A257350F79 FOREIGN KEY (corbeille_id) REFERENCES corbeille (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE actionvalider_corbeille ADD CONSTRAINT FK_BF60B54D9D32F035 FOREIGN KEY (action_id) REFERENCES action (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE actionvalider_corbeille ADD CONSTRAINT FK_BF60B54D57350F79 FOREIGN KEY (corbeille_id) REFERENCES corbeille (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE action_file ADD CONSTRAINT FK_FA7E8D1E9D32F035 FOREIGN KEY (action_id) REFERENCES action (id)');
        $this->addSql('ALTER TABLE action_link ADD CONSTRAINT FK_404D22FF9D32F035 FOREIGN KEY (action_id) REFERENCES action (id)');
        $this->addSql('ALTER TABLE action_state ADD CONSTRAINT FK_445D1E479D32F035 FOREIGN KEY (action_id) REFERENCES action (id)');
        $this->addSql('ALTER TABLE action_state ADD CONSTRAINT FK_445D1E47A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE cadrage_file ADD CONSTRAINT FK_AEEB83879D32F035 FOREIGN KEY (action_id) REFERENCES action (id)');
        $this->addSql('ALTER TABLE cadrage_link ADD CONSTRAINT FK_14D82C669D32F035 FOREIGN KEY (action_id) REFERENCES action (id)');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1476556AF FOREIGN KEY (thematique_id) REFERENCES thematique (id)');
        $this->addSql('ALTER TABLE corbeille ADD CONSTRAINT FK_1646B7075DDD38F5 FOREIGN KEY (organisme_id) REFERENCES organisme (id)');
        $this->addSql('ALTER TABLE corbeille_user ADD CONSTRAINT FK_8ABC48C457350F79 FOREIGN KEY (corbeille_id) REFERENCES corbeille (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE corbeille_user ADD CONSTRAINT FK_8ABC48C4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE deployement ADD CONSTRAINT FK_E0E4A0625DDD38F5 FOREIGN KEY (organisme_id) REFERENCES organisme (id)');
        $this->addSql('ALTER TABLE deployement ADD CONSTRAINT FK_E0E4A0629D32F035 FOREIGN KEY (action_id) REFERENCES action (id)');
        $this->addSql('ALTER TABLE deployementwriter_corbeille ADD CONSTRAINT FK_56EFF56124724016 FOREIGN KEY (deployement_id) REFERENCES deployement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE deployementwriter_corbeille ADD CONSTRAINT FK_56EFF56157350F79 FOREIGN KEY (corbeille_id) REFERENCES corbeille (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE deployementreader_corbeille ADD CONSTRAINT FK_748B00CE24724016 FOREIGN KEY (deployement_id) REFERENCES deployement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE deployementreader_corbeille ADD CONSTRAINT FK_748B00CE57350F79 FOREIGN KEY (corbeille_id) REFERENCES corbeille (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE deployement_file ADD CONSTRAINT FK_8FB6F0B324724016 FOREIGN KEY (deployement_id) REFERENCES deployement (id)');
        $this->addSql('ALTER TABLE deployement_link ADD CONSTRAINT FK_35855F5224724016 FOREIGN KEY (deployement_id) REFERENCES deployement (id)');
        $this->addSql('ALTER TABLE indicator ADD CONSTRAINT FK_D1349DB39D32F035 FOREIGN KEY (action_id) REFERENCES action (id)');
        $this->addSql('ALTER TABLE indicator_value ADD CONSTRAINT FK_D18506234402854A FOREIGN KEY (indicator_id) REFERENCES indicator (id)');
        $this->addSql('ALTER TABLE indicator_value ADD CONSTRAINT FK_D185062324724016 FOREIGN KEY (deployement_id) REFERENCES deployement (id)');
        $this->addSql('ALTER TABLE indicator_value_history ADD CONSTRAINT FK_BD33123B97C2ED71 FOREIGN KEY (indicator_value_id) REFERENCES indicator_value (id)');
        $this->addSql('ALTER TABLE indicator_value_history ADD CONSTRAINT FK_BD33123BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE organisme_user ADD CONSTRAINT FK_A53730645DDD38F5 FOREIGN KEY (organisme_id) REFERENCES organisme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE organisme_user ADD CONSTRAINT FK_A5373064A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pole ADD CONSTRAINT FK_FD6042E12E30CD41 FOREIGN KEY (axe_id) REFERENCES axe (id)');
        $this->addSql('ALTER TABLE thematique ADD CONSTRAINT FK_3A8ED5A8419C3385 FOREIGN KEY (pole_id) REFERENCES pole (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64986383B10 FOREIGN KEY (avatar_id) REFERENCES avatar (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE action_cible DROP FOREIGN KEY FK_69320879D32F035');
        $this->addSql('ALTER TABLE action_vecteur DROP FOREIGN KEY FK_8DF82599D32F035');
        $this->addSql('ALTER TABLE actionreader_corbeille DROP FOREIGN KEY FK_AA7AC70D9D32F035');
        $this->addSql('ALTER TABLE actionwriter_corbeille DROP FOREIGN KEY FK_881E32A29D32F035');
        $this->addSql('ALTER TABLE actionvalider_corbeille DROP FOREIGN KEY FK_BF60B54D9D32F035');
        $this->addSql('ALTER TABLE action_file DROP FOREIGN KEY FK_FA7E8D1E9D32F035');
        $this->addSql('ALTER TABLE action_link DROP FOREIGN KEY FK_404D22FF9D32F035');
        $this->addSql('ALTER TABLE action_state DROP FOREIGN KEY FK_445D1E479D32F035');
        $this->addSql('ALTER TABLE cadrage_file DROP FOREIGN KEY FK_AEEB83879D32F035');
        $this->addSql('ALTER TABLE cadrage_link DROP FOREIGN KEY FK_14D82C669D32F035');
        $this->addSql('ALTER TABLE deployement DROP FOREIGN KEY FK_E0E4A0629D32F035');
        $this->addSql('ALTER TABLE indicator DROP FOREIGN KEY FK_D1349DB39D32F035');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64986383B10');
        $this->addSql('ALTER TABLE pole DROP FOREIGN KEY FK_FD6042E12E30CD41');
        $this->addSql('ALTER TABLE action DROP FOREIGN KEY FK_47CC8C9212469DE2');
        $this->addSql('ALTER TABLE action_cible DROP FOREIGN KEY FK_6932087A96E5E09');
        $this->addSql('ALTER TABLE actionreader_corbeille DROP FOREIGN KEY FK_AA7AC70D57350F79');
        $this->addSql('ALTER TABLE actionwriter_corbeille DROP FOREIGN KEY FK_881E32A257350F79');
        $this->addSql('ALTER TABLE actionvalider_corbeille DROP FOREIGN KEY FK_BF60B54D57350F79');
        $this->addSql('ALTER TABLE corbeille_user DROP FOREIGN KEY FK_8ABC48C457350F79');
        $this->addSql('ALTER TABLE deployementwriter_corbeille DROP FOREIGN KEY FK_56EFF56157350F79');
        $this->addSql('ALTER TABLE deployementreader_corbeille DROP FOREIGN KEY FK_748B00CE57350F79');
        $this->addSql('ALTER TABLE deployementwriter_corbeille DROP FOREIGN KEY FK_56EFF56124724016');
        $this->addSql('ALTER TABLE deployementreader_corbeille DROP FOREIGN KEY FK_748B00CE24724016');
        $this->addSql('ALTER TABLE deployement_file DROP FOREIGN KEY FK_8FB6F0B324724016');
        $this->addSql('ALTER TABLE deployement_link DROP FOREIGN KEY FK_35855F5224724016');
        $this->addSql('ALTER TABLE indicator_value DROP FOREIGN KEY FK_D185062324724016');
        $this->addSql('ALTER TABLE indicator_value DROP FOREIGN KEY FK_D18506234402854A');
        $this->addSql('ALTER TABLE indicator_value_history DROP FOREIGN KEY FK_BD33123B97C2ED71');
        $this->addSql('ALTER TABLE corbeille DROP FOREIGN KEY FK_1646B7075DDD38F5');
        $this->addSql('ALTER TABLE deployement DROP FOREIGN KEY FK_E0E4A0625DDD38F5');
        $this->addSql('ALTER TABLE organisme_user DROP FOREIGN KEY FK_A53730645DDD38F5');
        $this->addSql('ALTER TABLE thematique DROP FOREIGN KEY FK_3A8ED5A8419C3385');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1476556AF');
        $this->addSql('ALTER TABLE action_state DROP FOREIGN KEY FK_445D1E47A76ED395');
        $this->addSql('ALTER TABLE corbeille_user DROP FOREIGN KEY FK_8ABC48C4A76ED395');
        $this->addSql('ALTER TABLE indicator_value_history DROP FOREIGN KEY FK_BD33123BA76ED395');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FA76ED395');
        $this->addSql('ALTER TABLE organisme_user DROP FOREIGN KEY FK_A5373064A76ED395');
        $this->addSql('ALTER TABLE action_vecteur DROP FOREIGN KEY FK_8DF825979578F41');
        $this->addSql('DROP TABLE action');
        $this->addSql('DROP TABLE action_cible');
        $this->addSql('DROP TABLE action_vecteur');
        $this->addSql('DROP TABLE actionreader_corbeille');
        $this->addSql('DROP TABLE actionwriter_corbeille');
        $this->addSql('DROP TABLE actionvalider_corbeille');
        $this->addSql('DROP TABLE action_file');
        $this->addSql('DROP TABLE action_link');
        $this->addSql('DROP TABLE action_state');
        $this->addSql('DROP TABLE avatar');
        $this->addSql('DROP TABLE axe');
        $this->addSql('DROP TABLE cadrage_file');
        $this->addSql('DROP TABLE cadrage_link');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE cible');
        $this->addSql('DROP TABLE corbeille');
        $this->addSql('DROP TABLE corbeille_user');
        $this->addSql('DROP TABLE deployement');
        $this->addSql('DROP TABLE deployementwriter_corbeille');
        $this->addSql('DROP TABLE deployementreader_corbeille');
        $this->addSql('DROP TABLE deployement_file');
        $this->addSql('DROP TABLE deployement_link');
        $this->addSql('DROP TABLE indicator');
        $this->addSql('DROP TABLE indicator_value');
        $this->addSql('DROP TABLE indicator_value_history');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE organisme');
        $this->addSql('DROP TABLE organisme_user');
        $this->addSql('DROP TABLE pole');
        $this->addSql('DROP TABLE thematique');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE vecteur');
    }
}
