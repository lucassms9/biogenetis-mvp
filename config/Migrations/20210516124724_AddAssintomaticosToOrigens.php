<?php
use Migrations\AbstractMigration;

class AddAssintomaticosToOrigens extends AbstractMigration
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
        $table = $this->table('origens');
        $table->addColumn('assintomatico', 'boolean', [
            'default' => 0,
            'null' => false,
        ]);
        $table->addColumn('sintomatico', 'boolean', [
            'default' => 0,
            'null' => false,
        ]);
        $table->update();
    }
}
