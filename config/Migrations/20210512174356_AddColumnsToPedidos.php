<?php

use Migrations\AbstractMigration;

class AddColumnsToPedidos extends AbstractMigration
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

        $table->addColumn('codigo_prioridade', 'string', [
            'null' => true,
        ]);
        $table->addColumn('data_hora_DUM', 'datetime', [
            'null' => true,
        ]);
        $table->addColumn('descricao_dados_clinicos', 'string', [
            'null' => true,
        ]);
        $table->addColumn('descricao_regiao_coleta', 'string', [
            'null' => true,
        ]);
        $table->addColumn('material_apoiado', 'string', [
            'null' => true,
        ]);
        $table->addColumn('codigo_conselho', 'string', [
            'null' => true,
        ]);
        $table->addColumn('codigo_conselho_solicitante', 'string', [
            'null' => true,
        ]);
        $table->addColumn('codigo_UF_conselho_solicitante', 'string', [
            'null' => true,
        ]);
        $table->addColumn('nome_colicitante', 'string', [
            'null' => true,
        ]);
        $table->addColumn('numero_atendimento_apoiado', 'string', [
            'null' => true,
        ]);
        $table->addColumn('posto_coleta', 'string', [
            'null' => true,
        ]);
        $table->addColumn('uso_apoiado', 'string', [
            'null' => true,
        ]);

        $table->update();
    }
}
