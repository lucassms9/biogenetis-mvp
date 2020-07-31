<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EncadeamentoResultadosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EncadeamentoResultadosTable Test Case
 */
class EncadeamentoResultadosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\EncadeamentoResultadosTable
     */
    public $EncadeamentoResultados;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.EncadeamentoResultados',
        'app.ExameOrigems',
        'app.Escademantos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('EncadeamentoResultados') ? [] : ['className' => EncadeamentoResultadosTable::class];
        $this->EncadeamentoResultados = TableRegistry::getTableLocator()->get('EncadeamentoResultados', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EncadeamentoResultados);

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
