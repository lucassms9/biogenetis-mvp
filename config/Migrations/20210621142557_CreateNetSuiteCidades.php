<?php

use Migrations\AbstractMigration;

class CreateNetSuiteCidades extends AbstractMigration
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
        $table = $this->table('net_suite_cidades');

        $table->addColumn('internal_id', 'integer', [
            'null' => false,
        ]);
        $table->addColumn('nome', 'string', [
            'null' => true,
        ]);
        $table->addColumn('code_municipio', 'string', [
            'null' => true,
        ]);
        $table->addColumn('municipio_nome', 'string', [
            'null' => false,
        ]);
        $table->addColumn('uf', 'string', [
            'null' => false,
        ]);
        $table->create();
    }
}
