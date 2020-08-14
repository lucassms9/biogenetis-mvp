<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SaldoContaTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SaldoContaTable Test Case
 */
class SaldoContaTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SaldoContaTable
     */
    public $SaldoConta;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.SaldoConta',
        'app.Clientes',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('SaldoConta') ? [] : ['className' => SaldoContaTable::class];
        $this->SaldoConta = TableRegistry::getTableLocator()->get('SaldoConta', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SaldoConta);

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
