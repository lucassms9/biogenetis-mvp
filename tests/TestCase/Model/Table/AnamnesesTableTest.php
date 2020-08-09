<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AnamnesesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AnamnesesTable Test Case
 */
class AnamnesesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AnamnesesTable
     */
    public $Anamneses;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Anamneses',
        'app.Pacientes',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Anamneses') ? [] : ['className' => AnamnesesTable::class];
        $this->Anamneses = TableRegistry::getTableLocator()->get('Anamneses', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Anamneses);

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
