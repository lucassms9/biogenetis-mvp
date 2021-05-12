<?php
use Migrations\AbstractMigration;

class CreateAuthIntegrations extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('auth_integrations');

        $table->addColumn('user', 'string', [
            'null' => false,
        ]);

        $table->addColumn('password', 'string', [
            'null' => false,
        ]);

        $table->create();
    }
}
