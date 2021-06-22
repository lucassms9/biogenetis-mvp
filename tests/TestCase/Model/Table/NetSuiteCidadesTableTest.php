<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\NetSuiteCidadesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\NetSuiteCidadesTable Test Case
 */
class NetSuiteCidadesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\NetSuiteCidadesTable
     */
    public $NetSuiteCidades;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.NetSuiteCidades',
        'app.Internals',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('NetSuiteCidades') ? [] : ['className' => NetSuiteCidadesTable::class];
        $this->NetSuiteCidades = TableRegistry::getTableLocator()->get('NetSuiteCidades', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->NetSuiteCidades);

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
