<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EncadeamentosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EncadeamentosTable Test Case
 */
class EncadeamentosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\EncadeamentosTable
     */
    public $Encadeamentos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Encadeamentos',
        'app.OrigemParents',
        'app.OrigemEncadeamentos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Encadeamentos') ? [] : ['className' => EncadeamentosTable::class];
        $this->Encadeamentos = TableRegistry::getTableLocator()->get('Encadeamentos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Encadeamentos);

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
