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

use Cake\Test\TestCase\ORM\MarshallerTest as CakeMarshallerTest;

/**
 * Tests Marshaller class
 *
 */
class MarshallerTest extends CakeMarshallerTest
{
    public $fixtures = [
        'core.Articles',
//        'core.ArticlesTags',
        'plugin.CakeDC/OracleDriver.ArticlesTags',
        'core.Comments',
        'core.SpecialTags',
        'core.Tags',
        'core.Users',
    ];
}
