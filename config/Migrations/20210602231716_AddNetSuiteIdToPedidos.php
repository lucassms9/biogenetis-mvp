<?php
use Migrations\AbstractMigration;

class AddNetSuiteIdToPedidos extends AbstractMigration
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
        $table = $this->table('pedidos');

        $table->addColumn('net_suite_id', 'integer', [
            'null' => true,
        ]);

        $table->update();
    }
}
