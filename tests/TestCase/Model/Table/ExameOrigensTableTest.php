<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ExameOrigensTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ExameOrigensTable Test Case
 */
class ExameOrigensTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ExameOrigensTable
     */
    public $ExameOrigens;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ExameOrigens',
        'app.Exames',
        'app.Origems',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ExameOrigens') ? [] : ['className' => ExameOrigensTable::class];
        $this->ExameOrigens = TableRegistry::getTableLocator()->get('ExameOrigens', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ExameOrigens);

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
