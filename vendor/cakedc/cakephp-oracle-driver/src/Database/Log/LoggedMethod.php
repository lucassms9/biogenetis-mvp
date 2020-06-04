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

namespace CakeDC\OracleDriver\Database\Log;

/**
 * Contains a method string, the params used to executed it, time taken to do it
 * and the number of rows found or affected by its execution.
 */
class LoggedMethod
{
    /**
     * Method query string that was executed
     *
     * @var string
     */
    public $method = '';

    /**
     * Number of milliseconds this method took to complete
     *
     * @var float
     */
    public $took = 0;

    /**
     * Associative array with the params bound to the method string
     *
     * @var string
     */
    public $params = [];

    /**
     * Number of rows affected or returned by the method execution
     *
     * @var int
     */
    public $numRows = 0;

    /**
     * The exception that was thrown by the execution of this method
     *
     * @var \Exception
     */
    public $error;

    /**
     * Returns the string representation of this logged method
     *
     * @return string
     */
    public function __toString()
    {
        return $this->method;
    }
}
