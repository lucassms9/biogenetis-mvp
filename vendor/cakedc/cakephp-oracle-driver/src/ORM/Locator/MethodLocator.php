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

namespace CakeDC\OracleDriver\ORM\Locator;

use CakeDC\OracleDriver\Database\OracleConnection;
use CakeDC\OracleDriver\ORM\Method;
use Cake\Core\App;
use Cake\Datasource\ConnectionManager;
use Cake\Utility\Inflector;
use RuntimeException;

/**
 * Provides a default registry/factory for Method objects.
 */
class MethodLocator implements LocatorInterface
{

    /**
     * Configuration for aliases.
     *
     * @var array
     */
    protected $_config = [];

    /**
     * Instances that belong to the registry.
     *
     * @var array
     */
    protected $_instances = [];

    /**
     * Contains a list of Method objects that were created out of the
     * built-in Method class. The list is indexed by method names
     *
     * @var array
     */
    protected $_fallbacked = [];

    /**
     * Contains a list of options that were passed to get() method.
     *
     * @var array
     */
    protected $_options = [];

    /**
     * Stores a list of options to be used when instantiating an object
     * with a matching alias.
     *
     * The options that can be stored are those that are recognized by `get()`
     * If second argument is omitted, it will return the current settings
     * for $alias.
     *
     * If no arguments are passed it will return the full configuration array for
     * all aliases
     *
     * @param string|null $alias Name of the alias
     * @param array|null $options list of options for the alias
     * @return array The config data.
     * @throws RuntimeException When you attempt to configure an existing method instance.
     */
    public function config($alias = null, $options = null)
    {
        if ($alias === null) {
            return $this->_config;
        }
        if (!is_string($alias)) {
            return $this->_config = $alias;
        }
        if ($options === null) {
            return isset($this->_config[$alias]) ? $this->_config[$alias] : [];
        }
        if (isset($this->_instances[$alias])) {
            throw new RuntimeException(sprintf(
                'You cannot configure "%s", it has already been constructed.',
                $alias
            ));
        }

        return $this->_config[$alias] = $options;
    }

    /**
     * Get a method instance from the registry.
     *
     * Methods are only created once until the registry is flushed.
     * This means that aliases must be unique across your application.
     * This is important because method associations are resolved at runtime
     * and cyclic references need to be handled correctly.
     *
     * The options that can be passed are the same as in `Method::__construct()`, but the
     * key `className` is also recognized.
     *
     * If $options does not contain `className` CakePHP will attempt to construct the
     * class name based on the alias. If this class does not exist,
     * then the default `CakeDC\OracleDriver\ORM\Method` class will be used. By setting the `className`
     * option you can define the specific class to use. This className can
     * use a plugin short class reference.
     *
     * If you use a `$name` that uses plugin syntax only the name part will be used as
     * key in the registry. This means that if two plugins, or a plugin and app provide
     * the same alias, the registry will only store the first instance.
     *
     * If no `method` option is passed, the method name will be the underscored version
     * of the provided $alias.
     *
     * If no `connection` option is passed the method's defaultConnectionName() method
     * will be called to get the default connection name to use.
     *
     * @param string $alias The alias name you want to get.
     * @param array $options The options you want to build the method with.
     *   If a method has already been loaded the options will be ignored.
     * @return \CakeDC\OracleDriver\ORM\Method
     * @throws \RuntimeException When you try to configure an alias that already exists.
     */
    public function get($alias, array $options = [])
    {
        if (isset($this->_instances[$alias])) {
            if (!empty($options) && $this->_options[$alias] !== $options) {
                throw new RuntimeException(sprintf(
                    'You cannot configure "%s", it already exists in the registry.',
                    $alias
                ));
            }

            return $this->_instances[$alias];
        }

        $this->_options[$alias] = $options;
        list(, $classAlias) = pluginSplit($alias);
        $options = ['alias' => $classAlias] + $options;

        if (isset($this->_config[$alias])) {
            $options += $this->_config[$alias];
        }

        if (empty($options['className'])) {
            $options['className'] = Inflector::camelize($alias);
        }

        $className = $this->_getClassName($alias, $options);
        if ($className) {
            $options['className'] = $className;
            $options['method'] = Inflector::underscore($alias);
        } else {
            if (!isset($options['method']) && strpos($options['className'], '\\') === false) {
                list(, $method) = pluginSplit($options['className']);
                $options['method'] = Inflector::underscore($method);
            }
            $options['className'] = 'CakeDC\OracleDriver\ORM\Method';
        }

        if (empty($options['connection'])) {
            $connectionName = $options['className']::defaultConnectionName();
            $options['connection'] = ConnectionManager::get($connectionName);
        }
        if (!($options['connection'] instanceof OracleConnection)) {
            $options['connection'] = OracleConnection::build($options['connection']);
        }

        $options['registryAlias'] = $alias;
        $this->_instances[$alias] = $this->_create($options);

        if ($options['className'] === 'CakeDC\OracleDriver\ORM\Method') {
            $this->_fallbacked[$alias] = $this->_instances[$alias];
        }

        return $this->_instances[$alias];
    }

    /**
     * Gets the method class name.
     *
     * @param string $alias The alias name you want to get.
     * @param array $options Method options array.
     * @return string
     */
    protected function _getClassName($alias, array $options = [])
    {
        if (empty($options['className'])) {
            $options['className'] = Inflector::camelize($alias);
        }

        return App::className($options['className'], 'Model/Method', 'Method');
    }

    /**
     * Wrapper for creating method instances
     *
     * @param array $options The alias to check for.
     * @return \CakeDC\OracleDriver\ORM\Method
     */
    protected function _create(array $options)
    {
        return new $options['className']($options);
    }

    /**
     * {@inheritDoc}
     */
    public function exists($alias)
    {
        return isset($this->_instances[$alias]);
    }

    /**
     * {@inheritDoc}
     */
    public function set($alias, Method $object)
    {
        return $this->_instances[$alias] = $object;
    }

    /**
     * {@inheritDoc}
     */
    public function clear()
    {
        $this->_instances = [];
        $this->_config = [];
        $this->_fallbacked = [];
    }

    /**
     * Returns the list of methods that were created by this registry that could
     * not be instantiated from a specific subclass. This method is useful for
     * debugging common mistakes when setting up associations or created new method
     * classes.
     *
     * @return array
     */
    public function genericInstances()
    {
        return $this->_fallbacked;
    }

    /**
     * {@inheritDoc}
     */
    public function remove($alias)
    {
        unset(
            $this->_instances[$alias],
            $this->_config[$alias],
            $this->_fallbacked[$alias]
        );
    }
}
