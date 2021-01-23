<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CroquisTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CroquisTable Test Case
 */
class CroquisTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\CroquisTable
     */
    public $Croquis;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Croquis',
        'app.Equipamentos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Croquis') ? [] : ['className' => CroquisTable::class];
        $this->Croquis = TableRegistry::getTableLocator()->get('Croquis', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Croquis);

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
