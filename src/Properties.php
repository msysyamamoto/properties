<?php
class Properties
{
    private $prop_reader = null;

    private $prop_writer = null;

    public function __get($name)
    {
        if ($this->prop_reader === null) {
            $this->reflect();
        }

        if (isset($this->prop_reader[$name])) {
            return $this->$name;
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
        if ($this->prop_writer === null) {
            $this->reflect();
        }

        if (isset($this->prop_writer[$name])) {
            $this->$name = $value;
            return; 
        }

        list($trace) = debug_backtrace();
        trigger_error(
            'Undefined property via __set(): ' . $name .
            ' in ' . $trace['file'] . ' on line ' . $trace['line'],
            E_USER_ERROR
        );
    }

    public function reflect()
    {
        $this->prop_reader = array(); 
        $this->prop_writer = array(); 

        $ref = new ReflectionClass($this);

        $filter = ReflectionProperty::IS_PROTECTED | ReflectionProperty::IS_PRIVATE;
        $props = $ref->getProperties($filter);

        foreach ($props as $prop) {
            $comments = $prop->getDocComment();
            if (empty($comments)) {
                continue;
            }

            $lines = preg_split('/(?:\r\n|\r|\n)/', $comments, 0, PREG_SPLIT_NO_EMPTY);
            $lines = array_map('trim', $lines);
            foreach ($lines as $line) {
                if (preg_match('/@(reader|writer|accessor)\z/', $line, $m) === 0) {
                    continue;
                }

                $name = $prop->getName();
                switch ($m[1]) {
                    case 'reader':
                        $this->prop_reader[$name] = true;
                        break; 
                    case 'writer':
                        $this->prop_writer[$name] = true;
                        break; 
                    case 'accessor':
                        $this->prop_reader[$name] = true;
                        $this->prop_writer[$name] = true;
                        break; 
                }
                break;
            }
        }
    }
}
