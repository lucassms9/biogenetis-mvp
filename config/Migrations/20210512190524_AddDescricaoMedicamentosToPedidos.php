<?php
use Migrations\AbstractMigration;

class AddDescricaoMedicamentosToPedidos extends AbstractMigration
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

        $table->addColumn('descricao_medicamentos', 'string', [
            'null' => true,
        ]);

        $table->update();
    }
}
