<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ExtratoSaldoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ExtratoSaldoTable Test Case
 */
class ExtratoSaldoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ExtratoSaldoTable
     */
    public $ExtratoSaldo;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ExtratoSaldo',
        'app.Vouchers',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ExtratoSaldo') ? [] : ['className' => ExtratoSaldoTable::class];
        $this->ExtratoSaldo = TableRegistry::getTableLocator()->get('ExtratoSaldo', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ExtratoSaldo);

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
