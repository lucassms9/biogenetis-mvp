<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ConfiguracoesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ConfiguracoesTable Test Case
 */
class ConfiguracoesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ConfiguracoesTable
     */
    public $Configuracoes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Configuracoes',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Configuracoes') ? [] : ['className' => ConfiguracoesTable::class];
        $this->Configuracoes = TableRegistry::getTableLocator()->get('Configuracoes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Configuracoes);

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
