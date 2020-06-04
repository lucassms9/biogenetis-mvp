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

namespace CakeDC\OracleDriver\Test\TestCase\ORM;

use CakeDC\OracleDriver\ORM\Request;
use Cake\TestSuite\TestCase;

/**
 * Request test case.
 */
class RequestTest extends TestCase
{

    /**
     * Tests setting a single property in an request without custom setters
     *
     * @return void
     */
    public function testSetOneParamNoSetters()
    {
        $request = new Request();
        $request->set('foo', 'bar');
        $this->assertEquals('bar', $request->foo);

        $request->set('foo', 'baz');
        $this->assertEquals('baz', $request->foo);

        $request->set('id', 1);
        $this->assertSame(1, $request->id);
    }

    /**
     * Tests setting multiple properties without custom setters
     *
     * @return void
     */
    public function testSetMultiplePropertiesNoSetters()
    {
        $request = new Request();

        $request->set(['foo' => 'bar', 'id' => 1]);
        $this->assertEquals('bar', $request->foo);
        $this->assertSame(1, $request->id);

        $request->set(['foo' => 'baz', 'id' => 2, 'thing' => 3]);
        $this->assertEquals('baz', $request->foo);
        $this->assertSame(2, $request->id);
        $this->assertSame(3, $request->thing);
    }

    /**
     * Tests setting a single property using a setter function
     *
     * @return void
     */
    public function testSetOneParamWithSetter()
    {
        $request = $this->getMockBuilder(Request::class)
            ->setMethods(['_setName'])
            ->getMock();
        $request->expects($this->once())->method('_setName')
            ->with('Jones')
            ->will($this->returnCallback(function ($name) {
                $this->assertEquals('Jones', $name);

                return 'Dr. ' . $name;
            }));
        $request->set('name', 'Jones');
        $this->assertEquals('Dr. Jones', $request->name);
    }

    /**
     * Tests setting multiple properties using a setter function
     *
     * @return void
     */
    public function testMultipleWithSetter()
    {
        $request = $this->getMockBuilder(Request::class)
            ->setMethods(['_setName', '_setStuff'])
            ->getMock();
        $request->expects($this->once())->method('_setName')
            ->with('Jones')
            ->will($this->returnCallback(function ($name) {
                $this->assertEquals('Jones', $name);

                return 'Dr. ' . $name;
            }));
        $request->expects($this->once())->method('_setStuff')
            ->with(['a', 'b'])
            ->will($this->returnCallback(function ($stuff) {
                $this->assertEquals(['a', 'b'], $stuff);

                return ['c', 'd'];
            }));
        $request->set(['name' => 'Jones', 'stuff' => ['a', 'b']]);
        $this->assertEquals('Dr. Jones', $request->name);
        $this->assertEquals(['c', 'd'], $request->stuff);
    }

    /**
     * Tests that it is possible to bypass the setters
     *
     * @return void
     */
    public function testBypassSetters()
    {
        $request = $this->getMockBuilder(Request::class)
            ->setMethods(['_setName', '_setStuff'])
            ->getMock();

        $request->expects($this->never())->method('_setName');
        $request->expects($this->never())->method('_setStuff');

        $request->set('name', 'Jones', ['setter' => false]);
        $this->assertEquals('Jones', $request->name);

        $request->set('stuff', 'Thing', ['setter' => false]);
        $this->assertEquals('Thing', $request->stuff);

        $request->set(['name' => 'foo', 'stuff' => 'bar'], ['setter' => false]);
        $this->assertEquals('bar', $request->stuff);
    }

    /**
     * Tests that the constructor will set initial properties
     *
     * @return void
     */
    public function testConstructor()
    {
        $request = $this->getMockBuilder('\CakeDC\OracleDriver\ORM\Request')
            ->setMethods(['set'])
            ->disableOriginalConstructor()
            ->getMock();
        $request->expects($this->at(0))
            ->method('set')
            ->with(['a' => 'b', 'c' => 'd'], ['setter' => true]);

        $request->expects($this->at(1))
            ->method('set')
            ->with(['foo' => 'bar'], ['setter' => false]);

        $request->__construct(['a' => 'b', 'c' => 'd']);
        $request->__construct(['foo' => 'bar'], ['useSetters' => false]);
    }

    /**
     * Tests getting properties with no custom getters
     *
     * @return void
     */
    public function testGetNoGetters()
    {
        $request = new Request(['id' => 1, 'foo' => 'bar']);
        $this->assertSame(1, $request->get('id'));
        $this->assertSame('bar', $request->get('foo'));
    }

