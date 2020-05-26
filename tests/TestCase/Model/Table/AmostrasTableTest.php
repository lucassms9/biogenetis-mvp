<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AmostrasTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AmostrasTable Test Case
 */
class AmostrasTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AmostrasTable
     */
    public $Amostras;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Amostras',
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
        $config = TableRegistry::getTableLocator()->exists('Amostras') ? [] : ['className' => AmostrasTable::class];
        $this->Amostras = TableRegistry::getTableLocator()->get('Amostras', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Amostras);

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
