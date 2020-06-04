<?php
/**
 * Copyright 2015 - 2016, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2015 - 2016, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace CakeDC\OracleDriver\Test\TestCase\ORM\Locator;

///
/// @TODO fix plugins tests
///
///


use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use CakeDC\OracleDriver\ORM\Locator\MethodLocator;
use CakeDC\OracleDriver\ORM\Method;
use Cake\TestSuite\TestCase;

/**
 * Used to test correct class is instantiated when using $this->_locator->get();
 */
class MyUsersMethod extends Method
{

    /**
     * Overrides default method name
     *
     * @var string
     */
    protected $_method = 'users';
}

/**
 * Test case for MethodLocator
 */
class MethodLocatorTest extends TestCase
{

    /**
     * MethodLocator instance.
     *
     * @var \CakeDC\OracleDriver\ORM\Locator\MethodLocator
     */
    protected $_locator;

    /**
     * setup
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        Configure::write('App.namespace', 'TestApp');

        $this->_locator = new MethodLocator();
    }

    /**
     * Test config() method.
     *
     * @return void
     */
    public function testConfig()
    {
        $this->assertEquals([], $this->_locator->config('Tests'));

        $data = [
            'connection' => 'testing',
            'entityClass' => 'TestApp\Model\Entity\Article',
        ];
        $result = $this->_locator->config('Tests', $data);
        $this->assertEquals($data, $result, 'Returns config data.');

        $result = $this->_locator->config();
        $expected = ['Tests' => $data];
        $this->assertEquals($expected, $result);
    }

    /**
     * Test config() method with plugin syntax aliases
     *
     * @return void
     */
    public function testConfigPlugin()
    {
        Plugin::getCollection()->add(new \TestPlugin\Plugin());

        $data = [
            'connection' => 'testing',
            'entityClass' => 'TestPlugin\Model\Entity\Comment',
        ];

        $result = $this->_locator->config('TestPlugin.TestPluginComments', $data);
        $this->assertEquals($data, $result, 'Returns config data.');
    }

    /**
     * Test calling config() on existing instances throws an error.
     *
     * @expectedException \RuntimeException
     * @expectedExceptionMessage You cannot configure "Users", it has already been constructed.
     * @return void
     */
    public function testConfigOnDefinedInstance()
    {
        $users = $this->_locator->get('Users');
        $this->_locator->config('Users', ['method' => 'my_users']);
    }

    /**
     * Test the exists() method.
     *
     * @return void
     */
    public function testExists()
    {
        $this->assertFalse($this->_locator->exists('Articles'));

        $this->_locator->config('Articles', ['method' => 'articles']);
        $this->assertFalse($this->_locator->exists('Articles'));

        $this->_locator->get('Articles', ['method' => 'articles']);
        $this->assertTrue($this->_locator->exists('Articles'));
    }

    /**
     * Test the exists() method with plugin-prefixed models.
     *
     * @return void
     */
    public function testExistsPlugin()
    {
        $this->assertFalse($this->_locator->exists('Comments'));
        $this->assertFalse($this->_locator->exists('TestPlugin.Comments'));

        $this->_locator->config('TestPlugin.Comments', ['method' => 'comments']);
        $this->assertFalse($this->_locator->exists('Comments'), 'The Comments key should not be populated');
        $this->assertFalse($this->_locator->exists('TestPlugin.Comments'), 'The plugin.alias key should not be populated');

        $this->_locator->get('TestPlugin.Comments', ['method' => 'comments']);
        $this->assertFalse($this->_locator->exists('Comments'), 'The Comments key should not be populated');
        $this->assertTrue($this->_locator->exists('TestPlugin.Comments'), 'The plugin.alias key should now be populated');
    }

    /**
     * Test getting instances from the registry.
     *
     * @return void
     */
    public function testGet()
    {
        $result = $this->_locator->get('Articles', [
            'method' => 'my_articles',
        ]);
        $this->assertInstanceOf('CakeDC\OracleDriver\ORM\Method', $result);
        $this->assertEquals('my_articles', $result->method());

        $result2 = $this->_locator->get('Articles');
        $this->assertSame($result, $result2);
        $this->assertEquals('my_articles', $result->method());
    }

