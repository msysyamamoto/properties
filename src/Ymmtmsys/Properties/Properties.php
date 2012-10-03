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
    private $prop_reader = array();

    private $prop_writer = array();

    public function __get($name)
    {
        if ($this->isReadableProperty($name) === true) {
            return $this->getPropertyValue($name);
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
        if ($this->isWritableProperty($name) === true) {
            return $this->setPropertyValue($name, $value);
        } else {
            list($trace) = debug_backtrace();
            trigger_error(
                'Undefined property via __set(): ' . $name .
                ' in ' . $trace['file'] . ' on line ' . $trace['line'],
                E_USER_ERROR
            );
        }
    }

    private function isReadableProperty($name)
    {
        if (property_exists($this, $name) === false) {
            return false;
        }

        if (isset($this->prop_reader[$name]) === false) {
            $comment  = $this->getPropertyComment($name);
            $readable = preg_match('/@(?:accessor|reader)\b/', $comment) === 1;
            $this->prop_reader[$name] = $readable;
        }

        return $this->prop_reader[$name];
    }

    private function isWritableProperty($name)
    {
        if (property_exists($this, $name) === false) {
            return false;
        }

        if (isset($this->prop_writer[$name]) === false) {
            $comment  = $this->getPropertyComment($name);
            $writable = preg_match('/@(?:accessor|writer)\b/', $comment) === 1;
            $this->prop_writer[$name] = $writable;
        }

        return $this->prop_writer[$name];
    }

    private function getPropertyComment($name)
    {
        $prop = new \ReflectionProperty($this, $name);
        return $prop->getDocComment();
    }

    private function getPropertyValue($name)
    {
        $prop = new \ReflectionProperty($this, $name);
        $prop->setAccessible(true);
        $value = $prop->getValue($this);
        $prop->setAccessible(false);
        return $value;
    }

    private function setPropertyValue($name, $value)
    {
        $prop = new \ReflectionProperty($this, $name);
        $prop->setAccessible(true);
        $prop->setValue($this, $value);
        $prop->setAccessible(false);
    }
}
