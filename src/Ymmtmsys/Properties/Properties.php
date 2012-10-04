<?php
/**
 * Properties.php
 *
 * @package   Ymmtmsys\Properties
 * @author    ymmtmsys
 * @copyright Copyright (c) 2012 ymmtmsys
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      https://github.com/ymmtmsys/Properties
 */
namespace Ymmtmsys\Properties;

abstract class Properties
{
    private $__prop_reader = array();

    private $__prop_writer = array();

    private $__props = array();

    public function __get($name)
    {
        if ($this->_isReadableProperty($name) === true) {
            return $this->_getPropertyValue($name);
        }

        list($trace) = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace['file'] . ' on line ' . $trace['line'],
            E_USER_ERROR
        );
    }

    public function __set($name, $value)
    {
        if ($this->_isWritableProperty($name) === true) {
            $this->_setPropertyValue($name, $value);
        } else {
            list($trace) = debug_backtrace();
            trigger_error(
                'Undefined property via __set(): ' . $name .
                ' in ' . $trace['file'] . ' on line ' . $trace['line'],
                E_USER_ERROR
            );
        }
    }

    private function _isReadableProperty($name)
    {
        if (isset($this->__prop_reader[$name]) === false) {
            $comment  = $this->_getPropertyComment($name);
            $readable = preg_match('/@(?:accessor|reader)\b/', $comment) === 1;
            $this->__prop_reader[$name] = $readable;
        }

        return $this->__prop_reader[$name];
    }

    private function _isWritableProperty($name)
    {
        if (isset($this->__prop_writer[$name]) === false) {
            $comment  = $this->_getPropertyComment($name);
            $writable = preg_match('/@(?:accessor|writer)\b/', $comment) === 1;
            $this->__prop_writer[$name] = $writable;
        }

        return $this->__prop_writer[$name];
    }

    private function _getPropertyComment($name)
    {
        $prop = $this->_getProperty($name);
        if ($prop === false) {
            return false;
        }
        return $prop->getDocComment();
    }

    private function _getPropertyValue($name)
    {
        $prop = $this->_getProperty($name);
        $prop->setAccessible(true);
        $value = $prop->getValue($this);
        $prop->setAccessible(false);
        return $value;
    }

    private function _setPropertyValue($name, $value)
    {
        $prop = $this->_getProperty($name);
        $prop->setAccessible(true);
        $prop->setValue($this, $value);
        $prop->setAccessible(false);
    }

    private function _getProperty($name)
    {
        if (isset($this->__props[$name]) === false) {
            $this->__props[$name] = self::_getPropertyRecursive(
                get_class($this), $name
            );
        }
        return $this->__props[$name];
    }

    private static function _getPropertyRecursive($klass, $prop_name)
    {
        try {
            return new \ReflectionProperty($klass, $prop_name);
        } catch (\ReflectionException $exp) {
            $obj = new \ReflectionClass($klass);
            $parent_klass = $obj->getParentClass();
            $parent_name  = $parent_klass->getName();
            if ($parent_name === __CLASS__) {
                return false;
            }
            return self::_getPropertyRecursive($parent_name, $prop_name);
        }
    }
}