    /**
     * Are auto-models instanciated correctly? How about when they have an alias?
     *
     * @return void
     */
    public function testGetFallbacks()
    {
        $result = $this->_locator->get('Droids');
        $this->assertInstanceOf('CakeDC\OracleDriver\ORM\Method', $result);
        $this->assertEquals('droids', $result->method());
//        $this->assertEquals('Droids', $result->alias());

        $result = $this->_locator->get('R2D2', ['className' => 'Droids']);
        $this->assertInstanceOf('CakeDC\OracleDriver\ORM\Method', $result);
        $this->assertEquals('droids', $result->method(), 'The method should be derived from the className');
//        $this->assertEquals('R2D2', $result->alias());

        $result = $this->_locator->get('C3P0', ['className' => 'Droids', 'method' => 'rebels']);
        $this->assertInstanceOf('CakeDC\OracleDriver\ORM\Method', $result);
        $this->assertEquals('rebels', $result->method(), 'The method should be taken from options');
//        $this->assertEquals('C3P0', $result->alias());

        $result = $this->_locator->get('Funky.Chipmunks');
        $this->assertInstanceOf('CakeDC\OracleDriver\ORM\Method', $result);
        $this->assertEquals('chipmunks', $result->method(), 'The method should be derived from the alias');
//        $this->assertEquals('Chipmunks', $result->alias());

        $result = $this->_locator->get('Awesome', ['className' => 'Funky.Monkies']);
        $this->assertInstanceOf('CakeDC\OracleDriver\ORM\Method', $result);
        $this->assertEquals('monkies', $result->method(), 'The method should be derived from the classname');
//        $this->assertEquals('Awesome', $result->alias());

        $result = $this->_locator->get('Stuff', ['className' => 'CakeDC\OracleDriver\ORM\Method']);
        $this->assertInstanceOf('CakeDC\OracleDriver\ORM\Method', $result);
        $this->assertEquals('stuff', $result->method(), 'The method should be derived from the alias');
//        $this->assertEquals('Stuff', $result->alias());
    }

    /**
     * Test that get() uses config data set with config()
     *
     * @return void
     */
    public function testGetWithConfig()
    {
        $this->_locator->config('Articles', [
            'method' => 'my_articles',
        ]);
        $result = $this->_locator->get('Articles');
        $this->assertEquals('my_articles', $result->method(), 'Should use config() data.');
    }

    /**
     * Test that get() uses config data `className` set with config()
     *
     * @return void
     */
    public function testGetWithConfigClassName()
    {
        $this->_locator->config('MyUsersMethodAlias', [
            'className' => '\CakeDC\OracleDriver\Test\TestCase\ORM\Locator\MyUsersMethod',
        ]);
        $result = $this->_locator->get('MyUsersMethodAlias');
        $this->assertInstanceOf('\CakeDC\OracleDriver\Test\TestCase\ORM\Locator\MyUsersMethod', $result, 'Should use config() data className option.');
    }

    /**
     * Test get with config throws an exception if the alias exists already.
     *
     * @expectedException \RuntimeException
     * @expectedExceptionMessage You cannot configure "Users", it already exists in the registry.
     * @return void
     */
    public function testGetExistingWithConfigData()
    {
        $users = $this->_locator->get('Users');
        $this->_locator->get('Users', ['method' => 'my_users']);
    }