    /**
     * Tests get with custom getter
     *
     * @return void
     */
    public function testGetCustomGetters()
    {
        $request = $this->getMockBuilder(Request::class)
            ->setMethods(['_getName'])
            ->getMock();
        $request->expects($this->any())
            ->method('_getName')
            ->with('Jones')
            ->will($this->returnCallback(function ($name) {
                return 'Dr. ' . $name;
            }));
        $request->set('name', 'Jones');
        $this->assertEquals('Dr. Jones', $request->get('name'));
        $this->assertEquals('Dr. Jones', $request->get('name'));
    }

    /**
     * Tests get with custom getter
     *
     * @return void
     */
    public function testGetCustomGettersAfterSet()
    {
        $request = $this->getMockBuilder(Request::class)
            ->setMethods(['_getName'])
            ->getMock();
        $request->expects($this->any())
            ->method('_getName')
            ->will($this->returnCallback(function ($name) {
                return 'Dr. ' . $name;
            }));
        $request->set('name', 'Jones');
        $this->assertEquals('Dr. Jones', $request->get('name'));
        $this->assertEquals('Dr. Jones', $request->get('name'));

        $request->set('name', 'Mark');
        $this->assertEquals('Dr. Mark', $request->get('name'));
        $this->assertEquals('Dr. Mark', $request->get('name'));
    }

    /**
     * Tests that the get cache is cleared by unsetProperty.
     *
     * @return void
     */
    public function testGetCacheClearedByUnset()
    {
        $request = $this->getMockBuilder(Request::class)
            ->setMethods(['_getName'])
            ->getMock();
        $request->expects($this->any())->method('_getName')
            ->will($this->returnCallback(function ($name) {
                return 'Dr. ' . $name;
            }));
        $request->set('name', 'Jones');
        $this->assertEquals('Dr. Jones', $request->get('name'));

        $request->unsetProperty('name');
        $this->assertEquals('Dr. ', $request->get('name'));
    }

    /**
     * Test magic property setting with no custom setter
     *
     * @return void
     */
    public function testMagicSet()
    {
        $request = new Request();
        $request->name = 'Jones';
        $this->assertEquals('Jones', $request->name);
        $request->name = 'George';
        $this->assertEquals('George', $request->name);
    }

    /**
     * Tests magic set with custom setter function
     *
     * @return void
     */
    public function testMagicSetWithSetter()
    {
        $request = $this->getMockBuilder(Request::class)
            ->setMethods(['_setName'])
            ->getMock();
        $request->expects($this->once())->method('_setName')
            ->with('Jones')
            ->will($this->returnCallback(function ($name) {
                $this->assertEquals('Jones', $name);

                return 'Dr. ' . $name;
            }));
        $request->name = 'Jones';
        $this->assertEquals('Dr. Jones', $request->name);
    }

    /**
     * Tests the magic getter with a custom getter function
     *
     * @return void
     */
    public function testMagicGetWithGetter()
    {
        $request = $this->getMockBuilder(Request::class)
            ->setMethods(['_getName'])
            ->getMock();
        $request->expects($this->once())->method('_getName')
            ->with('Jones')
            ->will($this->returnCallback(function ($name) {
                $this->assertSame('Jones', $name);

                return 'Dr. ' . $name;
            }));
        $request->set('name', 'Jones');
        $this->assertEquals('Dr. Jones', $request->name);
    }

    /**
     * Test indirectly modifying internal properties
     *
     * @return void
     */
    public function testIndirectModification()
    {
        $request = new Request();
        $request->things = ['a', 'b'];
        $request->things[] = 'c';
        $this->assertEquals(['a', 'b', 'c'], $request->things);
    }

    /**
     * Tests has() method
     *
     * @return void
     */
    public function testHas()
    {
        $request = new Request(['id' => 1, 'name' => 'Juan', 'foo' => null]);
        $this->assertTrue($request->has('id'));
        $this->assertTrue($request->has('name'));
        $this->assertFalse($request->has('foo'));
        $this->assertFalse($request->has('last_name'));

        $this->assertTrue($request->has(['id']));
        $this->assertTrue($request->has(['id', 'name']));
        $this->assertFalse($request->has(['id', 'foo']));
        $this->assertFalse($request->has(['id', 'nope']));

        $request = $this->getMockBuilder(Request::class)
            ->setMethods(['_getThings'])
            ->getMock();
        $request->expects($this->once())->method('_getThings')
            ->will($this->returnValue(0));
        $this->assertTrue($request->has('things'));
    }

