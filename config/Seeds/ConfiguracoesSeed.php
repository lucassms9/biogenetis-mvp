<?php
use Migrations\AbstractSeed;

/**
 * Configuracoes seed.
 */
class ConfiguracoesSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'cns_profissional_rnds' => '138352136850009',
            'cnes_rnds' => '9846972'
        ];

        $table = $this->table('configuracoes');
        $table->insert($data)->save();
    }
}
