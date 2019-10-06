<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20190907100138 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSQL('Update item set uid = link');
    }

    public function down(Schema $schema) : void
    {
        $this->addSQL('Update item set uid = null');
    }
}
