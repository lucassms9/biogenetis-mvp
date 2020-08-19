<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PedidoCroquiTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PedidoCroquiTable Test Case
 */
class PedidoCroquiTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PedidoCroquiTable
     */
    public $PedidoCroqui;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.PedidoCroqui',
        'app.CroquiTipos',
        'app.Pedidos',
        'app.PedidoCroquiValores',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('PedidoCroqui') ? [] : ['className' => PedidoCroquiTable::class];
        $this->PedidoCroqui = TableRegistry::getTableLocator()->get('PedidoCroqui', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PedidoCroqui);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