    /**
     * Tests unsetProperty one property at a time
     *
     * @return void
     */
    public function testUnset()
    {
        $request = new Request(['id' => 1, 'name' => 'bar']);
        $request->unsetProperty('id');
        $this->assertFalse($request->has('id'));
        $this->assertTrue($request->has('name'));
        $request->unsetProperty('name');
        $this->assertFalse($request->has('id'));
    }

    /**
     * Tests unsetProperty whith multiple properties
     *
     * @return void
     */
    public function testUnsetMultiple()
    {
        $request = new Request(['id' => 1, 'name' => 'bar', 'thing' => 2]);
        $request->unsetProperty(['id', 'thing']);
        $this->assertFalse($request->has('id'));
        $this->assertTrue($request->has('name'));
        $this->assertFalse($request->has('thing'));
    }

    /**
     * Tests the magic __isset() method
     *
     * @return void
     */
    public function testMagicIsset()
    {
        $request = new Request(['id' => 1, 'name' => 'Juan', 'foo' => null]);
        $this->assertTrue(isset($request->id));
        $this->assertTrue(isset($request->name));
        $this->assertFalse(isset($request->foo));
        $this->assertFalse(isset($request->thing));
    }

    /**
     * Tests the magic __unset() method
     *
     * @return void
     */
    public function testMagicUnset()
    {
        $request = $this->getMockBuilder(Request::class)
            ->setMethods(['unsetProperty'])
            ->getMock();
        $request->expects($this->at(0))
            ->method('unsetProperty')
            ->with('foo');
        unset($request->foo);
    }

    /**
     * Tests isset with array access
     *
     * @return void
     */
    public function testIssetArrayAccess()
    {
        $request = new Request(['id' => 1, 'name' => 'Juan', 'foo' => null]);
        $this->assertTrue(isset($request['id']));
        $this->assertTrue(isset($request['name']));
        $this->assertFalse(isset($request['foo']));
        $this->assertFalse(isset($request['thing']));
    }

    /**
     * Tests get property with array access
     *
     * @return void
     */
    public function testGetArrayAccess()
    {
        $request = $this->getMockBuilder(Request::class)
            ->setMethods(['get'])
            ->getMock();
        $request->expects($this->at(0))
            ->method('get')
            ->with('foo')
            ->will($this->returnValue('worked'));

        $request->expects($this->at(1))
            ->method('get')
            ->with('bar')
            ->will($this->returnValue('worked too'));

        $this->assertEquals('worked', $request['foo']);
        $this->assertEquals('worked too', $request['bar']);
    }

    /**
     * Tests set with array access
     *
     * @return void
     */
    public function testSetArrayAccess()
    {
        $request = $this->getMockBuilder(Request::class)
            ->setMethods(['set'])
            ->getMock();

        $request->expects($this->at(0))
            ->method('set')
            ->with('foo', 1)
            ->will($this->returnSelf());

        $request->expects($this->at(1))
            ->method('set')
            ->with('bar', 2)
            ->will($this->returnSelf());

        $request['foo'] = 1;
        $request['bar'] = 2;
    }

    /**
     * Tests unset with array access
     *
     * @return void
     */
    public function testUnsetArrayAccess()
    {
        $request = $this->getMockBuilder(Request::class)
            ->setMethods(['unsetProperty'])
            ->getMock();
        $request->expects($this->at(0))
            ->method('unsetProperty')
            ->with('foo');
        unset($request['foo']);
    }

    /**
     * Tests that the method cache will only report the methods for the called class,
     * this is, calling methods defined in another request will not cause a fatal error
     * when trying to call directly an inexistent method in another class
     *
     * @return void
     */
    public function testMethodCache()
    {
        $request = $this->getMockBuilder(Request::class)
            ->setMethods(['_setFoo', '_getBar'])
            ->getMock();
        $request2 = $this->getMockBuilder(Request::class)
            ->setMethods(['_setBar'])
            ->getMock();
        $request->expects($this->once())->method('_setFoo');
        $request->expects($this->once())->method('_getBar');
        $request2->expects($this->once())->method('_setBar');

        $request->set('foo', 1);
        $request->get('bar');
        $request2->set('bar', 1);
    }

