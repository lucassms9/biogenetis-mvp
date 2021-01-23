<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TecnicoPeritosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TecnicoPeritosTable Test Case
 */
class TecnicoPeritosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\TecnicoPeritosTable
     */
    public $TecnicoPeritos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.TecnicoPeritos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('TecnicoPeritos') ? [] : ['className' => TecnicoPeritosTable::class];
        $this->TecnicoPeritos = TableRegistry::getTableLocator()->get('TecnicoPeritos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->TecnicoPeritos);

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