    /**
     * Test get() can be called several times with the same option without
     * throwing an exception.
     *
     * @return void
     */
    public function testGetWithSameOption()
    {
        $result = $this->_locator->get('Users', ['className' => 'CakeDC\OracleDriver\Test\TestCase\ORM\Locator\MyUsersMethod']);
        $result2 = $this->_locator->get('Users', ['className' => 'CakeDC\OracleDriver\Test\TestCase\ORM\Locator\MyUsersMethod']);
        $this->assertEquals($result, $result2);
    }

/**
 * Tests that methods can be instantiated based on conventions
 * and using plugin notation
 *
 * @return void
 */
//    public function testGetWithConventions()
//    {
//        $method = $this->_locator->get('articles');
//        $this->assertInstanceOf('TestApp\Model\Method\ArticlesMethod', $method);
//        $method = $this->_locator->get('Articles');
//        $this->assertInstanceOf('TestApp\Model\Method\ArticlesMethod', $method);
//
//        $method = $this->_locator->get('authors');
//        $this->assertInstanceOf('TestApp\Model\Method\AuthorsMethod', $method);
//        $method = $this->_locator->get('Authors');
//        $this->assertInstanceOf('TestApp\Model\Method\AuthorsMethod', $method);
//    }

/**
 * Test get() with plugin syntax aliases
 *
 * @return void
 */
//    public function testGetPlugin()
//    {
//        Plugin::load('TestPlugin');
//        $method = $this->_locator->get('TestPlugin.TestPluginComments');
//
//        $this->assertInstanceOf('TestPlugin\Model\Method\TestPluginCommentsMethod', $method);
//        $this->assertFalse(
//            $this->_locator->exists('TestPluginComments'),
//            'Short form should NOT exist'
//        );
//        $this->assertTrue(
//            $this->_locator->exists('TestPlugin.TestPluginComments'),
//            'Long form should exist'
//        );
//
//        $second = $this->_locator->get('TestPlugin.TestPluginComments');
//        $this->assertSame($method, $second, 'Can fetch long form');
//    }

/**
 * Test get() with same-alias models in different plugins
 *
 * There should be no internal cache-confusion
 *
 * @return void
 */
//    public function testGetMultiplePlugins()
//    {
//        Plugin::load('TestPlugin');
//        Plugin::load('TestPluginTwo');
//
//        $app = $this->_locator->get('Comments');
//        $plugin1 = $this->_locator->get('TestPlugin.Comments');
//        $plugin2 = $this->_locator->get('TestPluginTwo.Comments');
//
//        $this->assertInstanceOf('CakeDC\OracleDriver\ORM\Method', $app, 'Should be an app method instance');
//        $this->assertInstanceOf('TestPlugin\Model\Method\CommentsMethod', $plugin1, 'Should be a plugin 1 method instance');
//        $this->assertInstanceOf('TestPluginTwo\Model\Method\CommentsMethod', $plugin2, 'Should be a plugin 2 method instance');
//
//        $plugin2 = $this->_locator->get('TestPluginTwo.Comments');
//        $plugin1 = $this->_locator->get('TestPlugin.Comments');
//        $app = $this->_locator->get('Comments');
//
//        $this->assertInstanceOf('CakeDC\OracleDriver\ORM\Method', $app, 'Should still be an app method instance');
//        $this->assertInstanceOf('TestPlugin\Model\Method\CommentsMethod', $plugin1, 'Should still be a plugin 1 method instance');
//        $this->assertInstanceOf('TestPluginTwo\Model\Method\CommentsMethod', $plugin2, 'Should still be a plugin 2 method instance');
//    }

/**
 * Test get() with plugin aliases + className option.
 *
 * @return void
 */
//    public function testGetPluginWithClassNameOption()
//    {
//        Plugin::load('TestPlugin');
//        $method = $this->_locator->get('Comments', [
//            'className' => 'TestPlugin.TestPluginComments',
//        ]);
//        $class = 'TestPlugin\Model\Method\TestPluginCommentsMethod';
//        $this->assertInstanceOf($class, $method);
//        $this->assertFalse($this->_locator->exists('TestPluginComments'), 'Class name should not exist');
//        $this->assertFalse($this->_locator->exists('TestPlugin.TestPluginComments'), 'Full class alias should not exist');
//        $this->assertTrue($this->_locator->exists('Comments'), 'Class name should exist');
//
//        $second = $this->_locator->get('Comments');
//        $this->assertSame($method, $second);
//    }

/**
 * Test get() with full namespaced classname
 *
 * @return void
 */
//    public function testGetPluginWithFullNamespaceName()
//    {
//        Plugin::load('TestPlugin');
//        $class = 'TestPlugin\Model\Method\TestPluginCommentsMethod';
//        $method = $this->_locator->get('Comments', [
//            'className' => $class,
//        ]);
//        $this->assertInstanceOf($class, $method);
//        $this->assertFalse($this->_locator->exists('TestPluginComments'), 'Class name should not exist');
//        $this->assertFalse($this->_locator->exists('TestPlugin.TestPluginComments'), 'Full class alias should not exist');
//        $this->assertTrue($this->_locator->exists('Comments'), 'Class name should exist');
//    }