    /**
     * Tests that long properties in the request are inflected correctly
     *
     * @return void
     */
    public function testSetGetLongProperyNames()
    {
        $request = $this->getMockBuilder(Request::class)
            ->setMethods(['_getVeryLongProperty', '_setVeryLongProperty'])
            ->getMock();
        $request->expects($this->once())->method('_getVeryLongProperty');
        $request->expects($this->once())->method('_setVeryLongProperty');
        $request->get('very_long_property');
        $request->set('very_long_property', 1);
    }

    /**
     * Tests serializing an request as json
     *
     * @return void
     */
    public function testJsonSerialize()
    {
        $data = ['name' => 'James', 'age' => 20, 'phones' => ['123', '457']];
        $request = new Request($data);
        $this->assertEquals(json_encode($data), json_encode($request));
    }

    /**
     * Tests the isNew method
     *
     * @return void
     */
    public function testIsNew()
    {
        $data = [
            'id' => 1,
            'title' => 'Foo',
            'author_id' => 3,
        ];
        $request = new Request($data);
        $this->assertTrue($request->isNew());

        $request->isNew(true);
        $this->assertTrue($request->isNew());

        $request->isNew('derpy');
        $this->assertTrue($request->isNew());

        $request->isNew(false);
        $this->assertFalse($request->isNew());
    }

    /**
     * Tests the constructor when passing the markClean option
     *
     * @return void
     */
    public function testConstructorWithMarkNew()
    {
        $request = $this->getMockBuilder('\CakeDC\OracleDriver\ORM\Request')
            ->setMethods(['isNew', 'clean'])
            ->disableOriginalConstructor()
            ->getMock();
        $request->expects($this->never())->method('clean');
        $request->__construct(['a' => 'b', 'c' => 'd']);

        $request = $this->getMockBuilder('\CakeDC\OracleDriver\ORM\Request')
            ->setMethods(['isNew'])
            ->disableOriginalConstructor()
            ->getMock();
        $request->expects($this->once())->method('isNew');
        $request->__construct(['a' => 'b', 'c' => 'd'], ['markNew' => true]);
    }

    /**
     * Test toArray method.
     *
     * @return void
     */
    public function testToArray()
    {
        $data = ['name' => 'James', 'age' => 20, 'phones' => ['123', '457']];
        $request = new Request($data);

        $this->assertEquals($data, $request->toArray());
    }

    /**
     * Test that get accessors are called when converting to arrays.
     *
     * @return void
     */
    public function testToArrayWithAccessor()
    {
        $request = $this->getMockBuilder(Request::class)
            ->setMethods(['_getName'])
            ->getMock();
        $request->set(['name' => 'Mark', 'email' => 'mark@example.com']);
        $request->expects($this->any())
            ->method('_getName')
            ->will($this->returnValue('Jose'));

        $expected = ['name' => 'Jose', 'email' => 'mark@example.com'];
        $this->assertEquals($expected, $request->toArray());
    }

    /**
     * Tests the request's __toString method
     *
     * @return void
     */
    public function testToString()
    {
        $request = new Request(['foo' => 1, 'bar' => 2]);
        $this->assertEquals(json_encode($request, JSON_PRETTY_PRINT), (string)$request);
    }

    /**
     * Tests __debugInfo
     *
     * @return void
     */
    public function testDebugInfo()
    {
        $request = new Request(['foo' => 'bar'], ['markClean' => true]);
        $request->somethingElse = 'value';
        $result = $request->__debugInfo();
        $expected = [
            'foo' => 'bar',
            'somethingElse' => 'value',
            '[new]' => true,
//            '[repository]' => 'foos'
        ];
        $this->assertSame($expected, $result);
    }

/**
 * Tests the source method
 *
 * @return void
 */
//    public function testRepository()
//    {
//        $request = new Request;
//        $this->assertNull($request->repository());
//    }

    /**
     * Provides empty values
     *
     * @return void
     */
    public function emptyNamesProvider()
    {
        return [[''], [null], [false]];
    }

    /**
     * Tests that trying to get an empty propery name throws exception
     *
     * @dataProvider emptyNamesProvider
     * @expectedException \InvalidArgumentException
     * @return void
     */
    public function testEmptyProperties($property)
    {
        $request = new Request();
        $request->get($property);
    }

    /**
     * Tests that setitng an empty property name does nothing
     *
     * @expectedException \InvalidArgumentException
     * @dataProvider emptyNamesProvider
     * @return void
     */
    public function testSetEmptyPropertyName($property)
    {
        $request = new Request();
        $request->set($property, 'bar');
    }
}
