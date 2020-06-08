<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AmostraErrosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AmostraErrosTable Test Case
 */
class AmostraErrosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AmostraErrosTable
     */
    public $AmostraErros;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.AmostraErros',
        'app.Amostras',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('AmostraErros') ? [] : ['className' => AmostraErrosTable::class];
        $this->AmostraErros = TableRegistry::getTableLocator()->get('AmostraErros', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AmostraErros);

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
