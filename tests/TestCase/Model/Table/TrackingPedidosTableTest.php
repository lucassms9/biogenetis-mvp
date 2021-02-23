<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TrackingPedidosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TrackingPedidosTable Test Case
 */
class TrackingPedidosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\TrackingPedidosTable
     */
    public $TrackingPedidos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.TrackingPedidos',
        'app.Users',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('TrackingPedidos') ? [] : ['className' => TrackingPedidosTable::class];
        $this->TrackingPedidos = TableRegistry::getTableLocator()->get('TrackingPedidos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->TrackingPedidos);

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