    /**
     * Tests that method options can be pre-configured for the factory method
     *
     * @return void
     */
    public function testConfigAndBuild()
    {
        $this->_locator->clear();
        $map = $this->_locator->config();
        $this->assertEquals([], $map);

        $connection = ConnectionManager::get('test', false);
        $options = ['connection' => $connection];
        $this->_locator->config('users', $options);
        $map = $this->_locator->config();
        $this->assertEquals(['users' => $options], $map);
        $this->assertEquals($options, $this->_locator->config('users'));

        $schema = ['id' => ['type' => 'rubbish']];
        $options += ['schema' => $schema];
        $this->_locator->config('users', $options);

        $method = $this->_locator->get('users', ['method' => 'users']);
        $this->assertInstanceOf('CakeDC\OracleDriver\ORM\Method', $method);
        $this->assertEquals('users', $method->method());
//        $this->assertSame($connection, $method->connection());

//        $this->assertEquals(array_keys($schema), $method->schema()->columns());
//        $this->assertEquals($schema['id']['type'], $method->schema()->column('id')['type']);

        $this->_locator->clear();
        $this->assertEmpty($this->_locator->config());

        $this->_locator->config('users', $options);
        $method = $this->_locator->get('users', ['className' => __NAMESPACE__ . '\MyUsersMethod']);
        $this->assertInstanceOf(__NAMESPACE__ . '\MyUsersMethod', $method);
        $this->assertEquals('users', $method->method());
//        $this->assertSame($connection, $method->connection());

//        $this->assertEquals(array_keys($schema), $method->schema()->columns());
//        $this->assertEquals($schema['id']['type'], $method->schema()->column('id')['type']);
    }

    /**
     * Test setting an instance.
     *
     * @return void
     */
    public function testSet()
    {
        $mock = $this->getMockBuilder(Method::class)->getMock();
        $this->assertSame($mock, $this->_locator->set('Articles', $mock));
        $this->assertSame($mock, $this->_locator->get('Articles'));
    }

/**
 * Test setting an instance with plugin syntax aliases
 *
 * @return void
 */
//    public function testSetPlugin()
//    {
//        Plugin::load('TestPlugin');
//
//        $mock = $this->getMock('TestPlugin\Model\Method\CommentsMethod');
//
//        $this->assertSame($mock, $this->_locator->set('TestPlugin.Comments', $mock));
//        $this->assertSame($mock, $this->_locator->get('TestPlugin.Comments'));
//    }

    /**
     * Tests genericInstances
     *
     * @return void
     */
    public function testGenericInstances()
    {
        $foos = $this->_locator->get('Foos');
        $bars = $this->_locator->get('Bars');
        $expected = ['Foos' => $foos, 'Bars' => $bars];
        $this->assertEquals($expected, $this->_locator->genericInstances());
    }

    /**
     * Tests remove an instance
     *
     * @return void
     */
    public function testRemove()
    {
        $first = $this->_locator->get('Comments');

        $this->assertTrue($this->_locator->exists('Comments'));

        $this->_locator->remove('Comments');
        $this->assertFalse($this->_locator->exists('Comments'));

        $second = $this->_locator->get('Comments');

        $this->assertNotSame($first, $second, 'Should be different objects, as the reference to the first was destroyed');
        $this->assertTrue($this->_locator->exists('Comments'));
    }

/**
 * testRemovePlugin
 *
 * Removing a plugin-prefixed model should not affect any other
 * plugin-prefixed model, or app model.
 * Removing an app model should not affect any other
 * plugin-prefixed model.
 *
 * @return void
 */
//    public function testRemovePlugin()
//    {
//        Plugin::load('TestPlugin');
//        Plugin::load('TestPluginTwo');
//
//        $app = $this->_locator->get('Comments');
//        $this->_locator->get('TestPlugin.Comments');
//        $plugin = $this->_locator->get('TestPluginTwo.Comments');
//
//        $this->assertTrue($this->_locator->exists('Comments'));
//        $this->assertTrue($this->_locator->exists('TestPlugin.Comments'));
//        $this->assertTrue($this->_locator->exists('TestPluginTwo.Comments'));
//
//        $this->_locator->remove('TestPlugin.Comments');
//
//        $this->assertTrue($this->_locator->exists('Comments'));
//        $this->assertFalse($this->_locator->exists('TestPlugin.Comments'));
//        $this->assertTrue($this->_locator->exists('TestPluginTwo.Comments'));
//
//        $app2 = $this->_locator->get('Comments');
//        $plugin2 = $this->_locator->get('TestPluginTwo.Comments');
//
//        $this->assertSame($app, $app2, 'Should be the same Comments object');
//        $this->assertSame($plugin, $plugin2, 'Should be the same TestPluginTwo.Comments object');
//
//        $this->_locator->remove('Comments');
//
//        $this->assertFalse($this->_locator->exists('Comments'));
//        $this->assertFalse($this->_locator->exists('TestPlugin.Comments'));
//        $this->assertTrue($this->_locator->exists('TestPluginTwo.Comments'));
//
//        $plugin3 = $this->_locator->get('TestPluginTwo.Comments');
//
//        $this->assertSame($plugin, $plugin3, 'Should be the same TestPluginTwo.Comments object');
//    }
}
