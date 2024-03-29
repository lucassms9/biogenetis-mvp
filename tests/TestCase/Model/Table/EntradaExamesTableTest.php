<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EntradaExamesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EntradaExamesTable Test Case
 */
class EntradaExamesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\EntradaExamesTable
     */
    public $EntradaExames;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.EntradaExames',
        'app.Exames',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('EntradaExames') ? [] : ['className' => EntradaExamesTable::class];
        $this->EntradaExames = TableRegistry::getTableLocator()->get('EntradaExames', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EntradaExames);

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
