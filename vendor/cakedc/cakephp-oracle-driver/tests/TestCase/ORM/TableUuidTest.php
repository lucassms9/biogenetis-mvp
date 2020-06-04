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

use Cake\Test\TestCase\ORM\TableUuidTest as CakeTableUuidTest;

/**
 * Tests TableUuid class
 *
 */
class TableUuidTest extends CakeTableUuidTest
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'core.uuiditems',
    ];

    /**
     * Provider for testing that string and binary uuids work the same
     *
     * @return array
     */
    public function uuidTableProvider()
    {
        return [['uuiditems']];
    }
}
