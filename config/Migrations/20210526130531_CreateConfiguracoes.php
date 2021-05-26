<?php
use Migrations\AbstractMigration;

class CreateConfiguracoes extends AbstractMigration
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
        $table = $this->table('configuracoes');
        $table->addColumn('cns_profissional_rnds', 'string', [
            'null' => true,
        ]);
        $table->addColumn('cnes_rnds', 'string', [
            'null' => true,
        ]);
        $table->create();
    }
}
