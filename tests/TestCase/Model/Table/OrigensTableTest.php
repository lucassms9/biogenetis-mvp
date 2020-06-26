<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\OrigensTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\OrigensTable Test Case
 */
class OrigensTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\OrigensTable
     */
    public $Origens;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Origens',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Origens') ? [] : ['className' => OrigensTable::class];
        $this->Origens = TableRegistry::getTableLocator()->get('Origens', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Origens);

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
}
