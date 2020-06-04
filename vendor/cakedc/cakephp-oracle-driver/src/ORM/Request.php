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

namespace CakeDC\OracleDriver\ORM;

class Request implements RequestInterface
{
    use RequestTrait;

    /**
     * Initializes the internal properties of this request out of the
     * keys in an array.
     *
     * @param array $properties hash of properties to set in this entity
     * @param array $options list of options to use when creating this entity
     */
    public function __construct(array $properties = [], array $options = [])
    {
        $options += [
            'useSetters' => true,
            'markNew' => null,
            'repository' => null,
        ];
        $this->_className = get_class($this);

        if ($options['markNew'] !== null) {
            $this->isNew($options['markNew']);
        }

        if (!empty($properties)) {
            $this->set($properties, [
                'setter' => $options['useSetters'],
            ]);
        }

        if ($options['repository'] !== null) {
            $this->_repository = $options['repository'];
            $this->_driver = $this->_repository->connection()->getDriver();
            $this->applySchema($this->_repository->schema());
        }
    }
}
