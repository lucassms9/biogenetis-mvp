<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AuthIntegrationsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AuthIntegrationsTable Test Case
 */
class AuthIntegrationsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AuthIntegrationsTable
     */
    public $AuthIntegrations;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.AuthIntegrations',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('AuthIntegrations') ? [] : ['className' => AuthIntegrationsTable::class];
        $this->AuthIntegrations = TableRegistry::getTableLocator()->get('AuthIntegrations', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AuthIntegrations);

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
