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

namespace CakeDC\OracleDriver\Database\Statement\Method;

/**
 * Statement class meant to be used by an Oracle driver
 *
 */
class OracleBufferedStatement extends MethodStatementDecorator
{

    /**
     * Records count
     *
     * @var int
     */
    protected $_count = 0;

    /**
     * Array of results
     *
     * @var array
     */
    protected $_records = [];

    /**
     * If true, all rows were fetched
     *
     * @var bool
     */
    protected $_allFetched = true;

    /**
     * Current record pointer
     * @var int
     */
    protected $_counter = 0;

    /**
     * Constructor
     *
     * @param \Cake\Database\StatementInterface|null $statement Statement implementation such as PDOStatement
     * @param \Cake\Database\Driver|null $driver Driver instance
     */
    public function __construct($statement = null, $driver = null)
    {
        parent::__construct($statement, $driver);
        $this->_reset();
    }

    /**
     * Execute the statement and return the results.
     *
     * @param array|null $params list of values to be bound to query
     * @return bool true on success, false otherwise
     */
    public function execute($params = null)
    {
        $this->_reset();

        return parent::execute($params);
    }

    /**
     * {@inheritDoc}
     *
     * @param string $type The type to fetch.
     * @return mixed
     */
    public function fetch($type = 'num')
    {
        if ($this->_allFetched) {
            $row = ($this->_counter < $this->_count) ? $this->_records[$this->_counter++] : false;
            $row = ($row && $type === 'num') ? array_values($row) : $row;

            return $row;
        }

        $this->_fetchType = $type;
        $record = $this->_statement->fetch($type);

        if ($record === false) {
            $this->_allFetched = true;
            $this->_counter = $this->_count + 1;
            $this->_statement->closeCursor();

            return false;
        }

        if (is_array($record)) {
            foreach ($record as $key => &$value) {
                if (is_resource($value)) {
                    $value = stream_get_contents($value);
                }
            }
        }

        $this->_count++;

        return $this->_records[] = $record;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $type The type to fetch.
     * @return mixed
     */
    public function fetchAll($type = 'num')
    {
        if ($this->_allFetched) {
            return $this->_records;
        }

        $this->_records = parent::fetchAll($type);
        $this->_count = count($this->_records);
        $this->_allFetched = true;
        $this->_statement->closeCursor();

        return $this->_records;
    }

    /**
     * {@inheritDoc}
     */
    public function rowCount()
    {
        if (!$this->_allFetched) {
            $counter = $this->_counter;
            while ($this->fetch('assoc')) {
            }
            $this->_counter = $counter;
        }

        return $this->_count;
    }

    /**
     * Rewind the _counter property
     *
     * @return void
     */
    public function rewind()
    {
        $this->_counter = 0;
    }

    /**
     * Reset all properties
     *
     * @return void
     */
    protected function _reset()
    {
        $this->_count = $this->_counter = 0;
        $this->_records = [];
        $this->_allFetched = false;
    }
}
