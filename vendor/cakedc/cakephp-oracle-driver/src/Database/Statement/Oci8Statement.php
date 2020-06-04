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

namespace CakeDC\OracleDriver\Database\Statement;

use CakeDC\OracleDriver\Database\OCI8\OCI8Statement as Statement;

class Oci8Statement extends Statement
{

    /**
     * {@inheritDoc}
     */
    public function closeCursor()
    {
        if (empty($this->_sth)) {
            return true;
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function __destruct()
    {
        if (is_resource($this->_sth)) {
            oci_free_statement($this->_sth);
        }
    }
}
