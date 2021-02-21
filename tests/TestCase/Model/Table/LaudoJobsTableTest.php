<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LaudoJobsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LaudoJobsTable Test Case
 */
class LaudoJobsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\LaudoJobsTable
     */
    public $LaudoJobs;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.LaudoJobs',
        'app.Pedidos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('LaudoJobs') ? [] : ['className' => LaudoJobsTable::class];
        $this->LaudoJobs = TableRegistry::getTableLocator()->get('LaudoJobs', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->LaudoJobs);

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
